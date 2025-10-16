<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepresentingCountry;
use Illuminate\Support\Facades\DB;

final class DeleteRepresentingCountry
{
    public function handle(RepresentingCountry $representingCountry): void
    {
        DB::transaction(function () use ($representingCountry) {
            // Delete associated status records (cascade will handle sub_statuses)
            $representingCountry->repCountryStatuses()->delete();

            // Delete the representing country
            $representingCountry->delete();
        });
    }
}
