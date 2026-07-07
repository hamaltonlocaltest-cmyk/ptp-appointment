<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_feedbacks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appointment_id')
                  ->unique()
                  ->constrained('appointments')->onDelete('cascade');

            $table->foreignId('counselee_id')
                  ->constrained('counselees')->onDelete('cascade');

            // Denormalized so counselor-side queries don't need to join through appointments
            $table->foreignId('counselor_id')
                  ->constrained('counselors')->onDelete('cascade');

            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_feedbacks');
    }
};
