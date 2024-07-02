<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $request -> validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required'
        ]);
    }
    public function show(Request $request, Trip $trip)
    {
        if ($trip->user->id === $request->user()->id){
            return $trip;

        }
        if ($trip->driver->id === $request->driver()->id){
            return $trip;

        }

        return response () ->json(['message' => 'cannot find this trip'], 404);
        
    }
}
