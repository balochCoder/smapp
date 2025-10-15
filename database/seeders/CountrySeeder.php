<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'United Kingdom',
                'code' => 'GBR',
                'region' => 'Europe',
                'application_process_info' => 'UK universities accept applications through UCAS for undergraduate programs.',
                'visa_types' => ['Student Visa (Tier 4)', 'Short-term Study Visa'],
                'required_documents' => ['Passport', 'CAS Letter', 'Financial Proof', 'IELTS/TOEFL', 'TB Test'],
                'application_stages' => ['Application Submission', 'Offer Received', 'CAS Obtained', 'Visa Application', 'Visa Decision'],
            ],
            [
                'name' => 'Canada',
                'code' => 'CAN',
                'region' => 'North America',
                'application_process_info' => 'Canadian universities have their own application portals. Apply directly to institutions.',
                'visa_types' => ['Study Permit', 'Co-op Work Permit'],
                'required_documents' => ['Passport', 'Offer Letter', 'GIC', 'Financial Proof', 'IELTS/TOEFL', 'Medical Exam'],
                'application_stages' => ['Application Submission', 'Offer Received', 'GIC Payment', 'Visa Application', 'Biometrics', 'Visa Decision'],
            ],
            [
                'name' => 'Australia',
                'code' => 'AUS',
                'region' => 'Oceania',
                'application_process_info' => 'Apply directly to Australian universities. Some use agents for international students.',
                'visa_types' => ['Student Visa (Subclass 500)', 'Student Guardian Visa'],
                'required_documents' => ['Passport', 'COE', 'GTE Statement', 'Financial Proof', 'IELTS/TOEFL/PTE', 'Health Insurance'],
                'application_stages' => ['Application Submission', 'Offer Received', 'COE Obtained', 'GTE Prepared', 'Visa Application', 'Visa Grant'],
            ],
            [
                'name' => 'United States',
                'code' => 'USA',
                'region' => 'North America',
                'application_process_info' => 'US universities accept applications through Common App or individual portals.',
                'visa_types' => ['F-1 Student Visa', 'J-1 Exchange Visitor Visa'],
                'required_documents' => ['Passport', 'I-20 Form', 'Financial Proof', 'TOEFL/IELTS', 'SEVIS Fee Receipt'],
                'application_stages' => ['Application Submission', 'Offer Received', 'I-20 Obtained', 'Visa Application', 'Visa Interview', 'Visa Decision'],
            ],
            [
                'name' => 'Germany',
                'code' => 'DEU',
                'region' => 'Europe',
                'application_process_info' => 'Apply through Uni-Assist or directly to German universities.',
                'visa_types' => ['Student Visa', 'Student Applicant Visa'],
                'required_documents' => ['Passport', 'Admission Letter', 'Blocked Account', 'Health Insurance', 'Language Certificate'],
                'application_stages' => ['Application Submission', 'Admission', 'Blocked Account', 'Visa Application', 'Visa Decision'],
            ],
            [
                'name' => 'Ireland',
                'code' => 'IRL',
                'region' => 'Europe',
                'application_process_info' => 'Apply through CAO for undergraduate or directly for postgraduate programs.',
                'visa_types' => ['Study Visa (D Visa)', 'Stamp 2 Permission'],
                'required_documents' => ['Passport', 'Offer Letter', 'Financial Proof', 'IELTS/TOEFL', 'Medical Insurance'],
                'application_stages' => ['Application Submission', 'Offer Received', 'Visa Application', 'Visa Decision'],
            ],
            [
                'name' => 'New Zealand',
                'code' => 'NZL',
                'region' => 'Oceania',
                'application_process_info' => 'Apply directly to New Zealand institutions.',
                'visa_types' => ['Student Visa', 'Fee Paying Student Visa'],
                'required_documents' => ['Passport', 'Offer Letter', 'Financial Proof', 'IELTS/TOEFL/PTE', 'Medical Certificate'],
                'application_stages' => ['Application Submission', 'Offer Received', 'Visa Application', 'Visa Decision'],
            ],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::updateOrCreate(
                ['code' => $country['code']], // Match by unique code
                $country // Update or create with all data
            );
        }
    }
}
