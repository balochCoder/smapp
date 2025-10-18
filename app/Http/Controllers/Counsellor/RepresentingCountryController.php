<?php

declare(strict_types=1);

namespace App\Http\Controllers\Counsellor;

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

    /**
     * Display a simplified listing of active representing countries.
     * Counsellors can only view basic information.
     */
    public function index(Request $request): Response
    {
        $this->authorize('view-representing-countries');

        // Counsellors only see active countries with basic info
        $representingCountries = RepresentingCountry::query()
            ->with(['country', 'repCountryStatuses' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('order')
                    ->select('id', 'representing_country_id', 'status_name', 'custom_name', 'order', 'is_active');
            }])
            ->where('is_active', true)
            ->when($request->input('country'), function ($query, $countryId) {
                $query->where('country_id', $countryId);
            })
            ->orderBy('name')
            ->get();

        // Get all active countries for the filter
        $availableCountries = Country::query()
            ->whereHas('representingCountry', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'flag']);

        return Inertia::render('counsellor/representing-countries/index', [
            'representingCountries' => RepresentingCountryResource::collection($representingCountries),
            'availableCountries' => $availableCountries,
            'filters' => [
                'country' => $request->input('country'),
            ],
            'permissions' => [
                'canCreate' => false,
                'canEdit' => false,
                'canDelete' => false,
                'canManageStatus' => false,
            ],
        ]);
    }

    /**
     * Display a simplified view of the representing country.
     * Counsellors can view basic details only.
     */
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
                    ->orderBy('order')
                    ->select('id', 'representing_country_id', 'status_name', 'custom_name', 'order', 'is_active');
            },
        ]);

        return Inertia::render('counsellor/representing-countries/show', [
            'representingCountry' => new RepresentingCountryResource($representingCountry),
            'permissions' => [
                'canEdit' => false,
                'canDelete' => false,
                'canManageStatus' => false,
            ],
        ]);
    }
}
