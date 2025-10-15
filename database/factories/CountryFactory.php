<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
final class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = [
            ['name' => 'United Kingdom', 'code' => 'GBR', 'region' => 'Europe'],
            ['name' => 'Canada', 'code' => 'CAN', 'region' => 'North America'],
            ['name' => 'Australia', 'code' => 'AUS', 'region' => 'Oceania'],
            ['name' => 'United States', 'code' => 'USA', 'region' => 'North America'],
            ['name' => 'Germany', 'code' => 'DEU', 'region' => 'Europe'],
            ['name' => 'Ireland', 'code' => 'IRL', 'region' => 'Europe'],
            ['name' => 'New Zealand', 'code' => 'NZL', 'region' => 'Oceania'],
        ];

        $country = fake()->randomElement($countries);

        return [
            'name' => $country['name'],
            'code' => $country['code'],
            'region' => $country['region'],
            'flag' => null,
            'is_active' => true,
        ];
    }
}
