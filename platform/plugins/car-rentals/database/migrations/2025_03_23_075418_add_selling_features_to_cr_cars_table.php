<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->boolean('is_for_sale')->default(false)->after('is_used');
            $table->decimal('sale_price', 15, 2)->nullable()->after('is_for_sale');
            $table->string('condition', 30)->nullable()->after('sale_price');
            $table->string('ownership_history')->nullable()->after('condition');
            $table->text('warranty_information')->nullable()->after('ownership_history');
            $table->string('sale_status', 30)->default('available')->after('warranty_information');
        });
    }

    public function down(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->dropColumn([
                'is_for_sale',
                'sale_price',
                'condition',
                'ownership_history',
                'warranty_information',
                'sale_status',
            ]);
        });
    }
};
