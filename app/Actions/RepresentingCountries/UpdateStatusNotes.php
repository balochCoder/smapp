<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;
use Illuminate\Support\Facades\DB;

final class UpdateStatusNotes
{
    public function handle(array $statusNotes): void
    {
        DB::transaction(function () use ($statusNotes) {
            foreach ($statusNotes as $statusNote) {
                RepCountryStatus::where('id', $statusNote['id'])->update([
                    'notes' => $statusNote['notes'],
                ]);
            }
        });
    }
}

