<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RepCountryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubStatus>
 */
final class SubStatusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rep_country_status_id' => RepCountryStatus::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'order' => fake()->numberBetween(1, 10),
            'is_active' => fake()->boolean(80),
        ];
    }
}
