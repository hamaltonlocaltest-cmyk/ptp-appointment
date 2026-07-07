<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Nullable — donations are allowed from logged-out/guest donors
            $table->foreignId('counselee_id')->nullable()
                  ->constrained('counselees')->nullOnDelete();

            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');

            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            // Instamojo identifiers
            $table->string('instamojo_payment_request_id')->nullable();
            $table->string('payment_reference')->nullable(); // Instamojo payment_id once paid
            $table->text('gateway_response')->nullable();    // raw JSON snapshot for audit/debugging

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
