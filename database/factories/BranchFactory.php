<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
final class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            ['city' => 'London', 'state' => 'England', 'country' => 'United Kingdom'],
            ['city' => 'Manchester', 'state' => 'England', 'country' => 'United Kingdom'],
            ['city' => 'Toronto', 'state' => 'Ontario', 'country' => 'Canada'],
            ['city' => 'Vancouver', 'state' => 'British Columbia', 'country' => 'Canada'],
            ['city' => 'Sydney', 'state' => 'New South Wales', 'country' => 'Australia'],
            ['city' => 'Melbourne', 'state' => 'Victoria', 'country' => 'Australia'],
            ['city' => 'Dubai', 'state' => 'Dubai', 'country' => 'UAE'],
            ['city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India'],
        ];

        $location = fake()->randomElement($cities);

        return [
            'name' => fake()->company().' Branch',
            'code' => mb_strtoupper(fake()->unique()->lexify('BR-???')),
            'address' => fake()->streetAddress(),
            'city' => $location['city'],
            'state' => $location['state'],
            'country' => $location['country'],
            'postal_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'currency' => fake()->randomElement(['USD', 'GBP', 'CAD', 'AUD', 'EUR']),
            'timezone' => fake()->timezone(),
            'working_hours' => [
                'monday' => ['open' => '09:00', 'close' => '18:00'],
                'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                'thursday' => ['open' => '09:00', 'close' => '18:00'],
                'friday' => ['open' => '09:00', 'close' => '18:00'],
                'saturday' => ['open' => '10:00', 'close' => '14:00'],
                'sunday' => ['open' => null, 'close' => null],
            ],
            'representing_countries' => null, // Will be set after countries are seeded
            'territories' => null,
            'is_active' => true,
            'is_main' => false,
        ];
    }

    public function main(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_main' => true,
        ]);
    }
}
