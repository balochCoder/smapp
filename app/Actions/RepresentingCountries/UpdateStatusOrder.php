<?php

declare(strict_types=1);

namespace App\Actions\RepresentingCountries;

use App\Models\RepCountryStatus;
use Illuminate\Support\Facades\DB;

final class UpdateStatusOrder
{
    public function handle(array $statusOrders): void
    {
        DB::transaction(function () use ($statusOrders) {
            foreach ($statusOrders as $statusOrder) {
                $status = RepCountryStatus::find($statusOrder['id']);

                // Prevent reordering the "New" status
                if ($status && $status->status_name === 'New') {
                    continue;
                }

                RepCountryStatus::where('id', $statusOrder['id'])->update([
                    'order' => $statusOrder['order'],
                ]);
            }
        });
    }
}

