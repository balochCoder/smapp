<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institution>
 */
final class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $institutionTypes = ['University', 'College', 'Institute', 'Academy'];
        $names = [
            'Royal', 'Imperial', 'Metropolitan', 'Central', 'National',
            'State', 'City', 'International', 'Global', 'Commonwealth',
        ];
        $subjects = [
            'Technology', 'Science', 'Arts', 'Business', 'Medicine',
            'Engineering', 'Law', 'Education', 'Agriculture',
        ];

        return [
            'country_id' => \App\Models\Country::inRandomOrder()->first()?->id ?? \App\Models\Country::factory(),
            'name' => fake()->randomElement($names).' '.fake()->randomElement($subjects).' '.fake()->randomElement($institutionTypes),
            'logo' => null,
            'description' => fake()->paragraph(3),
            'institution_type' => fake()->randomElement($institutionTypes),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'address' => fake()->streetAddress(),
            'website' => fake()->url(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'rankings' => [
                'QS World Ranking' => fake()->numberBetween(50, 1000),
                'Times Higher Education' => fake()->numberBetween(50, 1000),
            ],
            'accreditation' => fake()->sentence(),
            'facilities' => ['Library', 'Laboratory', 'Sports Complex', 'Accommodation', 'Cafeteria', 'Medical Center'],
            'campus_life' => fake()->paragraph(),
            'established_year' => fake()->numberBetween(1800, 2020),
            'commission_rate' => fake()->randomFloat(2, 5, 20),
            'commission_type' => 'percentage',
            'contact_persons' => [
                [
                    'name' => fake()->name(),
                    'position' => 'Admissions Director',
                    'email' => fake()->companyEmail(),
                    'phone' => fake()->phoneNumber(),
                ],
            ],
            'is_partner' => fake()->boolean(70),
            'is_active' => true,
        ];
    }

    public function partner(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_partner' => true,
            'commission_rate' => fake()->randomFloat(2, 10, 25),
        ]);
    }
}
