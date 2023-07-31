<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supervisor>
 */
class SupervisorFactory extends Factory
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
            'full_name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'address' =>  fake()->address(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'status'    => 'Active',
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ];
    }
}
