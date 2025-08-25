<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarFuel;


class CarFuelTypesController extends Controller
{
    public function getCarFuelTypes()
    {
        $fuelTypes = CarFuel::select([
            'id',
            'name',
            'status'
        ])
        ->get();

        return response()->json([
            'fuelTypes' => $fuelTypes,
        ]);
    }
}
