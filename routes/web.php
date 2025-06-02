<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\Tools\OfferController;


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

    Route::prefix('offers')->group(function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');
        Route::get('/create', [OfferController::class, 'create'])->name('create');
        Route::post('/', [OfferController::class, 'store'])->name('store');
        Route::get('/{offer}', [OfferController::class, 'show'])->name('show');
        Route::get('/{offer}/edit', [OfferController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], 'offers/{offer}', [OfferController::class, 'update'])->name('update');
        Route::delete('/{offer}', [OfferController::class, 'destroy'])->name('destroy');
        Route::post('/{offer}/increment-lead', [OfferController::class, 'incrementLead'])
             ->name('tools.offers.increment-lead');
        Route::post('/{offer}/decrement-lead', [OfferController::class, 'decrementLead'])
             ->name('tools.offers.decrement-lead');
    });

});

