<?php

declare(strict_types=1);

namespace App\Actions\ApplicationProcesses;

use App\Models\ApplicationProcess;
use Illuminate\Support\Facades\DB;

final class UpdateApplicationProcess
{
    public function handle(ApplicationProcess $applicationProcess, array $data): ApplicationProcess
    {
        return DB::transaction(function () use ($applicationProcess, $data) {
            $applicationProcess->update([
                'name' => $data['name'] ?? $applicationProcess->name,
                'description' => $data['description'] ?? $applicationProcess->description,
                'order' => $data['order'] ?? $applicationProcess->order,
                'is_active' => $data['is_active'] ?? $applicationProcess->is_active,
            ]);

            // Sync representing countries if provided
            if (isset($data['representing_country_ids']) && is_array($data['representing_country_ids'])) {
                $applicationProcess->representingCountries()->sync($data['representing_country_ids']);
            }

            return $applicationProcess->fresh(['parent', 'subProcesses', 'representingCountries']);
        });
    }
}
