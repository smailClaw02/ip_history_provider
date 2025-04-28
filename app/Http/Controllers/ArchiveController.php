<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public static function sources()
    {
        $sources = Source::all();
        Storage::disk("local")->put(
            "/json/sources.json",
            $sources->toJson()
        );
    }
    
    // ... keep your other existing methods ...
}