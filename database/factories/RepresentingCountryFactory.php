<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepresentingCountry>
 */
final class RepresentingCountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'monthly_living_cost' => fake()->randomFloat(2, 800, 2500),
            'visa_requirements' => fake()->optional()->paragraph(),
            'part_time_work_details' => fake()->optional()->paragraph(),
            'country_benefits' => fake()->optional()->paragraph(),
            'is_active' => true,
        ];
    }
}
