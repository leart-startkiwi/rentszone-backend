<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('cr_customers', 'bank_info')) {
            Schema::table('cr_customers', function (Blueprint $table) {
                $table->json('bank_info')->nullable();
                $table->string('payout_payment_method', 120)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cr_customers', 'bank_info')) {
            Schema::table('cr_customers', function (Blueprint $table) {
                $table->dropColumn(['bank_info', 'payout_payment_method']);
            });
        }
    }
};
