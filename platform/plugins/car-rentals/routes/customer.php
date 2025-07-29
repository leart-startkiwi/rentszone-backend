<?php

use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function (): void {
    Route::group([
        'namespace' => 'Botble\CarRentals\Http\Controllers\Customers',
        'middleware' => ['customer.guest'],
        'as' => 'customer.',
    ], function (): void {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.post');
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->name('register.post');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.update');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.post');
    });

    Route::group([
        'namespace' => 'Botble\CarRentals\Http\Controllers\Customers',
        'middleware' => [
            'web',
            'core',
        ],
        'as' => 'customer.',
    ], function (): void {
        Route::get('register/confirm/resend', 'RegisterController@resendConfirmation')
            ->name('resend_confirmation');
        Route::get('register/confirm/{user}', 'RegisterController@confirm')
            ->name('confirm');
    });

    Route::group([
        'namespace' => 'Botble\CarRentals\Http\Controllers\Customers',
        'middleware' => ['customer'],
        'as' => 'customer.',
    ], function (): void {
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('overview', 'PublicController@getOverview')->name('overview');

        Route::get('profile', 'PublicController@getEditProfile')->name('profile');
        Route::post('profile', 'PublicController@postEditProfile')->name('profile.post');

        Route::get('change-password', 'PublicController@getChangePassword')->name('change-password');
        Route::post('change-password', 'PublicController@postChangePassword')->name('change-password.post');

        Route::post('avatar', 'PublicController@postAvatar')->name('avatar');

        Route::get('bookings', 'PublicController@getBookings')->name('bookings');

        Route::get('reviews', 'PublicController@getReviews')->name('reviews');

        Route::get('bookings/{transactionId}', 'PublicController@getBookingDetail')->name('bookings.show');

        Route::get('invoices/{invoice}/generate-invoice', 'PublicController@getGenerateInvoice')
            ->name('invoices.generate');
    });
});
