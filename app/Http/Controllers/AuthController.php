<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function getAllUsers(Request $request)
{
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();

        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Hanya admin yang dapat melihat data user.'], 403);
    }

        $users = User::select('id', 'name', 'email', 'role', 'created_at')->get();

            return response()->json([
            'message' => 'Data user berhasil diambil.',
            'data' => $users
    ]);
}

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user,pengurus',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => $request->role,
            'api_token' => Str::random(60), 
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user->makeHidden(['password', 'remember_token']),
            'token'   => $user->api_token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        // --- INI ADALAH JEMBATANNYA ---
        // Membuat session otentikasi untuk web setelah validasi berhasil.
        Auth::login($user);

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        return response()->json(['message' => 'Login berhasil. Mengarahkan...']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    
    public function adminOnly(Request $request)
    {
        $token = $request->bearerToken();

        $user = User::where('api_token', $token)->first();

        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Hanya admin yang bisa mengakses.'], 403);
        }

        return response()->json(['message' => 'Akses berhasil, selamat datang admin!', 'user' => $user]);
    }
}
