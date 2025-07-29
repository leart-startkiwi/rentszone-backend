<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        try {
            DB::table('settings')
                ->where('key', 'car_rentals_enabled_rental_booking')
                ->update(['key' => 'car_rentals_enabled_car_rental']);
        } catch (Throwable) {
            //
        }
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'car_rentals_enabled_car_rental')
            ->update(['key' => 'car_rentals_enabled_rental_booking']);
    }
};
