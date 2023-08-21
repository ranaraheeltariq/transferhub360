<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Hotel',
            'country_code' => 'TR',
            'country'  => 'Turkiye',
            'city_code' => 34,
            'city' => 'İSTANBUL',
            'zone_code' => 1852,
            'zone' => 'ÜMRANİYE',
            'location' => 'Ilhamurkoye',
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'thumbnail' => fake()->imageUrl(),
            'status'    => 'Active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_user_name' => 'System',
            'updated_user_name' => 'System',
        ];
    }
}
