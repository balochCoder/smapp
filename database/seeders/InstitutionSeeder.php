<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create partner institutions
        \App\Models\Institution::factory()->partner()->count(10)->create();

        // Create other institutions
        \App\Models\Institution::factory()->count(15)->create();
    }
}
