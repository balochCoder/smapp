<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepresentingCountry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetFilteredRepresentingCountries
{
    /**
     * Get filtered and paginated representing countries.
     */
    public function handle(?string $countryId = null, ?string $status = null, int $perPage = 12): LengthAwarePaginator
    {
        $query = RepresentingCountry::query()
            ->with([
                'country',
                'repCountryStatuses' => function ($query) {
                    $query->with('subStatuses')->orderBy('order');
                },
            ]);

        // Apply country filter
        if ($countryId !== null) {
            $query->where('country_id', $countryId);
        }

        // Apply status filter
        if ($status !== null) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }
}
