<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationProcess;
use App\Models\RepresentingCountry;
use Illuminate\Database\Seeder;

final class ApplicationProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main processes (parent processes that can be shared across countries)
        $mainProcesses = [
            'Application Submission' => 'Submit application to institution',
            'Offer Received' => 'Receive offer letter from institution',
            'Visa Application' => 'Apply for student visa',
            'Visa Decision' => 'Receive visa decision',
            'Pre-Departure' => 'Pre-departure preparations',
        ];

        $createdProcesses = [];
        $order = 1;
        foreach ($mainProcesses as $name => $description) {
            $process = ApplicationProcess::updateOrCreate(
                ['name' => $name, 'parent_id' => null],
                [
                    'description' => $description,
                    'order' => $order++,
                    'is_active' => true,
                ]
            );
            $createdProcesses[$name] = $process;
        }

        // Create country-specific sub-processes
        $subProcesses = [
            'Offer Received' => [
                'CAS Obtained' => 'Obtain Confirmation of Acceptance for Studies (UK)',
                'COE Obtained' => 'Obtain Confirmation of Enrolment (Australia)',
                'I-20 Obtained' => 'Obtain I-20 form (USA)',
                'GIC Payment' => 'Guaranteed Investment Certificate payment (Canada)',
            ],
            'Visa Application' => [
                'Biometrics' => 'Complete biometrics appointment',
                'Visa Interview' => 'Attend visa interview',
                'GTE Prepared' => 'Prepare Genuine Temporary Entrant statement (Australia)',
                'Blocked Account' => 'Open blocked account (Germany)',
            ],
        ];

        foreach ($subProcesses as $parentName => $subs) {
            $parent = $createdProcesses[$parentName] ?? null;
            if ($parent) {
                $subOrder = 1;
                foreach ($subs as $subName => $subDescription) {
                    ApplicationProcess::updateOrCreate(
                        ['name' => $subName, 'parent_id' => $parent->id],
                        [
                            'description' => $subDescription,
                            'order' => $subOrder++,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // Attach processes to representing countries
        $representingCountries = RepresentingCountry::all();

        if ($representingCountries->isNotEmpty()) {
            // Get all main process IDs
            $processIds = collect($createdProcesses)->pluck('id')->toArray();

            foreach ($representingCountries as $repCountry) {
                // Attach all main processes to all representing countries
                $repCountry->applicationProcesses()->syncWithoutDetaching($processIds);
            }
        }
    }
}
