<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('counselee_id')
                  ->constrained('counselees')->onDelete('cascade');

            $table->foreignId('counselor_id')
                  ->constrained('counselors')->onDelete('cascade');

            $table->foreignId('counsel_type_id')
                  ->constrained('counsel_types')->onDelete('cascade');

            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('mode', ['Online', 'In person', 'Both'])->nullable();

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');

            $table->text('notes')->nullable();          // counselee notes at booking
            $table->text('counselor_notes')->nullable(); // post-session counselor notes

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_by')->nullable();  // 'counselee' | 'counselor' | 'admin'

            $table->timestamps();

            // Prevent double-booking same counselor at same time
            $table->unique(['counselor_id', 'appointment_date', 'start_time'], 'no_double_book');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
