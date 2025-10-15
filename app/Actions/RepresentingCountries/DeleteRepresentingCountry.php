<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepresentingCountry;
use Illuminate\Support\Facades\DB;

final class DeleteRepresentingCountry
{
    public function handle(RepresentingCountry $representingCountry): bool
    {
        return DB::transaction(function () use ($representingCountry) {
            // Detach all application processes
            $representingCountry->applicationProcesses()->detach();

            // Delete the representing country
            return (bool) $representingCountry->delete();
        });
    }
}
