<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index()
    {
        $sources = Source::orderBy('date', 'desc')->paginate(15);
        return view('sources.index', compact('sources'));
    }

    public function create()
    {
        return view('sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'provider_ip' => 'nullable|ip',
            'from' => 'required|email',
            'spf' => 'required|in:pass,fail,softfail,neutral,none,permerror,temperror',
            'dkim' => 'required|in:pass,fail,none,permerror,temperror,policy',
            'header' => 'required|string',
            'body' => 'required|string',
        ]);

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
            'provider_ip' => 'nullable|ip',
            'from' => 'required|email',
            'spf' => 'required|in:pass,fail,softfail,neutral,none,permerror,temperror',
            'dkim' => 'required|in:pass,fail,none,permerror,temperror,policy',
            'header' => 'required|string',
            'body' => 'required|string',
        ]);

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