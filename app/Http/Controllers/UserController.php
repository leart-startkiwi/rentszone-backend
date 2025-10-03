<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Booking;


class UserController extends Controller
{
    public function forgotPassword(Request $request): JsonResponse
    {
        // Debug: Log mail config at runtime
        \Log::info('MAIL CONFIG DEBUG', [
            'default' => config('mail.default'),
            'mailers' => config('mail.mailers'),
            'from' => config('mail.from'),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $customer = Customer::where('email', $request->email)->first();

        // Always return the same response for security
        if (!$customer) {
            return response()->json([
                'message' => 'If your email exists in our system, you will receive a password reset link shortly.'
            ], 200);
        }

        $status = \Illuminate\Support\Facades\Password::broker('customers')->sendResetLink([
            'email' => $request->email
        ]);

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent to your email address.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unable to send password reset link.'
            ], 500);
        }
    }
    public function changePassword(Request $request): JsonResponse
    {
        try {
            // Check if user is authenticated
            if (!auth('sanctum')->check()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please login first.',
                ], 401);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|max:60|confirmed',
                'new_password_confirmation' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $customer = auth('sanctum')->user();

            // Check if current password matches the saved one in database
            if (!Hash::check($request->current_password, $customer->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect.',
                ], 400);
            }

            // Update the password
            $customer->password = Hash::make($request->new_password);
            $customer->save();

            return response()->json([
                'message' => 'Password changed successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to change password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            // Check if user is authenticated
            if (!auth('sanctum')->check()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please login first.',
                ], 401);
            }

            $customer = auth('sanctum')->user();

            // Optional: Check if customer has active bookings
            $activeBookings = Booking::where('customer_id', $customer->id)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->exists();

            if ($activeBookings) {
                return response()->json([
                    'message' => 'Cannot delete account. You have active bookings. Please complete or cancel them first.',
                ], 400);
            }

            // Delete the customer account
            $customer->delete();

            return response()->json([
                'message' => 'Account deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete account',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getVendors(Request $request): JsonResponse
    {
        try {
            // Get vendors with their location details
            $query = Customer::select([
                'cr_customers.id',
                'cr_customers.name',
                'cr_customers.avatar',
                'cr_customers.phone',
                'cr_customers.email',
                'cr_customers.location',
                'cr_car_addresses.detail_address as location_name'
            ])
            ->leftJoin('cr_car_addresses', 'cr_customers.location', '=', 'cr_car_addresses.id')
            ->where('cr_customers.is_vendor', 1);

            // Filter by location if provided
            if ($request->has('location') && $request->input('location')) {
                $query->where('cr_customers.location', $request->input('location'));
            }

            $vendors = $query->get();

            // Transform vendors with car count and average rating
            $vendorsWithStats = $vendors->map(function ($vendor) {
                // Get all approved cars for this vendor
                $approvedCarIds = \Botble\CarRentals\Models\Car::withoutGlobalScopes()
                    ->where('vendor_id', $vendor->id)
                    ->where('moderation_status', 'approved')
                    ->pluck('id');

                $totalCars = $approvedCarIds->count();

                // Calculate average rating across all vendor's approved cars
                $averageRating = 0;
                if ($totalCars > 0) {
                    $totalRating = \Botble\CarRentals\Models\CarReview::whereIn('car_id', $approvedCarIds)
                        ->where('status', 'published')
                        ->avg('star');
                    
                    $averageRating = $totalRating ? round((float) $totalRating, 1) : 0.0;
                }

                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'avatar' => $vendor->avatar,
                    'phone' => $vendor->phone,
                    'email' => $vendor->email,
                    'location' => $vendor->location_name,
                    'location_id' => $vendor->location,
                    'average_rating' => $averageRating,
                    'total_cars' => $totalCars,
                ];
            });

            // Sort by total cars (descending)
            $sortedVendors = $vendorsWithStats->sortByDesc('total_cars')->values();

            return response()->json([
                'message' => 'Vendors retrieved successfully!',
                'vendors' => $sortedVendors,
                'total_vendors' => $sortedVendors->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve vendors',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
