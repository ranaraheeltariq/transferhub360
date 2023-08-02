<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => fake()->company(),
            'brand_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'contact_person' => fake()->name(),
            'whatsapp_number' => fake()->name(),
            'website' => fake()->url(),
            'thumbnail' => fake()->imageUrl(),
            'country' =>  fake()->country(),
            'city' => fake()->city(),
            'zone' => fake()->state(),
            'address' => fake()->streetAddress(),
            'description' => fake()->paragraph(),
            'transfer_create_limit' => 0,
            'demo_end_at' => date('y-m-d', strtotime('+15 days')),
            'subscription_start_at' => date('y-m-d'),
            'subscription_end_at' => date('y-m-d', strtotime('+1 year')),
            'type' => 'Yearly',
            'status' => 'Active',
            'payment_on' => null,
            'payment_status' =>null,
            'payment_note' => null,
            'source_of_booking' => 'Patient Hub 360',
            'created_user_name' => 'System',
            'updated_user_name' => 'System',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ];
    }
}
