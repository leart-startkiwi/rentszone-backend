<?php

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('cr_car_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120)->default('')->nullable(false);
            $table->foreignId('parent_id')->default(0);
            $table->string('description', 400)->nullable();
            $table->string('status', 60)->default(BaseStatusEnum::PUBLISHED)->nullable(false);
            $table->string('icon', 60)->nullable();
            $table->tinyInteger('order')->default(0)->nullable(false);
            $table->unsignedTinyInteger('is_featured')->default(0)->nullable(false);
            $table->unsignedTinyInteger('is_default')->default(0)->nullable(false);
            $table->timestamps();
        });

        Schema::create('cr_cars_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cr_car_category_id')->nullable()->index();
            $table->foreignId('cr_car_id')->nullable()->index();
        });

        Schema::create('cr_car_categories_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_categories_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->text('content')->nullable();

            $table->primary(['lang_code', 'cr_car_categories_id'], 'cr_car_categories_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cr_car_categories');
        Schema::dropIfExists('cr_cars_categories');
        Schema::dropIfExists('cr_car_categories_translations');
    }
};
