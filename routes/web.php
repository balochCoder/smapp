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
    Route::get('representing-countries/{representingCountry}/reorder', [App\Http\Controllers\RepresentingCountryController::class, 'reorder'])->name('representing-countries.reorder');
    Route::post('representing-countries/{representingCountry}/reorder', [App\Http\Controllers\RepresentingCountryController::class, 'updateOrder'])->name('representing-countries.update-order');
    Route::post('representing-countries/{representingCountry}/toggle-active', [App\Http\Controllers\RepresentingCountryController::class, 'toggleActive'])->name('representing-countries.toggle-active');
    Route::post('representing-countries/{representingCountry}/toggle-status-active', [App\Http\Controllers\RepresentingCountryController::class, 'toggleStatusActive'])->name('representing-countries.toggle-status-active');
    Route::put('representing-countries/{representingCountry}/update-status-name', [App\Http\Controllers\RepresentingCountryController::class, 'updateStatusName'])->name('representing-countries.update-status-name');
    Route::post('representing-countries/{representingCountry}/add-status', [App\Http\Controllers\RepresentingCountryController::class, 'addStatus'])->name('representing-countries.add-status');
    Route::delete('representing-countries/{representingCountry}/status/{status}', [App\Http\Controllers\RepresentingCountryController::class, 'deleteStatus'])->name('representing-countries.delete-status');
    Route::post('representing-countries/{representingCountry}/status/{status}/add-substatus', [App\Http\Controllers\RepresentingCountryController::class, 'addSubStatus'])->name('representing-countries.add-substatus');
    Route::put('representing-countries/{representingCountry}/status/{status}/substatus/{subStatus}', [App\Http\Controllers\RepresentingCountryController::class, 'updateSubStatus'])->name('representing-countries.update-substatus');
    Route::delete('representing-countries/{representingCountry}/status/{status}/substatus/{subStatus}', [App\Http\Controllers\RepresentingCountryController::class, 'deleteSubStatus'])->name('representing-countries.delete-substatus');

    // Application Processes Management
    Route::resource('application-processes', App\Http\Controllers\ApplicationProcessController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
