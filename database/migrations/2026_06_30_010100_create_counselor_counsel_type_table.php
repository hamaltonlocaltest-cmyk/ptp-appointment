<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselor_counsel_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained('counselors')->onDelete('cascade');
            $table->foreignId('counsel_type_id')->constrained('counsel_types')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['counselor_id', 'counsel_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_counsel_type');
    }
};
