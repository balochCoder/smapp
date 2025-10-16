<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;
use App\Models\SubStatus;

final class AddSubStatusToStatus
{
    public function handle(RepCountryStatus $status, array $data): SubStatus
    {
        // Get the max order for this status's sub-statuses
        $maxOrder = $status->subStatuses()->max('order') ?? 0;

        return $status->subStatuses()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);
    }
}

