<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $owner = \App\Models\Owner::factory()->createQuietly([
            'full_name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => 'admin@transferhub360.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ]);
        $owner->assignRole('owner');
        // \App\Models\Owner::factory(10)->createQuietly();
        // \App\Models\Company::factory(1)->hasDrivers(1)->hasUsers(1)->hasSupervisors(1)->createQuietly();
        \App\Models\Company::factory(1)->hasCustomers(1)->hasHotels(10)->createQuietly();
        $user = \App\Models\User::factory()->createQuietly([
            'company_id' => 1,
            'full_name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => 'admin@yopmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ]);
        $user->assignRole('super admin');
        $driver = \App\Models\Driver::factory()->createQuietly([
            'company_id' => 1,
            'identify_number' => fake('tr_TR')->tcNo(),
            'full_name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => 'driver@yopmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'address' =>  fake()->address(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'status'    => 'Active',
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ]);
        $driver->assignRole('driver');
        $supervisor = \App\Models\Supervisor::factory()->createQuietly([
            'company_id' => 1,
            'id_number' => fake('tr_TR')->tcNo(),
            'full_name' => fake()->name(),
            'contact_number' => fake()->unique()->phoneNumber(),
            'email' => 'supervisor@yopmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'thumbnail' => fake()->imageUrl(),
            'address' =>  fake()->address(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'status'    => 'Active',
            'remember_token' => Str::random(10),
            'created_user_name' =>  'System',
            'updated_user_name' =>  'System',
        ]);
        $supervisor->assignRole('supervisor');
        \App\Models\Vehicle::factory()->createQuietly([
            'company_id' => 1,
            'driver_id' => 1,
            'number_plate' => '34 BAA 5566',
            'name' => 'BMW',
            'modal' => 'B800',
            'thumbnail' => fake()->imageUrl(),
            'created_user_name' => 'System',
            'updated_user_name' => 'System',
        ]);
        $passenger = \App\Models\Passenger::factory()->createQuietly([
            'company_id' => 1,
            'customer_id' => 1,
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
        ]);
        $passenger->assignRole('passenger');
        $this->call(UetdsCitiesSeeder::class);
    }
}
