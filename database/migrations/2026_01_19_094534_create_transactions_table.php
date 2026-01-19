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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('provider_id')->constrained();

            // The "who": phone number for recharge or meter/account number for bills
            $table->string('phone_number');
            $table->string('account_number')->nullable();

            // Amount & Payment Info
            $table->decimal('amount', 10, 2);
            $table->enum('connection_type', ['prepaid', 'postpaid'])->default('prepaid');

            // Status & Identification
            $table->string('status')->default('pending'); // pending, success, failed
            $table->string('trx_id')->nullable(); // External API reference ID
            $table->string('guest_email')->nullable(); // Optional: to send receipt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
