<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_taxes', function (Blueprint $table): void {
            $table->decimal('percentage', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cr_taxes', function (Blueprint $table): void {
            $table->decimal('percentage', 8, 6)->nullable()->change();
        });
    }
};
