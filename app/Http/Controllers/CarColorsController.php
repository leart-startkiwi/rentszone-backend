<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarColor;


class CarColorsController extends Controller
{
     public function getCarColors()
    {
        $colors = CarColor::select([
            'id',
            'name',
            'status'
        ])
        ->get();

        return response()->json([
            'colors' => $colors,
        ]);
    }
}
