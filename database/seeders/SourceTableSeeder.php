<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optionally clear existing data (commented out as it might not always be desired)
        // Source::truncate();

        // Load data from JSON file using Storage facade
        $json = Storage::disk('private')->get('json/sources.json');
        $sources = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse JSON: ' . json_last_error_msg());
        }

        foreach ($sources as $source) {
            Source::create($source);
        }

        // Archive after seeding (if this is still needed)
        \App\Http\Controllers\ArchiveController::sources();
    }
}