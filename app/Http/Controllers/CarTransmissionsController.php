<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarTransmission;


class CarTransmissionsController extends Controller
{
     public function getCarTransmissions()
    {
        $transmissions = CarTransmission::select([
            'id',
            'name',
        ])
        ->get();

        return response()->json([
            'transmissions' => $transmissions,
        ]);
    }
}
