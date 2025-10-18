<?php

declare(strict_types=1);

use App\Http\Controllers\Admin;
use App\Http\Controllers\Branch;
use App\Http\Controllers\Counsellor;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Smart dashboard redirect (redirects to role-specific dashboard)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// ADMIN ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('dashboard', fn () => Inertia::render('admin/dashboard'))->name('dashboard');

        // Admin - Full Representing Countries Management
        Route::resource('representing-countries', Admin\RepresentingCountryController::class);
        Route::prefix('representing-countries/{representingCountry}')->name('representing-countries.')->group(function () {
            Route::get('notes', [Admin\RepresentingCountryController::class, 'notes'])->name('notes');
            Route::post('notes', [Admin\RepresentingCountryController::class, 'updateNotes'])->name('update-notes');
            Route::get('reorder', [Admin\RepresentingCountryController::class, 'reorder'])->name('reorder');
            Route::post('reorder', [Admin\RepresentingCountryController::class, 'updateOrder'])->name('update-order');
            Route::post('toggle-active', [Admin\RepresentingCountryController::class, 'toggleActive'])->name('toggle-active');
            Route::post('toggle-status-active', [Admin\RepresentingCountryController::class, 'toggleStatusActive'])->name('toggle-status-active');
            Route::put('update-status-name', [Admin\RepresentingCountryController::class, 'updateStatusName'])->name('update-status-name');
            Route::post('add-status', [Admin\RepresentingCountryController::class, 'addStatus'])->name('add-status');
            Route::delete('status/{status}', [Admin\RepresentingCountryController::class, 'deleteStatus'])->name('delete-status');
            Route::post('status/{status}/add-substatus', [Admin\RepresentingCountryController::class, 'addSubStatus'])->name('add-substatus');
            Route::put('status/{status}/substatus/{subStatus}', [Admin\RepresentingCountryController::class, 'updateSubStatus'])->name('update-substatus');
            Route::post('status/{status}/substatus/{subStatus}/toggle-active', [Admin\RepresentingCountryController::class, 'toggleSubStatusActive'])->name('toggle-substatus-active');
            Route::delete('status/{status}/substatus/{subStatus}', [Admin\RepresentingCountryController::class, 'deleteSubStatus'])->name('delete-substatus');
        });
    });

// ============================================
// BRANCH MANAGER ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:BranchManager'])
    ->prefix('branch')
    ->name('branch.')
    ->group(function () {
        // Branch Dashboard
        Route::get('dashboard', fn () => Inertia::render('branch/dashboard'))->name('dashboard');

        // Branch - Limited Representing Countries (view only - no create/edit/delete/notes/reorder)
        Route::get('representing-countries', [Branch\RepresentingCountryController::class, 'index'])->name('representing-countries.index');
        Route::get('representing-countries/{representingCountry}', [Branch\RepresentingCountryController::class, 'show'])->name('representing-countries.show');
    });

// ============================================
// COUNSELLOR ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:Counsellor'])
    ->prefix('counsellor')
    ->name('counsellor.')
    ->group(function () {
        // Counsellor Dashboard
        Route::get('dashboard', fn () => Inertia::render('counsellor/dashboard'))->name('dashboard');

        // Counsellor - View only representing countries
        Route::get('representing-countries', [Counsellor\RepresentingCountryController::class, 'index'])->name('representing-countries.index');
        Route::get('representing-countries/{representingCountry}', [Counsellor\RepresentingCountryController::class, 'show'])->name('representing-countries.show');
    });

// ============================================
// PROCESSING OFFICER ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:ProcessingOfficer'])
    ->prefix('processing')
    ->name('processing.')
    ->group(function () {
        Route::get('dashboard', fn () => Inertia::render('processing/dashboard'))->name('dashboard');
        // Add processing officer specific routes here
    });

// ============================================
// FRONT OFFICE ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:FrontOffice'])
    ->prefix('frontoffice')
    ->name('frontoffice.')
    ->group(function () {
        Route::get('dashboard', fn () => Inertia::render('frontoffice/dashboard'))->name('dashboard');
        // Add front office specific routes here
    });

// ============================================
// FINANCE ROLE ROUTES
// ============================================
Route::middleware(['auth', 'verified', 'role:Finance'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('dashboard', fn () => Inertia::render('finance/dashboard'))->name('dashboard');
        // Add finance specific routes here
    });

// ============================================
// PLATFORM ROUTES (SuperAdmin, Support)
// ============================================
Route::middleware(['auth', 'verified', 'role:SuperAdmin|Support'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {
        Route::get('dashboard', fn () => Inertia::render('platform/dashboard'))->name('dashboard');
        Route::get('support-dashboard', fn () => Inertia::render('platform/support-dashboard'))->name('support-dashboard');
        // Add platform management routes here
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
