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
        \App\Models\Owner::factory()->createQuietly([
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
        \App\Models\Owner::factory(10)->createQuietly();
        $this->call(UetdsCitiesSeeder::class);
    }
}
