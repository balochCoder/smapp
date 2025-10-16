<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RepresentingCountry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepCountryStatus>
 */
final class RepCountryStatusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'representing_country_id' => RepresentingCountry::factory(),
            'status_name' => fake()->words(2, true),
            'notes' => fake()->optional()->paragraph(),
            'custom_name' => fake()->optional()->words(3, true),
            'order' => fake()->numberBetween(1, 20),
            'is_active' => fake()->boolean(80),
        ];
    }
}
