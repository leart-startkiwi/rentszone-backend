<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable();
            $table->string('author_type')->default(addslashes(User::class));
        });
    }

    public function down(): void
    {
        Schema::table('cr_cars', function (Blueprint $table) {
            $table->dropColumn('author_id');
            $table->dropColumn('author_type');
        });
    }
};
