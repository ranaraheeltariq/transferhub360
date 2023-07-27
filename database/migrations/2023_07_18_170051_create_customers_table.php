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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('legal_name')->nullable();
            $table->string('brand_name');
            $table->string('email')->unique();
            $table->string('contact_number')->unique();
            $table->string('contact_person');
            $table->string('whatsapp_number')->nullable();
            $table->string('website')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('country');
            $table->string('city');
            $table->string('zone');
            $table->integer('transfer_create_limit')->default(0);
            $table->string('address');
            $table->longText('description')->nullable();
            $table->date('demo_end_at')->nullable();
            $table->date('subscription_start_at')->nullable();
            $table->date('subscription_end_at')->nullable();
            $table->enum('type',['Demo','Monthly','Yearly'])->default('Demo')->nullable();
            $table->enum('status',['Active','Deactive'])->default('Active');
            $table->date('payment_on')->nullable();
            $table->enum('payment_status',['Pending','Processing','Completed'])->nullable();
            $table->longText('payment_note')->nullable();
            $table->string('source_of_booking')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
