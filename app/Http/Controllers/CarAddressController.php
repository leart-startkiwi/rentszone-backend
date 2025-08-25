<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botble\CarRentals\Models\CarAddress;

class CarAddressController extends Controller
{
    public function getAddresses()
    {
        $addresses = CarAddress::select([
            'id',
            'detail_address',
            'city_id',
            'state_id',
            'country_id',
            'status',
            'created_at',
            'updated_at',
        ])
        ->where('status', 'published') 
        ->get();

        return response()->json([
            'addresses' => $addresses,
        ]);
    }
}