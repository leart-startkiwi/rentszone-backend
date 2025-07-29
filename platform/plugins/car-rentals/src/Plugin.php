<?php

namespace Botble\CarRentals;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('cr_customers');
        Schema::dropIfExists('cr_customer_password_resets');
        Schema::dropIfExists('cr_currencies');
        Schema::dropIfExists('cr_makes');
    }
}
