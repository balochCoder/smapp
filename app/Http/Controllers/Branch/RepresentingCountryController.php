<?php

declare(strict_types=1);

namespace App\Http\Controllers\Branch;

use App\Actions\RepresentingCountries\GetFilteredRepresentingCountries;
use App\Http\Controllers\Controller;
use App\Http\Resources\RepresentingCountryResource;
use App\Models\Country;
use App\Models\RepresentingCountry;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class RepresentingCountryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, GetFilteredRepresentingCountries $getFilteredAction): Response
    {
        $this->authorize('view-representing-countries');

        // Use the same action as Admin but filter to active only
        // Force status to 'active' for branch users
        $representingCountries = $getFilteredAction->handle(
            $request->input('country'),
            'active' // Always show only active countries for branch users
        );

        // Get all active countries for the filter
        $availableCountries = Country::query()
            ->whereHas('representingCountry', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'flag']);

        // Get statistics (only active countries for branch)
        $totalCountries = RepresentingCountry::where('is_active', true)->count();
        $activeCountries = $totalCountries;

        return Inertia::render('branch/representing-countries/index', [
            'representingCountries' => RepresentingCountryResource::collection($representingCountries),
            'availableCountries' => $availableCountries,
            'filters' => [
                'country' => $request->input('country'),
            ],
            'statistics' => [
                'totalCountries' => $totalCountries,
                'activeCountries' => $activeCountries,
            ],
            'permissions' => [
                'canCreate' => false,
                'canEdit' => false,
                'canDelete' => false,
                'canManageStatus' => false,
            ],
        ]);
    }

    public function show(RepresentingCountry $representingCountry): Response
    {
        $this->authorize('view-representing-countries');

        // Only allow viewing active countries
        if (! $representingCountry->is_active) {
            abort(404);
        }

        $representingCountry->load([
            'country',
            'repCountryStatuses' => function ($query) {
                $query->where('is_active', true)
                    ->with('subStatuses')
                    ->orderBy('order');
            },
        ]);

        return Inertia::render('branch/representing-countries/show', [
            'representingCountry' => new RepresentingCountryResource($representingCountry),
            'permissions' => [
                'canEdit' => false,
                'canDelete' => false,
                'canManageStatus' => false,
            ],
        ]);
    }
}
