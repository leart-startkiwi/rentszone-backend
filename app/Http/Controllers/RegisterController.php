<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\CarRentals\Models\Customer;
use Botble\Base\Facades\BaseHelper;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {

        if (!CarRentalsHelper::isEnabledCustomerRegistration()) {
            return response()->json(['message' => 'Registration is disabled.'], 403);
        }

        // You may want to add custom validation or events here

        $data = $request->validated();

        $customer = new Customer();
        $customer->fill([
            'name' => BaseHelper::clean($data['name']),
            'email' => BaseHelper::clean($data['email']),
            'phone' => BaseHelper::clean($data['phone'] ?? null),
            'password' => Hash::make($data['password']),
        ]);

        $isEmailVerifyEnabled = CarRentalsHelper::isEnabledEmailVerification();

        $customer->confirmed_at = $isEmailVerifyEnabled ? null : Carbon::now();
        $customer->is_vendor = $request->boolean('is_vendor');
        $customer->save();

        event(new Registered($customer));

        if ($isEmailVerifyEnabled) {
            $customer->sendEmailVerificationNotification();
            return response()->json([
                'message' => 'We have sent you an email to verify your email. Please check and confirm your email address!',
                'requires_email_verification' => true,
            ], 201);
        }

        // Optionally, log the customer in and return a token if using API authentication

        return response()->json([
            'message' => 'Registered successfully!',
            'customer' => $customer,
            'requires_email_verification' => false,
        ], 201);
    }
}
