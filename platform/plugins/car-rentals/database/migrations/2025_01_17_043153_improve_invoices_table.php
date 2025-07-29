<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_invoices', function (Blueprint $table): void {
            $table->string('customer_email')->nullable()->change();
            $table->string('customer_phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cr_invoices', function (Blueprint $table): void {
            $table->string('customer_email')->change();
            $table->string('customer_phone')->change();
        });
    }
};
