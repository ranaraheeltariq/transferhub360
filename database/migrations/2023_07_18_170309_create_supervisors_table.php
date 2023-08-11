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
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('full_name');
            $table->string('contact_number')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('thumbnail')->nullable();
            $table->string('address');
            $table->string('gender');
            $table->string('id_number')->nullable();
            $table->string('device_token')->nullable();
            $table->enum('status',['Active','Deactive'])->default('Active');
            $table->rememberToken();
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
        Schema::dropIfExists('supervisors');
    }
};
