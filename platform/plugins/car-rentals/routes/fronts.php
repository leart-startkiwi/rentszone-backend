<?php

use Botble\CarRentals\Http\Controllers\Fronts\CouponController;
use Botble\CarRentals\Http\Controllers\Fronts\MessageController;
use Botble\CarRentals\Http\Controllers\Fronts\PublicController;
use Botble\CarRentals\Http\Controllers\Fronts\ServiceController;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\Service;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Theme::registerRoutes(function (): void {
        Route::get(SlugHelper::getPrefix(Car::class, 'cars') . '/{slug}', [PublicController::class, 'getCar']);
        Route::get('booking/external/{slug}', [PublicController::class, 'redirectToExternalBooking'])->name('public.car.external-booking');
        Route::get(SlugHelper::getPrefix(Service::class, 'services') . '/{slug}', [PublicController::class, 'getService']);
        Route::get(SlugHelper::getPrefix(Car::class, 'cars'), [PublicController::class, 'getCars'])->name('public.cars');
        Route::get('ajax/cars', [PublicController::class, 'ajaxGetCars'])->name('public.ajax.cars');
        Route::get('ajax/car-makes', [PublicController::class, 'ajaxGetCarsMake'])->name('public.ajax.car_makes');
        Route::get('ajax/locations', [PublicController::class, 'ajaxGetLocation'])->name('public.ajax.locations');
        Route::post('booking', [PublicController::class, 'postBooking'])->name('public.booking');
        Route::get('booking/{token}', [PublicController::class, 'getBooking'])->name('public.booking.form');
        Route::get('ajax/booking/{token}/update', [PublicController::class, 'updateGetBooking'])->name(
            'public.ajax.booking.update'
        );
        Route::post('ajax/booking/{token}/services/update', [ServiceController::class, 'store'])->name(
            'public.ajax.booking.services.update'
        );
        Route::post('checkout', [PublicController::class, 'postCheckout'])->name('public.checkout.post');
        Route::get('checkout/{transactionId}/success', [PublicController::class, 'getCheckoutSuccess'])->name(
            'public.checkout.success'
        );
        Route::get(SlugHelper::getPrefix(CarMake::class, 'makes') . '/{slug}', [PublicController::class, 'getMake']);

        Route::post('ajax/booking/estimate', [PublicController::class, 'estimateBooking'])->name(
            'public.ajax.booking.estimate'
        );

        Route::post('car-review/create', [PublicController::class, 'postCarReviews'])->name('public.car-reviews.create');

        Route::prefix('coupon')->name('public.coupon.')->group(function (): void {
            Route::post('apply', [CouponController::class, 'apply'])->name('apply');
            Route::post('remove', [CouponController::class, 'remove'])->name('remove');
            Route::get('refresh', [CouponController::class, 'refresh'])->name('refresh');
        });

        Route::get('currency/switch/{code?}', [PublicController::class, 'switchCurrency'])->name('public.currency.switch');

        Route::post('car-rentals/{id}/message', [MessageController::class, 'store'])->name('car-rentals.message');
    });
}
