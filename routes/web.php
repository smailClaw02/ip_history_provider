<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SourceController;  

Route::get('/', function () {
    return view('welcome');
});


Route::resource('sources', SourceController::class);
Route::post('/emails', 'EmailController@store')->name('emails.store');

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
});

