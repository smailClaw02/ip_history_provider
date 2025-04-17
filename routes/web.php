<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SourceController;  

Route::get('/', function () {
    return view('welcome');
});


Route::resource('sources', SourceController::class);
Route::post('/emails', 'EmailController@store')->name('emails.store');

Route::prefix('tools')->group(function () {
    Route::get('random', function () {
        return view('tools.random');
    })->name('tools.random');
});