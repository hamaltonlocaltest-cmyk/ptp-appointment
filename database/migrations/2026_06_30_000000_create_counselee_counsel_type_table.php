<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselee_counsel_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselee_id')->constrained('counselees')->onDelete('cascade');
            $table->foreignId('counsel_type_id')->constrained('counsel_types')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['counselee_id', 'counsel_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselee_counsel_type');
    }
};