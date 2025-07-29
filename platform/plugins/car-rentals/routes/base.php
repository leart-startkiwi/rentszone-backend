<?php

use Botble\Base\Facades\AdminHelper;
use Botble\CarRentals\Http\Controllers\WithdrawalInvoiceController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\CarRentals\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'car-rentals', 'as' => 'car-rentals.'], function (): void {
            Route::group(['prefix' => 'customers', 'as' => 'store.'], function (): void {
                Route::get('view/{id}', [
                    'as' => 'view',
                    'uses' => 'StoreRevenueController@view',
                ])->wherePrimaryKey();

                Route::group(['prefix' => 'revenues', 'as' => 'revenue.'], function (): void {
                    Route::match(['GET', 'POST'], 'list/{id}', [
                        'as' => 'index',
                        'uses' => 'StoreRevenueController@index',
                        'permission' => 'car-rentals.customers.view',
                    ])->wherePrimaryKey();

                    Route::post('create/{id}', [
                        'as' => 'create',
                        'uses' => 'StoreRevenueController@store',
                    ])->wherePrimaryKey();
                });
            });

            Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawal.'], function (): void {
                Route::resource('', 'WithdrawalController')
                    ->parameters(['' => 'withdrawal'])
                    ->except([
                        'create',
                        'store',
                    ]);

                Route::get('{withdrawal}/invoice', [WithdrawalInvoiceController::class, '__invoke'])
                    ->name('invoice');
            });
        });
    });
});
