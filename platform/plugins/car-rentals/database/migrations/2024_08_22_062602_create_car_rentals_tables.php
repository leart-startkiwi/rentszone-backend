<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('cr_customers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('status', 30)->default('published');
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->string('email_verify_token', 120)->nullable();
            $table->timestamps();
        });

        Schema::create('cr_customer_password_resets', function (Blueprint $table): void {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('cr_currencies', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 60);
            $table->string('symbol', 10);
            $table->tinyInteger('is_prefix_symbol')->unsigned()->default(0);
            $table->tinyInteger('decimals')->unsigned()->default(0);
            $table->integer('order')->default(0)->unsigned();
            $table->tinyInteger('is_default')->default(0);
            $table->double('exchange_rate')->default(1);
            $table->timestamps();
        });

        Schema::create('cr_car_makes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('logo')->nullable();
            $table->string('status', 30)->default('published');
            $table->timestamps();
        });

        Schema::create('cr_car_makes_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_makes_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'cr_car_makes_id'], 'cr_car_makes_translations_primary');
        });

        Schema::create('cr_taxes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->decimal('percentage', 8, 6)->nullable();
            $table->string('status', 30)->default('published');
            $table->smallInteger('priority')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('cr_taxes_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_taxes_id');
            $table->string('name', 120);

            $table->primary(['lang_code', 'cr_taxes_id'], 'cr_taxes_translations_primary');
        });

        Schema::create('cr_bookings', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_number')->unique()->nullable();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->integer('customer_age')->nullable()->unsigned();
            $table->string('customer_id')->nullable();
            $table->double('amount');
            $table->double('sub_total');
            $table->double('coupon_amount')->default(0);
            $table->string('coupon_code')->nullable();
            $table->double('tax_amount')->default(0);
            $table->string('currency_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->text('note')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_booking_cars', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id');
            $table->foreignId('car_id');
            $table->string('car_image')->nullable();
            $table->string('car_name');
            $table->double('price');
            $table->foreignId('currency_id');
            $table->foreignId('pickup_address_id')->nullable();
            $table->foreignId('return_address_id')->nullable();
            $table->dateTime('rental_start_date');
            $table->dateTime('rental_end_date');
            $table->timestamps();
        });

        Schema::create('cr_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->foreignId('payment_id')->nullable()->index();
            $table->foreignId('currency_id')->nullable()->nullable();
            $table->morphs('reference');
            $table->string('code')->unique();
            $table->double('sub_total');
            $table->double('tax_amount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('amount');
            $table->string('status')->index()->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cr_invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id');
            $table->string('name');
            $table->string('description', 400)->nullable();
            $table->unsignedInteger('qty');
            $table->decimal('sub_total', 15)->unsigned();
            $table->decimal('tax_amount', 15)->default(0)->unsigned();
            $table->decimal('discount_amount', 15)->default(0)->unsigned();
            $table->decimal('amount', 15)->unsigned();
            $table->timestamps();
        });

        Schema::create('cr_cars', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('images')->nullable();
            $table->string('license_plate', 15)->nullable();
            $table->foreignId('make_id')->index()->nullable();
            $table->string('status', 20)->default('available')->index();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->foreignId('vehicle_type_id')->nullable()->index();
            $table->foreignId('transmission_id')->nullable();
            $table->foreignId('fuel_type_id')->nullable();
            $table->unsignedTinyInteger('number_of_seats')->nullable();
            $table->unsignedTinyInteger('number_of_doors')->nullable();
            $table->double('rental_rate')->default(0)->unsigned();
            $table->string('rental_type')->default('per_day');
            $table->text('rental_available_types')->nullable();
            $table->text('insurance_info')->nullable();
            $table->string('vin')->nullable()->index();
            $table->string('current_location')->nullable();
            $table->foreignId('tax_id')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_used')->default(0);
            $table->timestamps();
        });

        Schema::create('cr_cars_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_cars_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->text('content')->nullable();

            $table->primary(['lang_code', 'cr_cars_id'], 'cr_cars_translations_primary');
        });

        Schema::create('cr_car_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_types_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_types_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'cr_car_types_id'], 'cr_car_types_translations_primary');
        });

        Schema::create('cr_car_transmissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_transmissions_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_transmissions_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'cr_car_transmissions_id'], 'cr_car_transmissions_translations_primary');
        });

        Schema::create('cr_car_fuels', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_fuels_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_fuels_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'cr_car_fuels_id'], 'cr_car_fuels_translations_primary');
        });

        Schema::create('cr_tags', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 400)->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_tag', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('car_id');
            $table->foreignId('tag_id');
        });

        Schema::create('cr_tags_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_tags_id');
            $table->string('name', 120)->nullable();
            $table->string('description', 400)->nullable();

            $table->primary(['lang_code', 'cr_tags_id'], 'cr_tags_translations_primary');
        });

        Schema::create('cr_car_reviews', function (Blueprint $table): void {
            $table->id();
            $table->text('content');
            $table->double('star')->default(0);
            $table->foreignId('customer_id');
            $table->foreignId('car_id');
            $table->foreignId('booking_id')->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_coupons', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('type', 60);
            $table->double('value');
            $table->tinyInteger('is_unlimited_expires')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->tinyInteger('is_unlimited')->default(1);
            $table->integer('limit')->default(0);
            $table->integer('used')->default(0);
            $table->timestamps();
        });

        Schema::create('cr_car_colors', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_colors_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_colors_id');
            $table->string('name', 120)->nullable();

            $table->primary(['lang_code', 'cr_car_colors_id'], 'cr_car_colors_translations_primary');
        });

        Schema::create('cr_cars_colors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cr_car_id');
            $table->foreignId('cr_car_color_id');
            $table->timestamps();
        });

        Schema::create('cr_car_maintenance_histories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->double('amount')->default(0);
            $table->foreignId('currency_id');
            $table->dateTime('date')->nullable();
            $table->foreignId('car_id')->index();
            $table->timestamps();
        });

        Schema::create('cr_car_maintenance_histories_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_maintenance_histories_id');
            $table->string('name', 120)->nullable();

            $table->primary(['lang_code', 'cr_car_maintenance_histories_id'], 'cr_car_maintenance_histories_translations_primary');
        });

        Schema::create('cr_services', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->double('price')->default(0)->unsigned();
            $table->string('image')->nullable();
            $table->string('logo')->nullable();
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_booking_service', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id');
            $table->foreignId('service_id');
        });

        Schema::create('cr_services_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_services_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->text('content')->nullable();

            $table->primary(['lang_code', 'cr_services_id'], 'cr_services_translations_primary');
        });

        Schema::create('cr_car_views', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('car_id')->index();
            $table->integer('views')->default(1);
            $table->date('date')->default(now());

            $table->unique(['car_id', 'date']);
        });

        Schema::create('cr_car_amenities', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('status')->index()->default('pending');
            $table->timestamps();
        });

        Schema::create('cr_car_amenities_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('cr_car_amenities_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'cr_car_amenities_id'], 'cr_car_amenities_translations_primary');
        });

        Schema::create('cr_cars_amenities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cr_car_id');
            $table->foreignId('cr_car_amenity_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cr_car_maintenance_histories_translations');
        Schema::dropIfExists('cr_car_maintenance_histories');
        Schema::dropIfExists('cr_cars_colors');
        Schema::dropIfExists('cr_colors_translations');
        Schema::dropIfExists('cr_car_colors');
        Schema::dropIfExists('cr_car_colors_translations');
        Schema::dropIfExists('cr_car_reviews');
        Schema::dropIfExists('cr_tags_translations');
        Schema::dropIfExists('cr_car_tag');
        Schema::dropIfExists('cr_tags');
        Schema::dropIfExists('cr_car_fuels_translations');
        Schema::dropIfExists('cr_car_fuels');
        Schema::dropIfExists('cr_car_transmissions_translations');
        Schema::dropIfExists('cr_car_transmissions');
        Schema::dropIfExists('cr_car_types_translations');
        Schema::dropIfExists('cr_car_types');
        Schema::dropIfExists('cr_cars_translations');
        Schema::dropIfExists('cr_cars');
        Schema::dropIfExists('cr_car_views');
        Schema::dropIfExists('cr_invoice_items');
        Schema::dropIfExists('cr_coupons');
        Schema::dropIfExists('cr_invoices');
        Schema::dropIfExists('cr_booking_cars');
        Schema::dropIfExists('cr_booking_cars');
        Schema::dropIfExists('cr_bookings');
        Schema::dropIfExists('cr_booking_service');
        Schema::dropIfExists('cr_taxes');
        Schema::dropIfExists('cr_taxes_translations');
        Schema::dropIfExists('cr_car_makes_translations');
        Schema::dropIfExists('cr_car_makes');
        Schema::dropIfExists('cr_currencies');
        Schema::dropIfExists('cr_customer_password_resets');
        Schema::dropIfExists('cr_customers');
        Schema::dropIfExists('cr_services');
        Schema::dropIfExists('cr_services_translations');
        Schema::dropIfExists('cr_car_amenities');
        Schema::dropIfExists('cr_car_amenities_translations');
    }
};
