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
        Schema::create('contact_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->string('name');
            $table->string('number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status',['Active','Deactive'])->default('Active');
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
        Schema::dropIfExists('contact_people');
    }
};
