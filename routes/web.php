<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Representing Countries Management
    Route::resource('representing-countries', App\Http\Controllers\RepresentingCountryController::class);
    Route::get('representing-countries/{representingCountry}/notes', [App\Http\Controllers\RepresentingCountryController::class, 'notes'])->name('representing-countries.notes');
    Route::post('representing-countries/{representingCountry}/notes', [App\Http\Controllers\RepresentingCountryController::class, 'updateNotes'])->name('representing-countries.update-notes');

    // Application Processes Management
    Route::resource('application-processes', App\Http\Controllers\ApplicationProcessController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
