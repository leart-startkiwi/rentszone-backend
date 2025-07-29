<?php

namespace Botble\CarRentals\Providers;

use Botble\Base\Facades\Assets;
use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen('core.assets.js.begin', function () {
            Assets::addScriptsDirectly([
                'vendor/core/plugins/car-rentals/js/store-revenue.js',
            ]);
        });
    }
}
