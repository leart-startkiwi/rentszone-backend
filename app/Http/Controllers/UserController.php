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
}
