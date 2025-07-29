<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('cr_bookings', 'vendor_id')) {
            Schema::table('cr_bookings', function (Blueprint $table) {
                $table->foreignId('vendor_id')->nullable()->after('customer_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cr_bookings', 'vendor_id')) {
            Schema::table('cr_bookings', function (Blueprint $table) {
                $table->dropColumn('vendor_id');
            });
        }
    }
};
