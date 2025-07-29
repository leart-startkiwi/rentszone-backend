<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_customers', function (Blueprint $table) {
            $table->dateTime('vendor_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cr_customers', function (Blueprint $table) {
            $table->dropColumn('vendor_verified_at');
        });
    }
};
