<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselees', function (Blueprint $table) {

            $table->string('title')->nullable()->after('id');

            $table->text('address')->nullable()->after('last_name');

            $table->string('telephone1',30)->nullable()->after('address');

            $table->string('telephone2',30)->nullable()->after('telephone1');

            $table->integer('age')->nullable()->after('email');

            $table->string('marital_status',30)->nullable()->after('gender');

            $table->string('referral')->nullable()->after('marital_status');

            $table->enum('previous_counselling',['Yes','No'])
                    ->nullable()
                    ->after('referral');

            $table->text('previous_counselling_details')
                    ->nullable()
                    ->after('previous_counselling');

        });
    }

    public function down(): void
    {
        Schema::table('counselees', function (Blueprint $table) {

            $table->dropColumn([
                'title',
                'address',
                'telephone1',
                'telephone2',
                'age',
                'marital_status',
                'referral',
                'previous_counselling',
                'previous_counselling_details'
            ]);

        });
    }
};