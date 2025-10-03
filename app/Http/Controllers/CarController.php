<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\Car;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Botble\CarRentals\Models\CarView;

use Botble\CarRentals\Http\Requests\CarRequest;
use Botble\CarRentals\Models\CarTag;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Carbon\Carbon;
use Botble\CarRentals\Models\BookingCar;



class CarController extends Controller
{
    /**
     * Helper method to convert image paths to full URLs
     */
    private function getImageUrls($images)
    {
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        
        if (!is_array($images)) {
            return null;
        }
        
        $imageUrls = [];
        foreach ($images as $key => $imagePath) {
            if ($imagePath) {
                // Clean the path - remove escaped slashes
                $cleanPath = str_replace('\/', '/', $imagePath);
                
                // Return just the cleaned path without generating full URL
                $imageUrls[$key] = $cleanPath;
            }
        }
        
        return $imageUrls;
    }

    /**
     * Get the first image URL from images array
     */
    private function getFirstImageUrl($images)
    {
        $imageUrls = $this->getImageUrls($images);
        return $imageUrls ? reset($imageUrls) : null;
    }

public function index(Request $request)
{
    try {
        $query = Car::select([
            'cr_cars.id',
            'cr_cars.name',
            'cr_cars.rental_rate',
            'cr_cars.status',
            'cr_cars.moderation_status',
            'cr_cars.created_at',
            'cr_cars.transmission_id',
            'cr_cars.number_of_seats',
            'cr_cars.vehicle_type_id',
            'cr_cars.images',
            'cr_cars.fuel_type_id'
        ])
        ->leftJoin('cr_car_fuels', 'cr_cars.fuel_type_id', '=', 'cr_car_fuels.id')
        ->addSelect([
            'cr_car_fuels.name as fuel_type_name',
            'cr_car_fuels.icon as fuel_type_icon'
        ]);

        // Multi-value filter for fuel_type_id
        if ($request->has('fuel_type_id')) {
            $fuelTypeIds = explode(',', $request->input('fuel_type_id'));
            $query->whereIn('cr_cars.fuel_type_id', $fuelTypeIds);
        }

        // Multi-value filter for number_of_seats
        if ($request->has('number_of_seats')) {
            $seats = explode(',', $request->input('number_of_seats'));
            $query->whereIn('cr_cars.number_of_seats', $seats);
        }

        // Multi-value filter for transmission_id
        if ($request->has('transmission_id')) {
            $transmissionIds = explode(',', $request->input('transmission_id'));
            $query->whereIn('cr_cars.transmission_id', $transmissionIds);
        }

        // Vehicle Type Filter
        if ($request->has('vehicle_type_id')) {
            $vehicleTypeIds = explode(',', $request->input('vehicle_type_id'));
            $query->whereIn('cr_cars.vehicle_type_id', $vehicleTypeIds);
        }

        // Pickup Address Filter
        if ($request->has('pick_address_id')) {
            $query->where('cr_cars.pick_address_id', $request->input('pick_address_id'));
        }

        // Return Address Filter
        if ($request->has('return_address_id')) {
            $query->where('cr_cars.return_address_id', $request->input('return_address_id'));
        }

        // Vendor Filter
        if ($request->has('vendor_id')) {
            $query->where('cr_cars.vendor_id', $request->input('vendor_id'));
        }

        // Date Range Filter for Availability
        if ($request->has('start_date') || $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            // Require both dates if either is provided
            if (!$startDate || !$endDate) {
                return response()->json([
                    'message' => 'Both start_date and end_date are required when filtering by date range.',
                ], 400);
            }
            
            try {
                $startDate = Carbon::parse($startDate);
                $endDate = Carbon::parse($endDate);
                
                // Validate date range
                if ($startDate->gte($endDate)) {
                    return response()->json([
                        'message' => 'Start date must be before end date.',
                    ], 400);
                }
                
                // Filter out cars that have conflicting bookings in the specified date range
                $query->whereNotExists(function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->select(\DB::raw(1))
                        ->from('cr_booking_cars')
                        ->whereColumn('cr_booking_cars.car_id', 'cr_cars.id')
                        ->where(function ($conflictQuery) use ($startDate, $endDate) {
                            $conflictQuery->where(function ($q) use ($startDate, $endDate) {
                                // Check if requested start date falls within existing booking
                                $q->where('rental_start_date', '<=', $startDate->format('Y-m-d'))
                                  ->where('rental_end_date', '>=', $startDate->format('Y-m-d'));
                            })
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                // Check if requested end date falls within existing booking
                                $q->where('rental_start_date', '<=', $endDate->format('Y-m-d'))
                                  ->where('rental_end_date', '>=', $endDate->format('Y-m-d'));
                            })
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                // Check if requested period completely encompasses existing booking
                                $q->where('rental_start_date', '>=', $startDate->format('Y-m-d'))
                                  ->where('rental_end_date', '<=', $endDate->format('Y-m-d'));
                            });
                        });
                });
                
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Invalid date format. Use YYYY-MM-DD format.',
                ], 400);
            }
        }

        $perPage = $request->input('per_page', 15);
        $cars = $query->orderBy('cr_cars.created_at', 'desc')->paginate($perPage);

        $carsTransformed = collect($cars->items())->map(function ($car) {
            // Get the first image URL
            $firstImageUrl = $this->getFirstImageUrl($car->images);

            // Get average rating for this car
           $averageRating = CarReview::where('car_id', $car->id)
    ->where('status', 'published')
    ->avg('star');

$averageRating = $averageRating
    ? round((float) $averageRating, 1)
    : 0.0;


            return [
                'id' => $car->id,
                'name' => $car->name,
                'rental_rate' => $car->rental_rate,
                'status' => $car->status,
                'moderation_status' => $car->moderation_status,
                'created_at' => $car->created_at,
                'transmission_id' => $car->transmission_id,
                'number_of_seats' => $car->number_of_seats,
                'vehicle_type_id' => $car->vehicle_type_id,
                'image' => $firstImageUrl,
                'fuel' => [
                    'id' => $car->fuel_type_id,
                    'name' => $car->fuel_type_name,
                    'icon' => $car->fuel_type_icon,
                ],
                'rating' => $averageRating,
            ];
        });

        return response()->json([
            'cars' => $carsTransformed,
            'pagination' => [
                'current_page' => $cars->currentPage(),
                'last_page' => $cars->lastPage(),
                'per_page' => $cars->perPage(),
                'total' => $cars->total(),
            ],
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve cars',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function mostViewed()
{
    try {
        // Get the top 5 car IDs with the most total views
        $topCarIds = CarView::selectRaw('car_id, SUM(views) as total_views')
            ->groupBy('car_id')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->pluck('car_id');

        if ($topCarIds->isEmpty()) {
            return response()->json([
                'cars' => [],
                'message' => 'No viewed cars found',
            ]);
        }

        $cars = Car::select([
                'cr_cars.id',
                'cr_cars.name',
                'cr_cars.number_of_seats',
                'cr_cars.rental_rate',
                'cr_cars.images',
                'cr_cars.fuel_type_id'
            ])
            ->leftJoin('cr_car_fuels', 'cr_cars.fuel_type_id', '=', 'cr_car_fuels.id')
            ->addSelect([
                'cr_car_fuels.name as fuel_type_name',
                'cr_car_fuels.icon as fuel_type_icon'
            ])
            ->whereIn('cr_cars.id', $topCarIds)
            ->orderByRaw('FIELD(cr_cars.id, ' . $topCarIds->implode(',') . ')')
            ->get()
            ->map(function ($car) {
                // Get the first image URL
                $firstImageUrl = $this->getFirstImageUrl($car->images);

                                // Get average rating for this car
           $averageRating = CarReview::where('car_id', $car->id)
    ->where('status', 'published')
    ->avg('star');

$averageRating = $averageRating
    ? round((float) $averageRating, 1)
    : 0.0;

                return [
                    'id' => $car->id,
                    'name' => $car->name,
                    'number_of_seats' => $car->number_of_seats,
                    'rental_rate' => $car->rental_rate,
                    'image' => $firstImageUrl,
                    'fuel' => [
                        'id' => $car->fuel_type_id,
                        'name' => $car->fuel_type_name,
                        'icon' => $car->fuel_type_icon,
                    ],
                    'rating' => $averageRating,
                ];
            });

        return response()->json([
            'cars' => $cars,
            'total' => $cars->count(),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve most viewed cars',
            'error' => $e->getMessage(),
        ], 500);
    }
}

 public function filterByType(Request $request)
    {
        $typeIds = $request->input('car_types', []);
        // Accepts either a single ID or an array of IDs

        $query = Car::query();

        if (!empty($typeIds)) {
            $query->whereIn('vehicle_type_id', (array)$typeIds);
        }

        $cars = $query->with([
            'amenities',
            'categories',
            'colors',
            'tags',
            'author',
            'type',
        ])->get();

        return response()->json([
            'cars' => $cars,
        ]);
    }


    public function store(CarRequest $request)
{
    // Check if user is authenticated
    if (!auth('sanctum')->check()) {
        return response()->json([
            'message' => 'Unauthenticated. Please login first.',
        ], 401);
    }

    $user = auth('sanctum')->user();
    $dataCreate = $request->validated();

    if ($request->boolean('is_same_drop_off') && !empty($dataCreate['pick_address_id'])) {
        $dataCreate['return_address_id'] = $dataCreate['pick_address_id'];
    }

    $car = new Car();
    $car->fill($dataCreate);
    
    // Set the vendor_id to the current authenticated user
    $car->vendor_id = $user->id;
    $car->author_id = $user->id;


    // Explicitly set specific fields to ensure they are saved
    if ($request->has('mileage')) {
        $car->mileage = $request->input('mileage');
    }
    if ($request->has('fuel_type_id')) {
        $car->fuel_type_id = $request->input('fuel_type_id');
    }
    if ($request->has('number_of_seats')) {
        $car->number_of_seats = $request->input('number_of_seats');
    }
    if ($request->has('transmission_id')) {
        $car->transmission_id = $request->input('transmission_id');
    }
    if ($request->has('number_of_doors')) {
        $car->number_of_doors = $request->input('number_of_doors');
    }
    if ($request->has('vin')) {
        $car->vin = $request->input('vin');
    }
    if ($request->has('location')) {
        $car->location = $request->input('location');
    }
    if ($request->has('is_used')) {
        $car->is_used = $request->input('is_used');
    }

    $car->images = array_filter($request->input('images', []));
    $car->moderation_status = ModerationStatusEnum::PENDING;
    
    // Handle file uploads for images
    if ($request->hasFile('images')) {
        $uploadedImages = [];
        $files = $request->file('images');
        
        // Ensure $files is always an array
        if (!is_array($files)) {
            $files = [$files];
        }
        
        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                // Create directory structure: customers/{user_id}/
                $directory = 'customers/' . $user->id;
                
                // Sanitize filename - remove special characters and spaces
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $basename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Clean filename: remove spaces, special chars, keep only alphanumeric, dots, hyphens, underscores
                $cleanBasename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
                $cleanFilename = time() . '_' . ($index + 1) . '_' . $cleanBasename . '.' . $extension;
                
                // Store file in public disk
                $path = $file->storeAs($directory, $cleanFilename, 'public');
                
                // Add to uploaded images array with numbered keys (starting from 1)
                // Store path without escaped slashes
                $uploadedImages[($index + 1)] = $path;
            }
        }
        
        // If we have uploaded images, use them instead of the input array
        if (!empty($uploadedImages)) {
            $car->images = $uploadedImages;
        }
    }
    $car->save();

    // Handle tags (comma separated string)
    $tags = $request->input('tags');
    $tags = $tags ? explode(',', $tags) : [];
    $tagIds = CarTag::query()->wherePublished()->whereIn('id', $tags)->pluck('id')->all();
    if ($tagIds) {
        $car->tags()->sync($tagIds);
    }

    // Handle categories
    $car->categories()->sync($request->input('categories', []));

    // Handle colors (comma separated string)
    $colors = $request->input('colors');
    $colors = $colors ? explode(',', $colors) : [];
    if ($colors) {
        $car->colors()->sync($colors);
    }

    // Handle amenities
    $car->amenities()->sync($request->input('amenities', []));

    return response()->json([
        'message' => 'Car created successfully!',
        'car' => $car->load([
            'amenities',
            'categories',
            'colors',
            'tags',
            'author',
            'type',
        ]),
    ], 201);
}

