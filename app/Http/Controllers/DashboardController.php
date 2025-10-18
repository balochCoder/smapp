<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

final class DashboardController extends Controller
{
    /**
     * Smart dashboard redirect based on user's primary role.
     * Redirects users to their role-specific dashboard.
     */
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        // Platform users (SuperAdmin, Support)
        if ($user->hasRole('SuperAdmin')) {
            return redirect()->route('platform.dashboard');
        }

        if ($user->hasRole('Support')) {
            return redirect()->route('platform.support-dashboard');
        }

        // Tenant users - redirect based on primary role
        // Priority order: Admin > BranchManager > Counsellor > ProcessingOfficer > FrontOffice > Finance
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('BranchManager')) {
            return redirect()->route('branch.dashboard');
        }

        if ($user->hasRole('Counsellor')) {
            return redirect()->route('counsellor.dashboard');
        }

        if ($user->hasRole('ProcessingOfficer')) {
            return redirect()->route('processing.dashboard');
        }

        if ($user->hasRole('FrontOffice')) {
            return redirect()->route('frontoffice.dashboard');
        }

        if ($user->hasRole('Finance')) {
            return redirect()->route('finance.dashboard');
        }

        // Fallback for users without roles
        abort(403, 'No role assigned. Please contact your administrator.');
    }
}
