<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
     public function index(Request $request)
    {
         $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

         return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
        ];
    }
}
