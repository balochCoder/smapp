<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\SubStatus;

final class UpdateSubStatus
{
    public function handle(SubStatus $subStatus, array $data): SubStatus
    {
        $subStatus->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $subStatus->fresh();
    }
}

