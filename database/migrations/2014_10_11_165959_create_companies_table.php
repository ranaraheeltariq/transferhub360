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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('thumbnail')->nullable();
            $table->string('city');
            $table->string('country');
            $table->mediumText('note')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_no')->nullable();
            $table->string('company_legal_name')->nullable();
            $table->string('uetds_url')->nullable();
            $table->string('uetds_username')->nullable();
            $table->string('uetds_password')->nullable();
            $table->string('uetds_status')->nullable();
            $table->integer('customer_create_limit')->default(0);
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
        Schema::dropIfExists('companies');
    }
};
