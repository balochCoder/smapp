<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepresentingCountry;
use Illuminate\Support\Facades\DB;

final class StoreRepresentingCountry
{
    public function handle(string $countryId, array $data): RepresentingCountry
    {
        return DB::transaction(function () use ($countryId, $data) {
            $representingCountry = RepresentingCountry::create([
                'country_id' => $countryId,
                'monthly_living_cost' => $data['monthly_living_cost'] ?? null,
                'visa_requirements' => $data['visa_requirements'] ?? null,
                'part_time_work_details' => $data['part_time_work_details'] ?? null,
                'country_benefits' => $data['country_benefits'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Attach application processes if provided
            if (isset($data['application_process_ids']) && is_array($data['application_process_ids'])) {
                $representingCountry->applicationProcesses()->sync($data['application_process_ids']);
            }

            return $representingCountry->load(['country', 'applicationProcesses']);
        });
    }
}
