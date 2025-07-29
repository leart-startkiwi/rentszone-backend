<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('cr_cars', 'external_booking_url')) {
            return;
        }

        Schema::table('cr_cars', function (Blueprint $table) {
            $table->string('external_booking_url')->nullable()->after('sale_status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('cr_cars', 'external_booking_url')) {
            return;
        }

        Schema::table('cr_cars', function (Blueprint $table) {
            $table->dropColumn('external_booking_url');
        });
    }
};
