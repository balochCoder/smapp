<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use App\Models\RepresentingCountry;
use Illuminate\Database\Seeder;

final class RepresentingCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $representingData = [
            'United Kingdom' => [
                'monthly_living_cost' => 1200.00,
                'visa_requirements' => 'Student Visa (Tier 4) required for international students. Must have CAS from university.',
                'part_time_work_details' => 'Students can work up to 20 hours per week during term time and full-time during holidays.',
                'country_benefits' => 'World-class universities, rich cultural heritage, English-speaking environment, post-study work opportunities.',
            ],
            'Canada' => [
                'monthly_living_cost' => 1000.00,
                'visa_requirements' => 'Study Permit required. Must show proof of funds and acceptance letter from DLI.',
                'part_time_work_details' => 'Students can work up to 20 hours per week during semesters and full-time during breaks.',
                'country_benefits' => 'High quality of life, multicultural society, affordable education, pathway to permanent residence.',
            ],
            'Australia' => [
                'monthly_living_cost' => 1400.00,
                'visa_requirements' => 'Student Visa (Subclass 500) required. Must have COE and meet GTE requirement.',
                'part_time_work_details' => 'Students can work up to 48 hours per fortnight during semester and unlimited hours during breaks.',
                'country_benefits' => 'Excellent universities, beautiful climate, diverse culture, post-study work visa options.',
            ],
            'United States' => [
                'monthly_living_cost' => 1500.00,
                'visa_requirements' => 'F-1 Student Visa required. Must have I-20 from SEVP-approved institution.',
                'part_time_work_details' => 'On-campus work allowed up to 20 hours/week. CPT and OPT available for practical training.',
                'country_benefits' => 'Top-ranked universities, cutting-edge research, diverse opportunities, global recognition.',
            ],
            'Germany' => [
                'monthly_living_cost' => 900.00,
                'visa_requirements' => 'Student Visa required. Must show blocked account with minimum funds and health insurance.',
                'part_time_work_details' => 'Students can work 120 full days or 240 half days per year.',
                'country_benefits' => 'Low or no tuition fees, strong economy, central European location, excellent engineering programs.',
            ],
            'Ireland' => [
                'monthly_living_cost' => 1100.00,
                'visa_requirements' => 'Study Visa (D Visa) and Stamp 2 registration required for non-EU students.',
                'part_time_work_details' => 'Students can work 20 hours per week during term and 40 hours during holidays.',
                'country_benefits' => 'English-speaking, tech hub of Europe, friendly culture, graduate visa available.',
            ],
            'New Zealand' => [
                'monthly_living_cost' => 1200.00,
                'visa_requirements' => 'Student Visa required. Must have offer of place and proof of funds.',
                'part_time_work_details' => 'Students can work up to 20 hours per week during study and full-time during breaks.',
                'country_benefits' => 'Safe environment, stunning natural beauty, quality education, work opportunities after study.',
            ],
        ];

        foreach ($representingData as $countryName => $data) {
            $country = Country::where('name', $countryName)->first();

            if ($country) {
                RepresentingCountry::updateOrCreate(
                    ['country_id' => $country->id],
                    array_merge($data, ['is_active' => true])
                );
            }
        }
    }
}
