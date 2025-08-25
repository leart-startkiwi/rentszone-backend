<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarType;

class CarTypeController extends Controller
{
    public function carTypesWithCount()
{
    $carTypes = \Botble\CarRentals\Models\CarType::withCount('cars')->get();

    return response()->json([
        'car_types' => $carTypes,
    ]);
}

 public function getCarTypes()
    {
        $types = CarType::select([
            'id',
            'name',
            'image'
        ])
        ->get();

        return response()->json([
            'types' => $types,
        ]);
    }
}