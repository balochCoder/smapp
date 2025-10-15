<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a main branch
        \App\Models\Branch::factory()->main()->create([
            'name' => 'Headquarters',
            'code' => 'BR-HQ',
        ]);

        // Create additional branches
        \App\Models\Branch::factory()->count(3)->create();
    }
}
