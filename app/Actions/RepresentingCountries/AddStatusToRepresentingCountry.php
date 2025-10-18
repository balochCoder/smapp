<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;

final class AddStatusToRepresentingCountry
{
    public function handle(RepresentingCountry $representingCountry, string $statusName): RepCountryStatus
    {
        // Get the max order for this representing country
        $maxOrder = $representingCountry->repCountryStatuses()->max('order') ?? 0;

        return RepCountryStatus::create([
            'representing_country_id' => $representingCountry->id,
            'status_name' => $statusName,
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);
    }
}

