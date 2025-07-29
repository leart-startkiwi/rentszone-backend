<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('cr_customer_withdrawals')) {
            Schema::create('cr_customer_withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id');
                $table->decimal('fee', 15);
                $table->decimal('amount', 15);
                $table->decimal('current_balance', 15, 2)->default(0);
                $table->string('currency', 120);
                $table->text('description')->nullable();
                $table->string('payment_channel', 60)->nullable();
                $table->foreignId('user_id')->nullable();
                $table->string('status', 60)->default('pending');
                $table->text('images')->nullable();
                $table->text('bank_info')->nullable();
                $table->string('transaction_id', 60)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cr_customer_withdrawals');
    }
};
