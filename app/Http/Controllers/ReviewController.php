<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarReview;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'star' => 'required|integer|min:1|max:5',
                'car_id' => 'required|integer|exists:cr_cars,id',
            ]);

            // Get the authenticated customer
            $customer = $request->user();

            // Check if customer already reviewed this car
            $existingReview = CarReview::where('customer_id', $customer->id)
                                     ->where('car_id', $validated['car_id'])
                                     ->first();

            if ($existingReview) {
                return response()->json([
                    'message' => 'You have already reviewed this car.',
                ], 409);
            }

            // Create the review
            $review = CarReview::create([
                'content' => $validated['content'],
                'star' => $validated['star'],
                'customer_id' => $customer->id,
                'car_id' => $validated['car_id'],
                'status' => 'published',
            ]);

            return response()->json([
                'message' => 'Review created successfully!',
                'review' => [
                    'id' => $review->id,
                    'content' => $review->content,
                    'star' => $review->star,
                    'customer_id' => $review->customer_id,
                    'car_id' => $review->car_id,
                    'created_at' => $review->created_at,
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function getCarReviews($carId): JsonResponse
{
    try {
        $reviews = CarReview::select([
                'cr_car_reviews.id',
                'cr_car_reviews.content',
                'cr_car_reviews.star',
                'cr_car_reviews.created_at',
                'cr_car_reviews.customer_id'
            ])
            ->leftJoin('cr_customers', 'cr_car_reviews.customer_id', '=', 'cr_customers.id')
            ->addSelect([
                'cr_customers.id as customer_id',
                'cr_customers.name as customer_name',
                'cr_customers.avatar as customer_avatar'
            ])
            ->where('cr_car_reviews.car_id', $carId)
            ->where('cr_car_reviews.status', 'published')
            ->orderBy('cr_car_reviews.created_at', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'content' => $review->content,
                    'star' => $review->star,
                    'created_at' => $review->created_at,
                    'customer' => [
                        'id' => $review->customer_id,
                        'name' => $review->customer_name,
                        'avatar' => $review->customer_avatar,
                    ],
                ];
            });

        return response()->json([
            'reviews' => $reviews,
            'total' => $reviews->count(),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve reviews',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}