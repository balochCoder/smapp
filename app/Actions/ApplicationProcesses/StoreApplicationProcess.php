<?php

declare(strict_types=1);

namespace App\Actions\ApplicationProcesses;

use App\Models\ApplicationProcess;
use Illuminate\Support\Facades\DB;

final class StoreApplicationProcess
{
    public function handle(array $data): ApplicationProcess
    {
        return DB::transaction(function () use ($data) {
            // Auto-calculate order if not provided
            $order = $data['order'] ?? null;
            if ($order === null) {
                $parentId = $data['parent_id'] ?? null;
                $maxOrder = ApplicationProcess::where('parent_id', $parentId)->max('order') ?? 0;
                $order = $maxOrder + 1;
            }

            $applicationProcess = ApplicationProcess::create([
                'parent_id' => $data['parent_id'] ?? null,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'order' => $order,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Attach to representing countries if provided
            if (isset($data['representing_country_ids']) && is_array($data['representing_country_ids'])) {
                $applicationProcess->representingCountries()->sync($data['representing_country_ids']);
            }

            return $applicationProcess->load(['parent', 'subProcesses', 'representingCountries']);
        });
    }
}
