<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationProcess;
use Illuminate\Database\Seeder;

final class ApplicationProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define default application statuses (flat structure - no parent/child)
        $statuses = [
            ['name' => 'New', 'color' => 'blue', 'order' => 1],
            ['name' => 'Application On Hold', 'color' => 'yellow', 'order' => 2],
            ['name' => 'Pre-Application Process', 'color' => 'purple', 'order' => 3],
            ['name' => 'Rejected by University', 'color' => 'red', 'order' => 4],
            ['name' => 'Application Submitted', 'color' => 'green', 'order' => 5],
            ['name' => 'Conditional Offer', 'color' => 'orange', 'order' => 6],
            ['name' => 'Pending Interview', 'color' => 'yellow', 'order' => 7],
            ['name' => 'Unconditional Offer', 'color' => 'green', 'order' => 8],
            ['name' => 'Acceptance', 'color' => 'green', 'order' => 9],
            ['name' => 'Visa Processing', 'color' => 'blue', 'order' => 10],
            ['name' => 'Enrolled', 'color' => 'green', 'order' => 11],
            ['name' => 'Dropped', 'color' => 'red', 'order' => 12],
        ];

        foreach ($statuses as $status) {
            ApplicationProcess::updateOrCreate(
                ['name' => $status['name']],
                [
                    'color' => $status['color'],
                    'order' => $status['order'],
                ]
            );
        }
    }
}
