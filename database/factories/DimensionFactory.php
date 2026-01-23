<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dimension>
 */
class DimensionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dimensions = [
            ['name' => 'kilograms', 'abbreviation' => 'kg'],
            ['name' => 'liters', 'abbreviation' => 'l'],
            ['name' => 'meters', 'abbreviation' => 'm'],
            ['name' => 'pieces', 'abbreviation' => 'pcs'],
            ['name' => 'boxes', 'abbreviation' => 'box'],
        ];
        $dim = $this->faker->randomElement($dimensions);
        return [
            'name' => $dim['name'],
            'abbreviation' => $dim['abbreviation'],
        ];
    }
}
