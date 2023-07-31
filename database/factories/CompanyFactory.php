<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'thumbnail' => fake()->imageUrl(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'note' => fake()->paragraph(),
            'contact_person' => fake()->name(),
            'contact_person_no' => fake()->unique()->phoneNumber(),
            'company_legal_name' => fake()->company(),
            'uetds_url' => null,
            'uetds_username' => null,
            'uetds_password' => null,
            'uetds_status'  =>  null,
            'customer_create_limit' => 0,
            'demo_end_at'   => null,
            'subscription_start_at' => null,
            'subscription_end_at'   => null,
            'type'  =>  'Yearly',
            'status' => 'Active',
            'payment_on' => null,
            'payment_status' =>null,
            'payment_note' => null,
            'source_of_booking' => null,
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ];
    }
}
