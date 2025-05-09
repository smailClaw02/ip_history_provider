<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SourceController;


Route::get('/', function () {
    return view('welcome');
});


Route::resource('sources', SourceController::class);

Route::get('/archive-sources', function() {
    App\Http\Controllers\ArchiveController::sources();
    return back()->with('success', 'Sources archived successfully!');
});

// In routes/web.php
Route::post('/archive-sources', function() {
    App\Http\Controllers\ArchiveController::sources();
    return back()->with('success', 'Sources archived successfully!');
})->name('archive.sources');


Route::prefix('tools')->group(function () {
    // random
    Route::get('random', function () {
        return view('tools.random');
    })->name('tools.random');
    
    // copy-count
    Route::get('copy-count', function () {
        return view('tools.copy_count');
    })->name('tools.copy-count');
    
    // x-delay
    Route::get('x-delay', function () {
        return view('tools.x_delay');
    })->name('tools.x-delay');

    // End Time Drop
    Route::get('end-time-drop', function() {
        return view('tools.end_time_drop');
    })->name('tools.end-time-drop');

    // Spf  Dmarc
    Route::get('spf-dmarc', function() {
        return view('tools.spf_dmarc');
    })->name('tools.spf-dmarc');

    // Body Filter
    Route::get('body-filter', function() {
        return view('tools.body');
    })->name('tools.body-filter');

    // Header
    Route::get('header-processor', function() {
        return view('tools.header');
    })->name('tools.header-processor');

    Route::get('cpanel-checker', [\App\Http\Controllers\Tools\CpanelCheckerController::class, 'index'])
        ->name('tools.cpanel-checker');
    Route::get('cpanel-checker/stats', [\App\Http\Controllers\Tools\CpanelCheckerController::class, 'stats'])
        ->name('tools.cpanel-checker.stats');
    Route::post('cpanel-checker/start', [\App\Http\Controllers\Tools\CpanelCheckerController::class, 'start'])
        ->name('tools.cpanel-checker.start');

});

