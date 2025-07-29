<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_cars', function (Blueprint $table): void {
            $table
                ->unsignedBigInteger('pick_address_id')
                ->comment('Id table cr_car_addresses')
                ->nullable()
                ->index('cr_pick_address_index')
                ->after('current_location');

            $table
                ->unsignedBigInteger('return_address_id')
                ->comment('Id table cr_car_addresses')
                ->nullable()
                ->index('cr_return_address_index')
                ->after('pick_address_id');
        });
    }

    public function down(): void
    {
        Schema::table('cr_cars', function (Blueprint $table): void {
            $table->dropIndex('cr_pick_address_index');
            $table->dropColumn('pick_address_id');
            $table->dropIndex('cr_return_address_index');
            $table->dropColumn('return_address_id');
        });
    }
};
