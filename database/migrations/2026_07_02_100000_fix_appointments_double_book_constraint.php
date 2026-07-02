<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// The original unique index on (counselor_id, appointment_date, start_time)
// blocks re-booking a slot even after the old appointment there was cancelled,
// because MySQL/MariaDB unique indexes don't know about the 'cancelled' status.
// Replace it with a unique index on a generated column that is NULL for
// cancelled appointments — NULLs don't collide in a unique index, so
// cancelled slots become bookable again while active double-bookings are
// still prevented.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // The old unique index doubles as the supporting index for the
            // counselor_id foreign key — add a plain index first so MySQL/
            // MariaDB will let us drop the unique one.
            $table->index('counselor_id', 'appointments_counselor_id_index');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('no_double_book');
        });

        DB::statement("
            ALTER TABLE appointments
            ADD COLUMN active_booking_key VARCHAR(150)
            GENERATED ALWAYS AS (
                CASE WHEN status <> 'cancelled'
                     THEN CONCAT(counselor_id, '|', appointment_date, '|', start_time)
                     ELSE NULL
                END
            ) STORED
        ");

        Schema::table('appointments', function (Blueprint $table) {
            $table->unique('active_booking_key', 'no_double_book_active');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('no_double_book_active');
            $table->dropColumn('active_booking_key');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unique(['counselor_id', 'appointment_date', 'start_time'], 'no_double_book');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_counselor_id_index');
        });
    }
};
