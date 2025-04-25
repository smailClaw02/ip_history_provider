<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Source::query()->orderBy('date', 'desc');

        if ($request->has('ip_search') && !empty($request->ip_search)) {
            $searchTerm = $request->ip_search;
            $query->where('ip', 'like', '%' . $searchTerm . '%');
        }

        $sources = $query->paginate(10);

        $allIps = Source::pluck('ip')->toArray();
        $ipGroups = [];

        foreach ($allIps as $ip) {
            $ipParts = explode('.', $ip);
            $prefix = count($ipParts) >= 3 ? implode('.', array_slice($ipParts, 0, 3)) : $ip;
            $ipGroups[$prefix] = ($ipGroups[$prefix] ?? 0) + 1;
        }

        return view('sources.index', compact('sources', 'ipGroups'));
    }

    public function create()
    {
        return view('sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'provider_ip' => 'nullable|string',
            'from' => 'required|string',
            'spf' => 'required|in:pass,fail,softfail,neutral,none,permerror,temperror',
            'dkim' => 'required|in:pass,fail,none,permerror,temperror,policy',
            'dmarc' => 'required|in:pass,fail,none,permerror,temperror,bestguesspass',
            'header' => 'required|string',
            'body' => 'required|string',
            'vmta' => 'nullable|string',
            'return_path' => 'required|string',
            'date' => 'required|date',
            'email' => 'required|string',
            'message_path' => 'required|in:inbox,spam',
            'colonne' => 'nullable|string',
            'redirect_link' => 'nullable|string',
        ]);

        $validated['date'] = Carbon::parse($validated['date']);

        Source::create($validated);

        return redirect()->route('sources.index')
            ->with('success', 'Source created successfully.');
    }

    public function show(Source $source)
    {
        return view('sources.show', compact('source'));
    }

    public function edit(Source $source)
    {
        return view('sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'provider_ip' => 'nullable|string',
            'from' => 'required|string',
            'spf' => 'required|in:pass,fail,softfail,neutral,none,permerror,temperror',
            'dkim' => 'required|in:pass,fail,none,permerror,temperror,policy',
            'dmarc' => 'required|in:pass,fail,none,permerror,temperror,bestguesspass',
            'header' => 'required|string',
            'body' => 'required|string',
            'vmta' => 'nullable|string',
            'return_path' => 'required|string',
            'date' => 'required|date',
            'email' => 'required|string',
            'message_path' => 'required|in:inbox,spam',
            'colonne' => 'nullable|string',
            'redirect_link' => 'nullable|string',
        ]);

        $validated['date'] = Carbon::parse($validated['date']);
        $source->update($validated);

        return redirect()->route('sources.index')
            ->with('success', 'Source updated successfully.');
    }

    public function destroy(Source $source)
    {
        $source->delete();
        return redirect()->route('sources.index')
            ->with('success', 'Source deleted successfully.');
    }
}