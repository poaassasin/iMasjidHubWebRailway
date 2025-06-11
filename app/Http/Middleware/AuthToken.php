<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        // Simpan user yang login ke dalam atribut request
        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}