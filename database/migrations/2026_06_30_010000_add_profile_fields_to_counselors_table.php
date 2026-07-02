<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->text('address')->nullable()->after('phone');
            $table->unsignedSmallInteger('experience_years')->nullable()->after('specialization');
            $table->enum('mode', ['Online', 'In person', 'Both'])->nullable()->after('experience_years');
            $table->string('languages')->nullable()->after('mode');
            $table->enum('training_level', ['Level 1', 'Level 2', 'Advanced', 'Certified', 'Other'])
                  ->nullable()->after('languages');
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->dropColumn(['address', 'experience_years', 'mode', 'languages', 'training_level']);
        });
    }
};
