<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Passenger>
 */
class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_number' => fake('tr_TR')->tcNo(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'full_name' => fake()->name(),
            'email' => 'passenger@yopmail.com',
            'contact_number' => fake()->unique()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'nationality' =>  fake()->country(),
            'country_code' => fake()->countryCode(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'age'   => fake()->numberBetween(18,50),
            'status'    => 'Active',
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ];
    }
}
