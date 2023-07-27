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
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('transfer_id')->constrained();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('flight_number')->nullable();
            $table->string('type')->nullable();
            $table->longText('info')->nullable();
            $table->string('file_path')->nullable();
            $table->string('message')->nullable();
            $table->float('star', 10, 1)->nullable();
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
        Schema::dropIfExists('transfer_details');
    }
};
