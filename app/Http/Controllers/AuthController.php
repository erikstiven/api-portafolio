<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            // Fuerza el guard JWT
            if (! $token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'No se pudo crear el token'], 500);
        }

        return response()->json([
            'token'       => $token,
            'token_type'  => 'Bearer',
            // Si configuraste ttl en config/jwt.php, puedes exponerlo:
            // 'expires_in'  => Auth::guard('api')->factory()->getTTL() * 60,
            'user'        => Auth::guard('api')->user(),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Logout OK']);
    }

    public function me(): JsonResponse
    {
        return response()->json(Auth::guard('api')->user());
    }
}
