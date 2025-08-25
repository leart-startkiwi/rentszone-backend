<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CarAddressController;
use App\Http\Controllers\CarMakesController;
use App\Http\Controllers\CarTransmissionsController;
use App\Http\Controllers\CarFuelTypesController;
use App\Http\Controllers\CarColorsController;
use App\Http\Controllers\CarAmenitiesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorDashboardController;


Route::group(['prefix' => 'v1'], function() {

    Route::post('register', [RegisterController::class, 'store']);
    Route::post('forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('login', [LoginController::class, 'login']);
    Route::get('cartypes', [CarTypeController::class, 'carTypesWithCount']);
    Route::get('car/{id}', [CarController::class, 'getCarDetails']);
    Route::get('cars/most-viewed-cars', [CarController::class, 'mostViewed']);
    Route::get('cars/filter-by-type', [CarController::class, 'filterByType']);
    Route::get('car-addresses', [CarAddressController::class, 'getAddresses']);
    Route::get('car-makes', [CarMakesController::class, 'getCarMakes']);
    Route::get('car-types', [CarTypeController::class, 'getCarTypes']);
    Route::get('car-transmissions', [CarTransmissionsController::class, 'getCarTransmissions']);
    Route::get('car-fuel-types', [CarFuelTypesController::class, 'getCarFuelTypes']);
    Route::get('car-colors', [CarColorsController::class, 'getCarColors']);
    Route::get('car-amenities', [CarAmenitiesController::class, 'getAmenities']);
    Route::post('cars/{id}/view', [CarController::class, 'incrementView']);
    Route::get('cars', [CarController::class, 'index']);
    Route::get('car/{id}/future-bookings', [BookingController::class, 'getFutureBookingsForCar']);
    Route::get('reviews/{carId}', [ReviewController::class, 'getCarReviews']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::get('vendor/dashboard-stats', [VendorDashboardController::class, 'dashboardStats']);
        Route::get('vendor/dashboard-recent-bookings', [VendorDashboardController::class, 'recentBookings']);
        Route::get('vendor/dashboard-top-performing-cars', [VendorDashboardController::class, 'topPerformingCars']);
        Route::get('cars/filter-by-type', [CarController::class, 'filterByType']);
        Route::post('cars/{id}/message', [MessageController::class, 'sendMessage']);
        Route::delete('car/{id}', [CarController::class, 'deleteCar']);
        Route::post('cars', [CarController::class, 'store']);
        Route::patch('cars/{id}', [CarController::class, 'patch']);
        Route::post('cars/{id}/update', [CarController::class, 'patch']);
        Route::get('cars/myCars', [CarController::class, 'myCars']);
        Route::get('cars/my-car/{id}', [CarController::class, 'getMyCarDetails']);
        Route::get('vendor-messages', [MessageController::class, 'getMessages']);
        Route::patch('messages/{id}', [MessageController::class, 'markAsRead']);
        Route::post('bookings', [BookingController::class, 'createBooking']);
        Route::post('reviews', [ReviewController::class, 'store']);
        Route::get('my-bookings', [BookingController::class, 'getMyBookings']);
        Route::get('vendor-bookings', [BookingController::class, 'getVendorBookings']);
        Route::patch('bookings/{bookingId}/status', [BookingController::class, 'updateBookingStatus']);
        Route::get('car/{carId}/has-booked', [BookingController::class, 'hasUserBookedCar']);
        Route::delete('account', [UserController::class, 'deleteAccount']);
    });
});
