<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\ApplicationProcess;
use App\Models\RepCountryStatus;
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
                'currency' => $data['currency'] ?? 'USD',
                'visa_requirements' => $data['visa_requirements'] ?? null,
                'part_time_work_details' => $data['part_time_work_details'] ?? null,
                'country_benefits' => $data['country_benefits'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Create status records for each selected application process
            if (isset($data['application_process_ids']) && is_array($data['application_process_ids'])) {
                $processes = ApplicationProcess::whereIn('id', $data['application_process_ids'])
                    ->orderBy('order')
                    ->get();

                foreach ($processes as $index => $process) {
                    RepCountryStatus::create([
                        'representing_country_id' => $representingCountry->id,
                        'status_name' => $process->name,
                        'order' => $index + 1,
                        'is_active' => true,
                    ]);
                }
            }

            return $representingCountry->load(['country', 'repCountryStatuses.subStatuses']);
        });
    }
}
