<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->input('email'))->first();

        if (! $customer || ! Hash::check($request->input('password'), $customer->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

       $token = $customer->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'customer' => $customer,
            'token' => $token, // Uncomment if using API tokens
        ]);
    }
}