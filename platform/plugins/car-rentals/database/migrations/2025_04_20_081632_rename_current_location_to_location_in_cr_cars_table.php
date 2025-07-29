<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->renameColumn('current_location', 'location');
        });
    }

    public function down(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->renameColumn('location', 'current_location');
        });
    }
};
