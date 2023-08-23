<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flight_numbers', function (Blueprint $table) {
            $table->string('arrival_country_code')->nullable()->after('arrival_airport');
            $table->string('arrival_country')->nullable()->after('arrival_country_code');
            $table->integer('arrival_city_code')->nullable()->after('arrival_country');
            $table->string('arrival_city')->nullable()->after('arrival_city_code');
            $table->integer('arrival_zone_code')->nullable()->after('arrival_city');
            $table->string('arrival_zone')->nullable()->after('arrival_zone_code');
            $table->string('arrival_location')->nullable()->after('arrival_zone');
            $table->string('departure_country_code')->nullable()->after('departure_airport');
            $table->string('departure_country')->nullable()->after('departure_country_code');
            $table->integer('departure_city_code')->nullable()->after('departure_country');
            $table->string('departure_city')->nullable()->after('departure_city_code');
            $table->integer('departure_zone_code')->nullable()->after('departure_city');
            $table->string('departure_zone')->nullable()->after('departure_zone_code');
            $table->string('departure_location')->nullable()->after('departure_zone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_numbers', function (Blueprint $table) {
            $table->dropColumn([
                'arrival_country_code',
                'arrival_country',
                'arrival_city_code',
                'arrival_city',
                'arrival_zone_code',
                'arrival_zone',
                'arrival_location',
                'departure_country_code',
                'departure_country',
                'departure_city_code',
                'departure_city',
                'departure_zone_code',
                'departure_zone',
                'departure_location',
            ]);
        });
    }
};
