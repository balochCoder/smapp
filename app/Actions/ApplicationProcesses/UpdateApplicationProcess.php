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
                'color' => $data['color'] ?? $applicationProcess->color,
                'order' => $data['order'] ?? $applicationProcess->order,
            ]);

            return $applicationProcess->fresh();
        });
    }
}
