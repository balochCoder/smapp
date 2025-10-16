<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\SubStatus;

final class DeleteSubStatus
{
    public function handle(SubStatus $subStatus): void
    {
        $subStatus->delete();
    }
}

