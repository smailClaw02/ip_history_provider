<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::paginate(10);
        return view('tools.offers.list_offers', compact('offers'));
    }

    public function create()
    {
        return view('tools.offers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['offers_text' => 'required|string']);

        $lines = explode("\n", $request->offers_text);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            preg_match('/^([A-Z]\d+)([a-zA-Z]+)(\d+)[A-Z]*$/', $line, $matches);
            
            if (count($matches) >= 4) {
                Offer::create([
                    'id_offer' => $matches[3],
                    'category' => $matches[1],
                    'name' => $matches[2],
                    // 'img' => 'https://i.imgur.com/VdRMcGA.png',
                    // Other fields use defaults
                ]);
            }
        }

        return redirect()->route('index')->with('success', 'Offers added successfully!');
    }

    public function show(Offer $offer)
    {
        return view('tools.offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        return view('tools.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'id_offer' => 'required|string',
            'category' => 'required|string',
            'name' => 'required|string',
            'rev' => 'nullable|numeric',
            'img' => 'nullable|string',
            'link_img' => 'nullable|string',
            'link_uns' => 'nullable|string',
            'from' => 'nullable|string',
            'sub' => 'nullable|string',
        ]);

        $offer->update($validated);
        return redirect()->route('index')->with('success', 'Offer updated successfully');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('index')->with('success', 'Offer deleted successfully');
    }

    public function incrementLead(Offer $offer)
    {
        try {
            $offer->increment('count_lead');
            return response()->json([
                'success' => true,
                'newCount' => $offer->count_lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lead count'
            ], 500);
        }
    }

    public function decrementLead(Offer $offer)
    {
        try {
            if ($offer->count_lead > 0) {
                $offer->decrement('count_lead');
            }
            return response()->json([
                'success' => true,
                'newCount' => $offer->count_lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lead count'
            ], 500);
        }
    }
}