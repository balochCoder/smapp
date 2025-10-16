<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\ApplicationProcess;
use App\Models\RepCountryStatus;
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

            // Update status records if application processes are provided
            if (isset($data['application_process_ids']) && is_array($data['application_process_ids'])) {
                // Get existing status names
                $existingStatuses = $representingCountry->repCountryStatuses->pluck('status_name', 'id')->toArray();

                // Get new processes
                $newProcesses = ApplicationProcess::whereIn('id', $data['application_process_ids'])
                    ->orderBy('order')
                    ->get()
                    ->pluck('name')
                    ->toArray();

                // Remove statuses that are no longer in the list
                $statusesToRemove = array_diff($existingStatuses, $newProcesses);
                if (! empty($statusesToRemove)) {
                    RepCountryStatus::whereIn('id', array_keys($statusesToRemove))->delete();
                }

                // Add new statuses
                $newStatusNames = array_diff($newProcesses, $existingStatuses);
                foreach ($newStatusNames as $index => $statusName) {
                    $process = ApplicationProcess::where('name', $statusName)->first();
                    if ($process) {
                        RepCountryStatus::create([
                            'representing_country_id' => $representingCountry->id,
                            'status_name' => $process->name,
                            'order' => count($existingStatuses) + $index + 1,
                            'is_active' => true,
                        ]);
                    }
                }
            }

            return $representingCountry->fresh(['country', 'repCountryStatuses.subStatuses']);
        });
    }
}