public function patch($id, Request $request)
{
    try {
        // Force Laravel to parse form-data for PATCH requests
        if ($request->isMethod('patch') && $request->hasHeader('Content-Type') && 
            strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            
            // Parse the raw input for multipart/form-data
            $input = $request->all();
            $request->merge($input);
        }
        
        // Check if car exists
        $car = Car::withoutGlobalScopes()->findOrFail($id);
        
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Check if the authenticated user is the owner of the car
        if ($car->vendor_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized. You can only update cars that you created.',
            ], 403);
        }
        
        // Define allowed fields for partial update
        $allowedFields = [
            'name', 'description', 'vehicle_type_id', 'make_id', 'year', 
            'rental_rate', 'status', 'pick_address_id', 'return_address_id',
            'is_for_sale', 'sale_price', 'condition', 'ownership_history',
            'insurance_info', 'warranty_information', 'sale_status',
            'external_booking_url', 'images', 'mileage', 'fuel_type_id',
            'number_of_seats', 'transmission_id', 'number_of_doors', 'vin',
            'location', 'is_used'
        ];
        
        // Only update fields that are present in the request
        $dataToUpdate = $request->only($allowedFields);
        
        if ($request->has('is_same_drop_off') && $request->boolean('is_same_drop_off') && $request->has('pick_address_id')) {
            $dataToUpdate['return_address_id'] = $request->input('pick_address_id');
        }
        
        // Update only the provided fields
        $car->fill($dataToUpdate);
        
        if ($request->has('images')) {
            $car->images = array_filter($request->input('images', []));
        }
        
        // Handle file uploads for images in updates
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            $files = $request->file('images');
            
            // Ensure $files is always an array
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    // Create directory structure: customers/{user_id}/
                    $directory = 'customers/' . $user->id;
                    
                    // Sanitize filename - remove special characters and spaces
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $basename = pathinfo($originalName, PATHINFO_FILENAME);
                    
                    // Clean filename: remove spaces, special chars, keep only alphanumeric, dots, hyphens, underscores
                    $cleanBasename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
                    $cleanFilename = time() . '_' . ($index + 1) . '_' . $cleanBasename . '.' . $extension;
                    
                    // Store file in public disk
                    $path = $file->storeAs($directory, $cleanFilename, 'public');
                    
                    // Add to uploaded images array with numbered keys (starting from 1)
                    // Store path without escaped slashes
                    $uploadedImages[($index + 1)] = $path;
                }
            }
            
            // If we have uploaded images, use them instead of the input array
            if (!empty($uploadedImages)) {
                $car->images = $uploadedImages;
            }
        }
        
        $car->save();
        
        // Handle relationships only if they are provided
        if ($request->has('tags')) {
            $tags = $request->input('tags');
            $tags = $tags ? explode(',', $tags) : [];
            $tagIds = CarTag::query()->wherePublished()->whereIn('id', $tags)->pluck('id')->all();
            $car->tags()->sync($tagIds);
        }
        
        if ($request->has('categories')) {
            $car->categories()->sync($request->input('categories', []));
        }
        
        if ($request->has('colors')) {
            $colors = $request->input('colors');
            $colors = $colors ? explode(',', $colors) : [];
            $car->colors()->sync($colors);
        }
        
        if ($request->has('amenities')) {
            $car->amenities()->sync($request->input('amenities', []));
        }
        
        return response()->json([
            'message' => 'Car updated successfully!',
            'car' => $car->load([
                'amenities',
                'categories',
                'colors',
                'tags',
                'author',
                'type',
            ]),
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update car',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function incrementView($id)
    {
        try {
            // Check if car exists
            $car = Car::findOrFail($id);
            
            $today = now()->toDateString();
            
            // Find existing view record for today
            $carView = CarView::where('car_id', $id)
                ->where('date', $today)
                ->first();
            
            if ($carView) {
                // If record exists, increment views by 1
                $carView->increment('views');
            } else {
                // If no record exists, create new one with 1 view
                CarView::create([
                    'car_id' => $id,
                    'views' => 1,
                    'date' => $today,
                ]);
            }
            
            // Get total views for this car
            $totalViews = CarView::where('car_id', $id)->sum('views');
            
            return response()->json([
                'message' => 'View count updated successfully',
                'car_id' => $id,
                'views_today' => $carView ? $carView->views : 1,
                'total_views' => $totalViews,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update view count',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteCar($id, Request $request)
{
    try {
        // Check if car exists
        $car = Car::withoutGlobalScopes()->findOrFail($id);
        
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Check if the authenticated user is the owner of the car
        if ($car->vendor_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized. You can only delete cars that you created.',
            ], 403);
        }
        
        // Store car info before deletion for response
        $carInfo = [
            'id' => $car->id,
            'name' => $car->name,
        ];
        
        // Delete the car
        $car->delete();
        
        return response()->json([
            'message' => 'Car deleted successfully!',
            'deleted_car' => $carInfo,
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete car',
            'error' => $e->getMessage(),
        ], 500);
    }

}

public function myCars(Request $request)
{
    try {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        $cars = Car::withoutGlobalScopes()
            ->select([
                'cr_cars.id',
                'cr_cars.name',
                'cr_cars.rental_rate',
                'cr_cars.status',
                'cr_cars.moderation_status',
                'cr_cars.created_at',
                'cr_cars.updated_at',
                'cr_cars.number_of_seats',
                'cr_cars.images',
                'cr_cars.fuel_type_id'
            ])
            ->leftJoin('cr_car_fuels', 'cr_cars.fuel_type_id', '=', 'cr_car_fuels.id')
            ->addSelect([
                'cr_car_fuels.name as fuel_type_name',
                'cr_car_fuels.icon as fuel_type_icon'
            ])
            ->where('vendor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($car) {
                // Check if car is currently rented - car is rented if today falls within any booking period
                $today = Carbon::today()->format('Y-m-d');
                
                $isCurrentlyRented = BookingCar::where('car_id', $car->id)
                    ->where('rental_start_date', '<=', $today)
                    ->where('rental_end_date', '>=', $today)
                    ->exists();

                // Handle images like in index function
                $images = is_string($car->images) ? json_decode($car->images, true) : $car->images;
                $firstImage = null;
                if (is_array($images) && count($images) > 0) {
                    $firstImage = reset($images);
                }
                
                return [
                    'id' => $car->id,
                    'name' => $car->name,
                    'rental_rate' => $car->rental_rate,
                    'status' => $car->status,
                    'moderation_status' => $car->moderation_status,
                    'created_at' => $car->created_at,
                    'updated_at' => $car->updated_at,
                    'number_of_seats' => $car->number_of_seats,
                    'image' => $firstImage,
                    'fuel' => [
                        'id' => $car->fuel_type_id,
                        'name' => $car->fuel_type_name,
                        'icon' => $car->fuel_type_icon,
                    ],
                    'is_available' => !$isCurrentlyRented,
                    'is_rented' => $isCurrentlyRented,
                ];
            });
        
        return response()->json([
            'message' => 'Cars retrieved successfully!',
            'cars' => $cars,
            'total_cars' => $cars->count(),
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve cars',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getCarDetails($id)
{
    try {
        // Check if user is authenticated to determine if they are the vendor
        $user = auth('sanctum')->user();
        $isVendorRequest = false;
        
        // First check if the authenticated user is the vendor of this car
        if ($user) {
            $vendorCheck = Car::withoutGlobalScopes()
                ->where('id', $id)
                ->where('vendor_id', $user->id)
                ->exists();
            $isVendorRequest = $vendorCheck;
        }
        
        // If user is the vendor, bypass global scopes to see their own car regardless of moderation status
        $carQuery = $isVendorRequest ? Car::withoutGlobalScopes() : Car::query();
        
        $car = $carQuery->select([
                'cr_cars.id',
                'cr_cars.name',
                'cr_cars.rental_rate',
                'cr_cars.status',
                'cr_cars.moderation_status',
                'cr_cars.created_at',
                'cr_cars.transmission_id',
                'cr_cars.number_of_seats',
                'cr_cars.vehicle_type_id',
                'cr_cars.images',
                'cr_cars.fuel_type_id',
                'cr_cars.vendor_id',
                'cr_cars.description',
                'cr_cars.license_plate',
                'cr_cars.year',
                'cr_cars.mileage',
                'cr_cars.insurance_info',
                'cr_cars.is_for_sale',
                'cr_cars.reject_reason',
                'cr_cars.make_id',
                'cr_cars.pick_address_id',
                'cr_cars.return_address_id',
                'cr_cars.content'
            ])
            ->leftJoin('cr_car_fuels', 'cr_cars.fuel_type_id', '=', 'cr_car_fuels.id')
            ->leftJoin('cr_customers', 'cr_cars.vendor_id', '=', 'cr_customers.id')
            ->addSelect([
                'cr_car_fuels.name as fuel_type_name',
                'cr_car_fuels.icon as fuel_type_icon',
                'cr_customers.id as vendor_id',
                'cr_customers.name as vendor_name',
                'cr_customers.email as vendor_email',
                'cr_customers.avatar as vendor_avatar',
                'cr_customers.created_at as vendor_created_at',
                'cr_customers.phone as vendor_phone'
            ])
            ->with(['amenities', 'colors', 'tags'])
            ->where('cr_cars.id', $id)
            ->first();

        if (!$car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }


        // Only consider approved cars for vendor stats
        $approvedCarIds = Car::withoutGlobalScopes()
            ->where('vendor_id', $car->vendor_id)
            ->where('moderation_status', 'approved')
            ->pluck('id');
        $vendorCarCount = $approvedCarIds->count();

        // Get vendor's overall rating (only approved cars)
        $vendorRating = CarReview::whereIn('car_id', $approvedCarIds)
            ->where('status', 'published')
            ->avg('star');
        $vendorRating = $vendorRating
            ? number_format($vendorRating, 1, '.', '')
            : number_format(0, 1, '.', '');

        // Get total number of reviews for this vendor (only approved cars)
        $vendorReviewCount = CarReview::whereIn('car_id', $approvedCarIds)
            ->where('status', 'published')
            ->count();

        // Get full image URLs
        $imageUrls = $this->getImageUrls($car->images);

        // Transform amenities
        $amenities = $car->amenities->map(function ($amenity) {
            return [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'icon' => $amenity->icon ?? null,
            ];
        });

        // Transform colors
        $colors = $car->colors->map(function ($color) {
            return [
                'id' => $color->id,
                'name' => $color->name,
                'color_code' => $color->color ?? null,
            ];
        });

        // Transform tags
        $tags = $car->tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        });

        return response()->json([
            'id' => $car->id,
            'name' => $car->name,
            'rental_rate' => $car->rental_rate,
            'status' => $car->status,
            'moderation_status' => $car->moderation_status,
            'created_at' => $car->created_at,
            'transmission_id' => $car->transmission_id,
            'number_of_seats' => $car->number_of_seats,
            'vehicle_type_id' => $car->vehicle_type_id,
            'images' => $imageUrls,
            'fuel' => [
                'id' => $car->fuel_type_id,
                'name' => $car->fuel_type_name,
                'icon' => $car->fuel_type_icon,
            ],
            'vendor' => [
                'id' => $car->vendor_id,
                'name' => $car->vendor_name,
                'email' => $car->vendor_email,
                'avatar' => $car->vendor_avatar,
                'created_at' => $car->vendor_created_at,
                'phone' => $car->vendor_phone,
                'total_cars' => $vendorCarCount,
                'rating' => $vendorRating,
                'total_reviews' => $vendorReviewCount,
            ],
            'amenities' => $amenities,
            'colors' => $colors,
            'tags' => $tags,
            'description' => $car->description,
            'license_plate' => $car->license_plate,
            'year' => $car->year,
            'mileage' => $car->mileage,
            'insurance_info' => $car->insurance_info,
            'is_for_sale' => $car->is_for_sale,
            'reject_reason' => $car->reject_reason,
            'make_id' => $car->make_id,
            'pick_address_id' => $car->pick_address_id,
            'return_address_id' => $car->return_address_id,
            'content' => $car->content
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve car details',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getMyCarDetails($id)
{
    try {
        // Check if user is authenticated
        if (!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }
        
        $user = auth('sanctum')->user();
        
        // Use withoutGlobalScopes to get car regardless of moderation status
        $car = Car::withoutGlobalScopes()
            ->select([
                'cr_cars.id',
                'cr_cars.name',
                'cr_cars.rental_rate',
                'cr_cars.status',
                'cr_cars.moderation_status',
                'cr_cars.created_at',
                'cr_cars.transmission_id',
                'cr_cars.number_of_seats',
                'cr_cars.vehicle_type_id',
                'cr_cars.images',
                'cr_cars.fuel_type_id',
                'cr_cars.vendor_id',
                'cr_cars.description',
                'cr_cars.license_plate',
                'cr_cars.year',
                'cr_cars.mileage',
                'cr_cars.insurance_info',
                'cr_cars.is_for_sale',
                'cr_cars.reject_reason',
                'cr_cars.make_id',
                'cr_cars.pick_address_id',
                'cr_cars.return_address_id'
            ])
            ->leftJoin('cr_car_fuels', 'cr_cars.fuel_type_id', '=', 'cr_car_fuels.id')
            ->addSelect([
                'cr_car_fuels.name as fuel_type_name',
                'cr_car_fuels.icon as fuel_type_icon'
            ])
            ->with(['amenities', 'colors', 'tags'])
            ->where('cr_cars.id', $id)
            ->where('cr_cars.vendor_id', $user->id)
            ->first();

        if (!$car) {
            return response()->json([
                'message' => 'Car not found or you are not the owner of this car.',
            ], 404);
        }

        // Get full image URLs
        $imageUrls = $this->getImageUrls($car->images);

        // Transform amenities
        $amenities = $car->amenities->map(function ($amenity) {
            return [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'icon' => $amenity->icon ?? null,
            ];
        });

        // Transform colors
        $colors = $car->colors->map(function ($color) {
            return [
                'id' => $color->id,
                'name' => $color->name,
                'color_code' => $color->color ?? null,
            ];
        });

        // Transform tags
        $tags = $car->tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        });

        return response()->json([
            'id' => $car->id,
            'name' => $car->name,
            'rental_rate' => $car->rental_rate,
            'status' => $car->status,
            'moderation_status' => $car->moderation_status,
            'created_at' => $car->created_at,
            'transmission_id' => $car->transmission_id,
            'number_of_seats' => $car->number_of_seats,
            'vehicle_type_id' => $car->vehicle_type_id,
            'images' => $imageUrls,
            'fuel' => [
                'id' => $car->fuel_type_id,
                'name' => $car->fuel_type_name,
                'icon' => $car->fuel_type_icon,
            ],
            'amenities' => $amenities,
            'colors' => $colors,
            'tags' => $tags,
            'description' => $car->description,
            'license_plate' => $car->license_plate,
            'year' => $car->year,
            'mileage' => $car->mileage,
            'insurance_info' => $car->insurance_info,
            'is_for_sale' => $car->is_for_sale,
            'reject_reason' => $car->reject_reason,
            'make_id' => $car->make_id,
            'pick_address_id' => $car->pick_address_id,
            'return_address_id' => $car->return_address_id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve car details',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}