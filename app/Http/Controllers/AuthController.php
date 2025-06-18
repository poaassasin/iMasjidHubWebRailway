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
        // 1. Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek apakah user ada dan passwordnya cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Jika kredensial tidak cocok, kembalikan error 401
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        // 4. Jika berhasil, ambil 'api_token' yang SUDAH ADA di database
        $token = $user->api_token;

        // Untuk keamanan, buat token baru setiap kali login berhasil
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        // 5. Kembalikan response sukses yang berisi token
        return response()->json([
            'message' => 'Login Berhasil',
            'user'    => $user->makeHidden(['password', 'remember_token']),
            'token'   => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Dapatkan user yang sedang login berdasarkan token yang dikirim
        $user = $request->auth_user; // Ini tergantung middleware 'AuthToken' Anda

        if ($user) {
            // Buat token lama tidak valid dengan menimpa-nya dengan yang baru.
            $user->api_token = Str::random(60);
            $user->save();
        }

        return response()->json(['message' => 'Logout berhasil']);
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
