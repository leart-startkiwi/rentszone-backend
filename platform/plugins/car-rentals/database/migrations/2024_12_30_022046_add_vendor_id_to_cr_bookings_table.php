<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_bookings', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable();
        });

        Schema::table('cr_invoices', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable();
        });

        Schema::table('cr_messages', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cr_bookings', function (Blueprint $table) {
            $table->removeColumn('vendor_id');
        });

        Schema::table('cr_invoices', function (Blueprint $table) {
            $table->removeColumn('vendor_id');
        });

        Schema::table('cr_messages', function (Blueprint $table) {
            $table->removeColumn('vendor_id');
        });
    }
};
