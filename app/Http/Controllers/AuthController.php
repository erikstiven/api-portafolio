<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $cred = $req->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (!Auth::attempt($cred)) {
            return response()->json(['message'=>'Credenciales incorrectas'], 401);
        }

        $user = $req->user();
        $token = $user->createToken('panel')->plainTextToken;
        return response()->json(['token'=>$token]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()?->delete();
        return response()->json(['message'=>'ok']);
    }
}
