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
    public function verify(Request $request)
    {
        // validate the incoming request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        // find the user
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();

        // is the code provided the same one saved?
        // if so, return back an auth token
        if ($user) {
            $user->update([
                'login_code' => null
            ]);
            
            return $user->createToken($request->login_code)->plainTextToken;
        }

        return response()->json(['message' => 'Invalid verification code.'], 401);




    }
}
