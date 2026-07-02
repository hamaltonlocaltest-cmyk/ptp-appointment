<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselee_counselling_areas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('counselee_id')
                  ->constrained('counselees')
                  ->cascadeOnDelete();

            $table->string('area');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselee_counselling_areas');
    }
};