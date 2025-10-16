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
                $maxOrder = ApplicationProcess::max('order') ?? 0;
                $order = $maxOrder + 1;
            }

            $applicationProcess = ApplicationProcess::create([
                'name' => $data['name'],
                'color' => $data['color'] ?? 'blue',
                'order' => $order,
            ]);

            return $applicationProcess;
        });
    }
}
