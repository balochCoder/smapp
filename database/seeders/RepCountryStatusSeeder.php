<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationProcess;
use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;
use Illuminate\Database\Seeder;

final class RepCountryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all representing countries
        $representingCountries = RepresentingCountry::all();

        // Get all application processes ordered by their order
        $applicationProcesses = ApplicationProcess::orderBy('order')->get();

        foreach ($representingCountries as $representingCountry) {
            foreach ($applicationProcesses as $index => $applicationProcess) {
                RepCountryStatus::updateOrCreate(
                    [
                        'representing_country_id' => $representingCountry->id,
                        'status_name' => $applicationProcess->name,
                    ],
                    [
                        'notes' => null,
                        'custom_name' => null,
                        'order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
