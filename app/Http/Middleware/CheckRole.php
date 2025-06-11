<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->attributes->get('auth_user');

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan di request.'], 401);
        }

        if ($user->role !== $role) {
            return response()->json(['message' => 'Akses ditolak, bukan role: ' . $role], 403);
        }

        return $next($request);
    }
}