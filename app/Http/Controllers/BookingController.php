<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Http\Requests\CreateBookingRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\BookingCar;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BookingController extends Controller
{
public function createBooking(CreateBookingRequest $request)
{
    try {
        $validated = $request->validated();

        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }

        $customer = auth('sanctum')->user();

        // Get car
        $car = Car::findOrFail($validated['car_id']);

        // Check if user already has a pending booking for this car
        $existingPendingBooking = Booking::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->whereHas('car', function ($query) use ($car) {
                $query->where('car_id', $car->id);
            })
            ->exists();

        if ($existingPendingBooking) {
            return response()->json([
                'message' => 'You already have a pending booking request for this car. Please wait for the vendor\'s response.',
            ], 409);
        }

        // Check if car is available for the requested dates
        $startDate = Carbon::parse($validated['rental_start_date']);
        $endDate = Carbon::parse($validated['rental_end_date']);

        $conflictingBooking = BookingCar::where('car_id', $car->id)
            ->whereHas('booking', function ($query) {
                // Only check confirmed/active bookings, not pending ones
                $query->whereIn('status', ['confirmed', 'processing', 'completed']);
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($subQuery) use ($startDate, $endDate) {
                    // Check if new booking start date falls within existing booking
                    $subQuery->where('rental_start_date', '<=', $startDate->format('Y-m-d'))
                             ->where('rental_end_date', '>=', $startDate->format('Y-m-d'));
                })
                ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                    // Check if new booking end date falls within existing booking
                    $subQuery->where('rental_start_date', '<=', $endDate->format('Y-m-d'))
                             ->where('rental_end_date', '>=', $endDate->format('Y-m-d'));
                })
                ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                    // Check if new booking completely encompasses existing booking
                    $subQuery->where('rental_start_date', '>=', $startDate->format('Y-m-d'))
                             ->where('rental_end_date', '<=', $endDate->format('Y-m-d'));
                });
            })
            ->exists();

        if ($conflictingBooking) {
            return response()->json([
                'message' => 'Car is not available for the selected dates.',
            ], 400);
        }

        // Calculate rental days and price
        $rentalDays = $startDate->diffInDays($endDate) ?: 1;
        $carPrice = $car->rental_rate * $rentalDays;

        // Calculate services cost
        $serviceAmount = 0;
        $serviceIds = $validated['services'] ?? [];

        if ($serviceIds) {
            $services = Service::wherePublished()
                ->whereIn('id', $serviceIds)
                ->get();

            foreach ($services as $service) {
                if ($service->price_type && $service->price_type->getValue() === 'per_day') {
                    $serviceAmount += $service->price * $rentalDays;
                } else {
                    $serviceAmount += $service->price;
                }
            }
        }

        $subTotal = $carPrice + $serviceAmount;

        // Calculate tax
        $taxAmount = 0;
        if ($car->tax && $car->tax->percentage) {
            $taxAmount = $subTotal * $car->tax->percentage / 100;
        }

        $totalAmount = $subTotal + $taxAmount;

        // Create booking
        $booking = Booking::create([
            'status' => 'pending', // Default status
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'customer_age' => $validated['customer_age'] ?? null,
            'note' => $validated['note'] ?? null,
            'booking_number' => Booking::generateUniqueBookingNumber(),
            'vendor_id' => $car->author_id,
            'transaction_id' => Str::upper(Str::random(32)),
            'amount' => $totalAmount,
            'sub_total' => $subTotal,
            'tax_amount' => $taxAmount,
            'currency_id' => 2,
            'payment_method' => 'pending', // Default payment method
            'payment_status' => 'pending', // Default payment status
        ]);

        // Attach services
        if ($serviceIds) {
            $booking->services()->attach($serviceIds);
        }

        // Create booking car record
        BookingCar::create([
            'car_id' => $car->id,
            'car_name' => $car->name,
            'car_image' => Arr::first($car->images),
            'booking_id' => $booking->id,
            'price' => $carPrice,
            'currency_id' => 2,
            'rental_start_date' => $startDate->format('Y-m-d'),
            'rental_end_date' => $endDate->format('Y-m-d'),
            'pickup_address_id' => $car->pick_address_id,
            'return_address_id' => $car->return_address_id ?: $car->pick_address_id,
        ]);

        // Load relationships for response
        $booking->load(['car', 'services', 'customer']);

        return response()->json([
            'message' => 'Booking created successfully!',
            'booking' => $booking,
            'total_amount' => $totalAmount,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to create booking',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getMyBookings(Request $request)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Get bookings for the authenticated user with the correct relationship
        $query = Booking::with(['car'])
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        // Optional: Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $bookings = $query->paginate($perPage);
        
        // Transform the data to include calculated information
        $transformedBookings = $bookings->getCollection()->map(function ($booking) {
            // Get booking car data directly from BookingCar model
            $bookingCar = BookingCar::where('booking_id', $booking->id)->first();
            
            if ($bookingCar) {
                // Calculate rental days
                $startDate = Carbon::parse($bookingCar->rental_start_date);
                $endDate = Carbon::parse($bookingCar->rental_end_date);
                $rentalDays = $startDate->diffInDays($endDate) ?: 1;
                
                // Calculate price based on rental days and car's rental rate
                $dailyRate = $booking->car ? $booking->car->rental_rate : 0;
                $calculatedPrice = $dailyRate * $rentalDays;
                
                // Get pickup address details with city name
                $pickupAddress = null;
                if ($bookingCar->pickup_address_id) {
                    $pickupAddressData = \DB::table('cr_car_addresses')
                        ->leftJoin('cities', 'cr_car_addresses.city_id', '=', 'cities.id')
                        ->select(
                            'cr_car_addresses.id',
                            'cr_car_addresses.detail_address',
                            'cities.name as city_name'
                        )
                        ->where('cr_car_addresses.id', $bookingCar->pickup_address_id)
                        ->first();
                    
                    if ($pickupAddressData) {
                        $pickupAddress = [
                            'id' => $pickupAddressData->id,
                            'detail_address' => $pickupAddressData->detail_address,
                            'city' => $pickupAddressData->city_name,
                        ];
                    }
                }
                
                // Get return address details with city name
                $returnAddress = null;
                if ($bookingCar->return_address_id) {
                    $returnAddressData = \DB::table('cr_car_addresses')
                        ->leftJoin('cities', 'cr_car_addresses.city_id', '=', 'cities.id')
                        ->select(
                            'cr_car_addresses.id',
                            'cr_car_addresses.detail_address',
                            'cities.name as city_name'
                        )
                        ->where('cr_car_addresses.id', $bookingCar->return_address_id)
                        ->first();
                    
                    if ($returnAddressData) {
                        $returnAddress = [
                            'id' => $returnAddressData->id,
                            'detail_address' => $returnAddressData->detail_address,
                            'city' => $returnAddressData->city_name,
                        ];
                    }
                }
                
                return [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'status' => $booking->status,
                    'car_name' => $bookingCar->car_name,
                    'pickup_address' => $pickupAddress,
                    'return_address' => $returnAddress,
                    'rental_dates' => [
                        'start_date' => $bookingCar->rental_start_date,
                        'end_date' => $bookingCar->rental_end_date,
                        'rental_days' => $rentalDays,
                    ],
                    'pricing' => [
                        'daily_rate' => $dailyRate,
                        'rental_days' => $rentalDays,
                        'calculated_price' => $calculatedPrice,
                        'booking_price' => $bookingCar->price,
                        'total_amount' => $booking->amount,
                    ],
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ];
            }
            
            return null;
        })->filter();
        
        return response()->json([
            'message' => 'Bookings retrieved successfully!',
            'bookings' => $transformedBookings->values(),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve bookings',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function getFutureBookingsForCar($carId)
{
    try {
        $today = Carbon::today()->format('Y-m-d');

        $bookings = BookingCar::where('car_id', $carId)
            ->whereHas('booking', function ($query) {
                $query->where('status', 'completed');
            })
            ->where(function ($query) use ($today) {
                // Include bookings that start today or later
                $query->where('rental_start_date', '>=', $today)
                      // OR include ongoing bookings (today is between start and end date)
                      ->orWhere(function ($subQuery) use ($today) {
                          $subQuery->where('rental_start_date', '<=', $today)
                                   ->where('rental_end_date', '>=', $today);
                      });
            })
            ->orderBy('rental_start_date', 'asc')
            ->get(['id', 'rental_start_date', 'rental_end_date']);

        return response()->json([
            'bookings' => $bookings,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve future bookings',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function hasUserBookedCar($carId)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Check if user has ever booked this car
        $hasBooked = Booking::where('customer_id', $user->id)
            ->whereHas('car', function ($query) use ($carId) {
                $query->where('car_id', $carId);
            })
            ->exists();
        
        return response()->json([
            'has_booked' => $hasBooked,
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to check booking history',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getVendorBookings(Request $request)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $vendor = auth('sanctum')->user();
        
        // Get all car IDs owned by this vendor
        $vendorCarIds = Car::where('vendor_id', $vendor->id)->pluck('id');
        
        if ($vendorCarIds->isEmpty()) {
            return response()->json([
                'message' => 'No cars found for this vendor.',
                'bookings' => [],
            ]);
        }
        
        // Get bookings for vendor's cars
        $query = Booking::with(['customer'])
            ->whereHas('car', function ($carQuery) use ($vendorCarIds) {
                $carQuery->whereIn('car_id', $vendorCarIds);
            })
            ->orderBy('created_at', 'desc');
        
        // Optional: Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Optional: Filter by car
        if ($request->has('car_id')) {
            $carId = $request->input('car_id');
            if (in_array($carId, $vendorCarIds->toArray())) {
                $query->whereHas('car', function ($carQuery) use ($carId) {
                    $carQuery->where('car_id', $carId);
                });
            }
        }
        
        // Get all results without pagination
        $bookings = $query->get();
        
        // Transform the data to include car and customer information
        $transformedBookings = $bookings->map(function ($booking) {
            // Get booking car data
            $bookingCar = BookingCar::where('booking_id', $booking->id)->first();
            
            if ($bookingCar) {
                // Calculate rental days
                $startDate = Carbon::parse($bookingCar->rental_start_date);
                $endDate = Carbon::parse($bookingCar->rental_end_date);
                $rentalDays = $startDate->diffInDays($endDate) ?: 1;
                
                // Get car details
                $car = Car::find($bookingCar->car_id);
                
                // Get customer details including avatar
                $customer = \DB::table('cr_customers')
                    ->select('id', 'name', 'email', 'phone', 'avatar')
                    ->where('id', $booking->customer_id)
                    ->first();

                
                // Calculate price based on rental days and car's rental rate
                $dailyRate = $car ? $car->rental_rate : 0;
                $calculatedPrice = $dailyRate * $rentalDays;
                
                // Get pickup address details
                $pickupAddress = null;
                if ($bookingCar->pickup_address_id) {
                    $pickupAddressData = \DB::table('cr_car_addresses')
                        ->leftJoin('cities', 'cr_car_addresses.city_id', '=', 'cities.id')
                        ->select(
                            'cr_car_addresses.id',
                            'cr_car_addresses.detail_address',
                            'cities.name as city_name'
                        )
                        ->where('cr_car_addresses.id', $bookingCar->pickup_address_id)
                        ->first();
                    
                    if ($pickupAddressData) {
                        $pickupAddress = [
                            'id' => $pickupAddressData->id,
                            'detail_address' => $pickupAddressData->detail_address,
                            'city' => $pickupAddressData->city_name,
                        ];
                    }
                }
                
                // Get return address details
                $returnAddress = null;
                if ($bookingCar->return_address_id) {
                    $returnAddressData = \DB::table('cr_car_addresses')
                        ->leftJoin('cities', 'cr_car_addresses.city_id', '=', 'cities.id')
                        ->select(
                            'cr_car_addresses.id',
                            'cr_car_addresses.detail_address',
                            'cities.name as city_name'
                        )
                        ->where('cr_car_addresses.id', $bookingCar->return_address_id)
                        ->first();
                    
                    if ($returnAddressData) {
                        $returnAddress = [
                            'id' => $returnAddressData->id,
                            'detail_address' => $returnAddressData->detail_address,
                            'city' => $returnAddressData->city_name,
                        ];
                    }
                }
                
                return [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'status' => $booking->status,
                    'car_name' => $bookingCar->car_name,
                    'customer' => [
                        'id' => $booking->customer_id,
                        'name' => $booking->customer_name,
                        'email' => $booking->customer_email,
                        'phone' => $booking->customer_phone,
                        'age' => $booking->customer_age,
                        'avatar' => $customer ? $customer->avatar : null,
                    ],
                    'pickup_address' => $pickupAddress,
                    'return_address' => $returnAddress,
                    'rental_dates' => [
                        'start_date' => $bookingCar->rental_start_date,
                        'end_date' => $bookingCar->rental_end_date,
                        'rental_days' => $rentalDays,
                    ],
                    'pricing' => [
                        'daily_rate' => $dailyRate,
                        'rental_days' => $rentalDays,
                        'calculated_price' => $calculatedPrice,
                        'booking_price' => $bookingCar->price,
                        'total_amount' => $booking->amount,
                    ],
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ];
            }
            
            return null;
        })->filter();
        
        return response()->json([
            'message' => 'Vendor bookings retrieved successfully!',
            'bookings' => $transformedBookings->values(),
            'total_bookings' => $transformedBookings->count(),
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve vendor bookings',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function updateBookingStatus($bookingId, Request $request)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Find the booking
        $booking = Booking::findOrFail($bookingId);
        
        // Get the car associated with this booking
        $bookingCar = BookingCar::where('booking_id', $booking->id)->first();
        
        if (!$bookingCar) {
            return response()->json([
                'message' => 'Booking car record not found.',
            ], 404);
        }
        
        $car = Car::find($bookingCar->car_id);
        
        if (!$car) {
            return response()->json([
                'message' => 'Car not found.',
            ], 404);
        }
        
        // Check if user is either the vendor/owner of the car OR the customer who made the booking
        $isVendor = $car->vendor_id === $user->id;
        $isCustomer = $booking->customer_id === $user->id;
        
        if (!$isVendor && !$isCustomer) {
            return response()->json([
                'message' => 'Unauthorized. You can only update your own bookings.',
            ], 403);
        }
        
        // Validate the request
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);
        
        $newStatus = $request->input('status');
        
        // Authorization logic based on user role and status
        if ($car->vendor_id === $user->id) {
            // Vendor can change to any status
            // No restrictions for vendors
        } elseif ($booking->customer_id === $user->id) {
            // Customer can only cancel their booking
            if ($newStatus !== 'cancelled') {
                return response()->json([
                    'message' => 'Customers can only cancel bookings.',
                ], 403);
            }
        } else {
            // Neither vendor nor customer
            return response()->json([
                'message' => 'Unauthorized. You can only update your own bookings.',
            ], 403);
        }
        
        // Update the booking status
        $oldStatus = $booking->status;
        $booking->status = $newStatus;
        $booking->save();
        
        return response()->json([
            'message' => 'Booking status updated successfully!',
            'booking' => [
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'old_status' => $oldStatus,
                'new_status' => $booking->status,
                'updated_at' => $booking->updated_at,
            ],
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update booking status',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}