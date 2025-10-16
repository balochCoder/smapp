<?php

declare(strict_types=1);

namespace App\Actions\ApplicationProcesses;

use App\Models\ApplicationProcess;
use Illuminate\Support\Facades\DB;

final class DeleteApplicationProcess
{
    public function handle(ApplicationProcess $applicationProcess): void
    {
        DB::transaction(function () use ($applicationProcess) {
            // Delete the application process
            $applicationProcess->delete();
        });
    }
}
