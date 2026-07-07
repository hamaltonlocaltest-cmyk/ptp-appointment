<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->string('reference_number')->unique();

            // Who filed the complaint — a counselee or a counselor
            $table->enum('filed_by', ['counselee', 'counselor'])->default('counselee');

            $table->foreignId('counselee_id')->nullable()
                  ->constrained('counselees')->nullOnDelete();

            $table->foreignId('counselor_id')->nullable()
                  ->constrained('counselors')->nullOnDelete();

            $table->foreignId('appointment_id')->nullable()
                  ->constrained('appointments')->nullOnDelete();

            $table->string('subject');
            $table->text('description');

            $table->enum('status', ['open', 'in_review', 'resolved', 'closed'])->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
