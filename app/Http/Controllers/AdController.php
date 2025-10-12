<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Ad;

class AdController extends Controller
{
    /**
     * Get all active ads with weighted randomization
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $ads = Ad::active()
                ->orderByWeight()
                ->get()
                ->map(function ($ad) {
                    return [
                        'id' => $ad->id,
                        'image_url' => $ad->image_url,
                        'link_url' => $ad->link_url,
                        'title' => $ad->title,
                        'is_active' => $ad->is_active,
                        'price_per_month' => $ad->price_per_month,
                        'created_at' => $ad->created_at,
                        'updated_at' => $ad->updated_at,
                    ];
                });

            return response()->json([
                'message' => 'Ads retrieved successfully!',
                'ads' => $ads,
                'total' => $ads->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve ads',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new ad
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'link_url' => 'required|url|max:255',
                'title' => 'required|string|max:100',
                'price_per_month' => 'required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ad = new Ad();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                if ($file->isValid()) {
                    // Create directory structure: ads/
                    $directory = 'ads';
                    
                    // Sanitize filename - remove special characters and spaces
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $basename = pathinfo($originalName, PATHINFO_FILENAME);
                    
                    // Clean filename: remove spaces, special chars, keep only alphanumeric, dots, hyphens, underscores
                    $cleanBasename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
                    $cleanFilename = 'ad_' . time() . '_' . $cleanBasename . '.' . $extension;
                    
                    // Store file in public disk
                    $path = $file->storeAs($directory, $cleanFilename, 'public');
                    
                    // Store path without /storage/ prefix (exactly like CarController)
                    $ad->image_url = $path;
                } else {
                    return response()->json([
                        'message' => 'Invalid image file',
                    ], 422);
                }
            }

            // Set other fields
            $ad->link_url = $request->input('link_url');
            $ad->title = $request->input('title');
            $ad->price_per_month = $request->input('price_per_month');
            $ad->is_active = $request->input('is_active', true);

            $ad->save();

            return response()->json([
                'message' => 'Ad created successfully!',
                'ad' => [
                    'id' => $ad->id,
                    'image_url' => $ad->image_url,
                    'link_url' => $ad->link_url,
                    'title' => $ad->title,
                    'is_active' => $ad->is_active,
                    'price_per_month' => $ad->price_per_month,
                    'created_at' => $ad->created_at,
                    'updated_at' => $ad->updated_at,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create ad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing ad
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $ad = Ad::findOrFail($id);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5120',
                'link_url' => 'sometimes|required|url|max:255',
                'title' => 'sometimes|required|string|max:100',
                'price_per_month' => 'sometimes|required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                if ($file->isValid()) {
                    // Delete old image file if exists
                    if ($ad->image_url && Storage::disk('public')->exists($ad->image_url)) {
                        Storage::disk('public')->delete($ad->image_url);
                    }

                    // Create directory structure: ads/
                    $directory = 'ads';
                    
                    // Sanitize filename - remove special characters and spaces
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $basename = pathinfo($originalName, PATHINFO_FILENAME);
                    
                    // Clean filename: remove spaces, special chars, keep only alphanumeric, dots, hyphens, underscores
                    $cleanBasename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
                    $cleanFilename = 'ad_' . time() . '_' . $cleanBasename . '.' . $extension;
                    
                    // Store file in public disk
                    $path = $file->storeAs($directory, $cleanFilename, 'public');
                    
                    // Store path without /storage/ prefix (exactly like CarController)
                    $ad->image_url = $path;
                } else {
                    return response()->json([
                        'message' => 'Invalid image file',
                    ], 422);
                }
            }

            // Update other fields only if provided
            if ($request->has('link_url')) {
                $ad->link_url = $request->input('link_url');
            }

            if ($request->has('title')) {
                $ad->title = $request->input('title');
            }

            if ($request->has('price_per_month')) {
                $ad->price_per_month = $request->input('price_per_month');
            }

            if ($request->has('is_active')) {
                $ad->is_active = $request->input('is_active');
            }

            $ad->save();

            return response()->json([
                'message' => 'Ad updated successfully!',
                'ad' => [
                    'id' => $ad->id,
                    'image_url' => $ad->image_url,
                    'link_url' => $ad->link_url,
                    'title' => $ad->title,
                    'is_active' => $ad->is_active,
                    'price_per_month' => $ad->price_per_month,
                    'created_at' => $ad->created_at,
                    'updated_at' => $ad->updated_at,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update ad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific ad by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $ad = Ad::findOrFail($id);

            return response()->json([
                'message' => 'Ad retrieved successfully!',
                'ad' => [
                    'id' => $ad->id,
                    'image_url' => $ad->image_url,
                    'link_url' => $ad->link_url,
                    'title' => $ad->title,
                    'is_active' => $ad->is_active,
                    'price_per_month' => $ad->price_per_month,
                    'created_at' => $ad->created_at,
                    'updated_at' => $ad->updated_at,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ad not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Delete an ad
     */
    public function destroy($id): JsonResponse
    {
        try {
            $ad = Ad::findOrFail($id);

            // Delete the image file if exists
            if ($ad->image_url && Storage::disk('public')->exists($ad->image_url)) {
                Storage::disk('public')->delete($ad->image_url);
            }

            $adInfo = [
                'id' => $ad->id,
                'title' => $ad->title,
            ];

            $ad->delete();

            return response()->json([
                'message' => 'Ad deleted successfully!',
                'deleted_ad' => $adInfo,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete ad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
