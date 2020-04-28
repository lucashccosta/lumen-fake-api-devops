<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    const RULES = [
        'email' => 'required|email',
        'password' => 'required|min:6'
    ];

    public function auth(Request $request) {
        $this->validate($request, self::RULES);
        
        $user = User::where('email', $request->input('email'))->firstOrFail();
        if(Hash::check($request->input('password'), $user->password)) {
            $random = Str::random(68);
            $token = base64_encode("{$user->id}#{$random}");
            app('redis')->set("User::{$user->id}", $token);

            return response()->json([
                'data' => [
                    'token' => $token
                ]
            ], 200);
        }
        else {
            return response()->json([
                'error' => [
                    'msg' => 'Unauthorized'
                ]
            ], 401);
        }
    }
}
