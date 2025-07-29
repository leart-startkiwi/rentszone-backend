<?php

use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Theme\Carento\Http\Controllers\CarentoController;

// Custom routes
// You can delete this route group if you don't need to add your custom routes.
Route::group(['controller' => CarentoController::class, 'middleware' => ['web', 'core']], function (): void {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function (): void {

        // Add your custom route here
        // Ex: Route::get('hello', 'getHello');

        Route::post('calculate-loan-car', [CarentoController::class, 'calculateLoanCar'])->name('public.calculate-loan-car');

        Route::group(['prefix' => 'ajax', 'as' => 'public.ajax.', 'middleware' => [RequiresJsonRequestMiddleware::class]], function () {
            Route::get('search-popular-vehicles', 'ajaxSearchPopularVehicles')
                ->name('search-popular-vehicles');
        });
    });
});

Theme::routes();
