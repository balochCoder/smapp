<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;
use Illuminate\Support\Facades\DB;

final class DeleteStatus
{
    public function handle(RepCountryStatus $status): void
    {
        DB::transaction(function () use ($status) {
            // Soft delete all sub-statuses first
            $status->subStatuses()->delete();

            // Then soft delete the status
            $status->delete();
        });
    }
}

