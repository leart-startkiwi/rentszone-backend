<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarAmenity;


class CarAmenitiesController extends Controller
{
     public function getAmenities()
    {
        $amenities = CarAmenity::select([
            'id',
            'name',
            'status'
        ])
        ->get();

        return response()->json([
            'amenities' => $amenities,
        ]);
    }
}
