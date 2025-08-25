<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    // All methods are now properly inside the class

    public function recentBookings(Request $request)
    {
        $vendor = auth('sanctum')->user();
        if (!$vendor) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get all car IDs owned by this vendor
        $vendorCarIds = \Botble\CarRentals\Models\Car::where('vendor_id', $vendor->id)->pluck('id');
        if ($vendorCarIds->isEmpty()) {
            return response()->json([
                'bookings' => [],
                'message' => 'No cars found for this vendor.'
            ]);
        }

        // Get the 10 most recent bookings for these cars
        $bookingCars = \Botble\CarRentals\Models\BookingCar::whereIn('car_id', $vendorCarIds)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $bookings = $bookingCars->map(function ($bookingCar) {
            $booking = $bookingCar->booking;
            $car = $bookingCar->car;
            $customer = $booking ? $booking->customer : null;
            return [
                'car_name' => $car ? $car->name : null,
                'user_name' => $customer ? $customer->name : null,
                'rental_start_date' => $bookingCar->rental_start_date,
                'rental_end_date' => $bookingCar->rental_end_date,
                'total_price' => $booking ? $booking->amount : null,
                'status' => $booking ? $booking->status : null,
            ];
        });

        return response()->json([
            'bookings' => $bookings,
        ]);
    }

    public function topPerformingCars(Request $request)
    {
        $vendor = auth('sanctum')->user();
        if (!$vendor) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get all car IDs owned by this vendor
        $vendorCarIds = \Botble\CarRentals\Models\Car::where('vendor_id', $vendor->id)->pluck('id');
        if ($vendorCarIds->isEmpty()) {
            return response()->json([
                'cars' => [],
                'message' => 'No cars found for this vendor.'
            ]);
        }

        // Get completed bookings for these cars, group by car_id, count and sum
        $completedBookingCars = \Botble\CarRentals\Models\BookingCar::whereIn('car_id', $vendorCarIds)
            ->whereHas('booking', function ($q) {
                $q->where('status', 'completed');
            })
            ->selectRaw('car_id, COUNT(*) as bookings_count, SUM(price) as total_earnings')
            ->groupBy('car_id')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $cars = $completedBookingCars->map(function ($bookingCarGroup) {
            $car = \Botble\CarRentals\Models\Car::find($bookingCarGroup->car_id);
            if (!$car) return null;
            // Get average rating for this car (as in CarController)
            $averageRating = \Botble\CarRentals\Models\CarReview::where('car_id', $car->id)
                ->where('status', 'published')
                ->avg('star');
            $averageRating = $averageRating ? round((float)$averageRating, 1) : 0.0;
            return [
                'car_name' => $car->name,
                'bookings_count' => (int) $bookingCarGroup->bookings_count,
                'total_earnings' => (float) $bookingCarGroup->total_earnings,
                'average_rating' => $averageRating,
            ];
        })->filter()->values();

        return response()->json([
            'cars' => $cars,
        ]);
    }

    public function dashboardStats(Request $request)
    {
        $vendor = auth('sanctum')->user();
        if (!$vendor) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

    // 1. Total cars (include all, regardless of moderation status)
    $totalCars = \Botble\CarRentals\Models\Car::withoutGlobalScopes()->where('vendor_id', $vendor->id)->count();

        // 2. Active cars (moderation_status = approved)
        $activeCars = \Botble\CarRentals\Models\Car::where('vendor_id', $vendor->id)
            ->where('moderation_status', 'approved')
            ->count();

        // 3. Total earnings this month (sum of BookingCar.price for completed bookings this month)
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $completedBookingIds = \Botble\CarRentals\Models\Booking::where('vendor_id', $vendor->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->pluck('id');
        $totalEarnings = \Botble\CarRentals\Models\BookingCar::whereIn('booking_id', $completedBookingIds)->sum('price');

        // 4. Pending bookings (count bookings for vendor's cars)
        $vendorCarIds = \Botble\CarRentals\Models\Car::where('vendor_id', $vendor->id)->pluck('id');
        $pendingBookings = \Botble\CarRentals\Models\Booking::where('status', 'pending')
            ->whereHas('car', function ($q) use ($vendorCarIds) {
                $q->whereIn('car_id', $vendorCarIds);
            })
            ->count();

        // 5. Average vendor rating (average star of all reviews for vendor's cars, status = published)
        $carIds = \Botble\CarRentals\Models\Car::where('vendor_id', $vendor->id)->pluck('id');
        $averageRating = 0.0;
        if ($carIds->count() > 0) {
            $averageRating = \Botble\CarRentals\Models\CarReview::whereIn('car_id', $carIds)
                ->where('status', 'published')
                ->avg('star') ?? 0.0;
            $averageRating = round((float)$averageRating, 1);
        }

        return response()->json([
            'total_cars' => $totalCars,
            'active_cars' => $activeCars,
            'total_earnings_this_month' => $totalEarnings,
            'pending_bookings' => $pendingBookings,
            'average_vendor_rating' => $averageRating,
        ]);
    }
}
