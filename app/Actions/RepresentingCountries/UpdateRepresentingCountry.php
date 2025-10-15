<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepresentingCountry;
use Illuminate\Support\Facades\DB;

final class UpdateRepresentingCountry
{
    public function handle(RepresentingCountry $representingCountry, array $data): RepresentingCountry
    {
        return DB::transaction(function () use ($representingCountry, $data) {
            $representingCountry->update([
                'monthly_living_cost' => $data['monthly_living_cost'] ?? $representingCountry->monthly_living_cost,
                'visa_requirements' => $data['visa_requirements'] ?? $representingCountry->visa_requirements,
                'part_time_work_details' => $data['part_time_work_details'] ?? $representingCountry->part_time_work_details,
                'country_benefits' => $data['country_benefits'] ?? $representingCountry->country_benefits,
                'is_active' => $data['is_active'] ?? $representingCountry->is_active,
            ]);

            // Sync application processes if provided
            if (isset($data['application_process_ids']) && is_array($data['application_process_ids'])) {
                $representingCountry->applicationProcesses()->sync($data['application_process_ids']);
            }

            return $representingCountry->fresh(['country', 'applicationProcesses']);
        });
    }
}
