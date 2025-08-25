<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarMake;


class CarMakesController extends Controller
{
    public function getCarMakes()
    {
        $makes = CarMake::select([
            'id',
            'name',
            'logo',
            'status',
            'created_at',
        ])
        ->get();

        return response()->json([
            'makes' => $makes,
        ]);
    }
}
