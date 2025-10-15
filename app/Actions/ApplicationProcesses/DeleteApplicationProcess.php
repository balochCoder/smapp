<?php

declare(strict_types=1);

namespace App\Actions\ApplicationProcesses;

use App\Models\ApplicationProcess;
use Illuminate\Support\Facades\DB;

final class DeleteApplicationProcess
{
    public function handle(ApplicationProcess $applicationProcess): bool
    {
        return DB::transaction(function () use ($applicationProcess) {
            // Detach all representing countries
            $applicationProcess->representingCountries()->detach();

            // Delete the application process (cascade will handle sub-processes)
            return (bool) $applicationProcess->delete();
        });
    }
}
