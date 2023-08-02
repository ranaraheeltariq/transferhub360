<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number_plate' => '34 BAA 5566',
            'name' => 'BMW',
            'modal' => 'B800',
            'thumbnail' => fake()->imageUrl(),
            'created_user_name' => 'System',
            'updated_user_name' => 'System',
        ];
    }
}
