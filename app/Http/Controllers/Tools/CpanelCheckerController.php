<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CpanelCheckerController extends Controller
{
    private $statsKey;
    private $resultsKey;
    
    public function __construct()
    {
        $this->statsKey = 'cpanel_checker_stats';
        $this->resultsKey = 'cpanel_checker_results';
    }
    
    public function index()
    {
        $stats = Cache::get($this->statsKey, [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'start_time' => null,
            'end_time' => null,
            'is_running' => false
        ]);
        
        $results = Cache::get($this->resultsKey, []);
        
        $darkMode = request()->cookie('darkMode') === 'true';
        
        return view('tools.cpanel_checker', [
            'stats' => $stats,
            'results' => $results,
            'isRunning' => $stats['is_running'],
            'darkMode' => $darkMode
        ]);
    }
    
    public function stats()
    {
        $stats = Cache::get($this->statsKey, [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'start_time' => null,
            'end_time' => null,
            'is_running' => false
        ]);
        
        return response()->json([
            'total' => $stats['total'],
            'success' => $stats['success'],
            'failed' => $stats['failed'],
            'start_time' => $stats['start_time'] ? $stats['start_time']->toDateTimeString() : null,
            'end_time' => $stats['end_time'] ? $stats['end_time']->toDateTimeString() : null,
            'is_running' => $stats['is_running']
        ]);
    }
    
    public function start(Request $request)
    {
        $content = $request->input('content');
        $threads = (int) $request->input('threads', 10);
        $outputFile = $request->input('outputFile', 'success_cpanels.txt');
        
        if (empty($content)) {
            return response()->json(['success' => false, 'message' => 'No content provided']);
        }
        
        // Parse the content
        $urls = [];
        foreach (explode("\n", $content) as $line) {
            $parts = explode('|', trim($line));
            if (count($parts) === 3) {
                $urls[] = $parts;
            }
        }
        
        if (empty($urls)) {
            return response()->json(['success' => false, 'message' => 'No valid cPanel entries found']);
        }
        
        // Update stats
        $stats = [
            'total' => count($urls),
            'success' => 0,
            'failed' => 0,
            'start_time' => now(),
            'end_time' => null,
            'is_running' => true
        ];
        
        Cache::put($this->statsKey, $stats);
        Cache::put($this->resultsKey, []);
        
        // Start checking in background
        dispatch(function () use ($urls, $threads, $outputFile) {
            $this->runChecks($urls, $threads, $outputFile);
        });
        
        return response()->json(['success' => true]);
    }
    
    private function runChecks($urls, $threads, $outputFile)
    {
        $batchId = Str::uuid();
        $chunks = array_chunk($urls, ceil(count($urls) / $threads));
        
        $results = [];
        $success = 0;
        $failed = 0;
        
        foreach ($chunks as $chunk) {
            foreach ($chunk as $urlInfo) {
                [$url, $username, $password] = $urlInfo;
                
                try {
                    $result = $this->checkCpanel($url, $username, $password);
                    $result['status'] = 'Success';
                    $success++;
                    
                    // Save successful logins
                    Storage::append($outputFile, "{$url}|{$username}|{$password}");
                } catch (\Exception $e) {
                    $result = [
                        'url' => $url,
                        'username' => $username,
                        'status' => 'Failed',
                        'error' => $e->getMessage(),
                        'domains' => 'N/A'
                    ];
                    $failed++;
                }
                
                $results[] = $result;
                
                // Update stats periodically
                if (count($results) % 5 === 0) {
                    $stats = Cache::get($this->statsKey);
                    $stats['success'] = $success;
                    $stats['failed'] = $failed;
                    Cache::put($this->statsKey, $stats);
                    Cache::put($this->resultsKey, $results);
                }
            }
        }
        
        // Final update
        $stats = Cache::get($this->statsKey);
        $stats['success'] = $success;
        $stats['failed'] = $failed;
        $stats['end_time'] = now();
        $stats['is_running'] = false;
        Cache::put($this->statsKey, $stats);
        Cache::put($this->resultsKey, $results);
    }
    
    private function checkCpanel($url, $username, $password)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false,
            'timeout' => 20,
            'allow_redirects' => true
        ]);
        
        // Login
        $response = $client->post("{$url}/login/?login_only=1", [
            'form_params' => [
                'user' => $username,
                'pass' => $password
            ]
        ]);
        
        $loginData = json_decode($response->getBody(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !isset($loginData['security_token'])) {
            throw new \Exception('Invalid login response');
        }
        
        $cpsessToken = substr($loginData['security_token'], 7);
        
        // Get domains data
        $response = $client->post(
            "{$url}/cpsess{$cpsessToken}/execute/DomainInfo/domains_data",
            [
                'form_params' => ['return_https_redirect_status' => '1']
            ]
        );
        
        $domainsData = json_decode($response->getBody(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !isset($domainsData['status'])) {
            throw new \Exception('Invalid domains data response');
        }
        
        $totalDomains = 1; // Main domain
        
        if ($domainsData['status'] == 1) {
            $totalDomains += count($domainsData['data']['sub_domains'] ?? []);
            $totalDomains += count($domainsData['data']['addon_domains'] ?? []);
        }
        
        return [
            'url' => $url,
            'username' => $username,
            'domains' => $totalDomains,
            'error' => null
        ];
    }

}