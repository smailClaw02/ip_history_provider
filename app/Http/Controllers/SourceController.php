<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Source::query();

        // Date filtering (only if date_filter parameter exists)
        if ($request->filled('date_filter')) {
            $date = Carbon::parse($request->date_filter);
            $query->whereDate('date', $date);
        }

        // IP search
        if ($request->filled('ip_search')) {
            $query->where('ip', 'like', '%' . $request->ip_search . '%');
        }

        // ID sorting (default to date desc if no sort specified)
        if ($request->filled('sort')) {
            $query->orderBy('id', $request->sort);
        }
        
        // Always sort by date descending as secondary sort
        $query->orderBy('date', 'desc');

        $sources = $query->paginate(20);

        // IP grouping by first two octets
        $ipGroups = Source::selectRaw(
            "SUBSTRING_INDEX(ip, '.', 2) as ip_prefix, COUNT(*) as count"
        )
            ->groupBy('ip_prefix')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'ip_prefix');

        return view('sources.index', compact('sources', 'ipGroups'));
    }

    public function create()
    {
        return view('sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(Source::validationRules());
        $validated['date'] = Carbon::parse($validated['date']);

        Source::create($validated);

        // Archive after creation
        ArchiveController::sources();

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
        $validated = $request->validate(Source::validationRules($source->id));
        $source->update($validated);
        
        // Archive after update
        ArchiveController::sources();

        return redirect()->route('sources.index')
            ->with('success', 'Source updated successfully.');
    }

    public function destroy(Source $source)
    {
        $source->delete();

        // Archive after deletion
        ArchiveController::sources();

        return redirect()->route('sources.index')
            ->with('success', 'Source deleted successfully.');
    }
}
