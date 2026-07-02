<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_reschedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appointment_id')
                  ->constrained('appointments')->onDelete('cascade');

            $table->date('old_appointment_date');
            $table->time('old_start_time');
            $table->time('old_end_time');
            $table->foreignId('old_counselor_id')->nullable()->constrained('counselors')->nullOnDelete();

            $table->date('new_appointment_date');
            $table->time('new_start_time');
            $table->time('new_end_time');
            $table->foreignId('new_counselor_id')->nullable()->constrained('counselors')->nullOnDelete();

            $table->string('rescheduled_by'); // 'counselee' | 'counselor' | 'admin'
            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_reschedules');
    }
};
