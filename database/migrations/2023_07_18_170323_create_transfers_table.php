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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_country_code');
            $table->string('pickup_country');
            $table->integer('pickup_city_code');
            $table->string('pickup_city');
            $table->integer('pickup_zone_code');
            $table->string('pickup_zone');
            $table->string('dropoff_country_code');
            $table->string('dropoff_country');
            $table->integer('dropoff_city_code');
            $table->string('dropoff_city');
            $table->integer('dropoff_zone_code');
            $table->string('dropoff_zone');
            $table->dateTime('pickup_start_time')->nullable();
            $table->dateTime('dropoff_time')->nullable();
            $table->dateTime('vehicle_assigned_time')->nullable();
            $table->string('vehicle_assigned_by')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->foreignId('flight_number_id')->nullable()->constrained();
            $table->string('flight_number')->nullable();
            $table->string('type')->nullable();
            $table->longText('info')->nullable();
            $table->string('file_path')->nullable();
            $table->string('message')->nullable();
            $table->float('star', 10, 1)->nullable();
            $table->string('status');
            $table->string('uetds_id')->nullable();
            $table->string('uetds_group_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_user_name');
            $table->string('updated_user_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
