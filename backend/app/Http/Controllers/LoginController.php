<?php

namespace App\Http\Controllers;
use App\Notifications\LoginNeedsVerification;


use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function submit(Request $request) {
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if (!$user) {
            return response()->json(['message' => 'Could not process with that phone number.'], 401);
        }

        $user ->notify(new LoginNeedsVerification());

        return response()->json(['message' => 'Text message notification sent.']);
    }




    }
}
