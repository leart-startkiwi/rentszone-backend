<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('cr_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('car_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->string('name', 60);
            $table->string('email', 60)->nullable();
            $table->text('content')->nullable();
            $table->ipAddress()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cr_messages');
    }
};
