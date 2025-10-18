<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;

final class UpdateStatusName
{
    public function handle(RepCountryStatus $status, string $customName): RepCountryStatus
    {
        $status->update([
            'custom_name' => $customName,
        ]);

        return $status->fresh();
    }
}

