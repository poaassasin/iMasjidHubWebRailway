<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\JadwalShalatController;
use App\Http\Controllers\AcaraController;

// Route API
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/admin-only', [AuthController::class, 'adminOnly']);
Route::get('/auth/redirect/google', [GoogleController::class, 'redirect']);
Route::get('/auth/callback/google', [GoogleController::class, 'callback']);
Route::middleware([CheckRole::class . ':admin'])->group(function () {
Route::get('/dashboard-admin', function () {
    return response()->json(['message' => 'Selamat datang admin']);
});
Route::get('/inventaris', [InventarisController::class, 'index']);     // Ambil semua data
Route::post('/inventaris', [InventarisController::class, 'store']);    // Tambah data
Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy']); // Hapus data
Route::get('/jadwal-shalat', [JadwalShalatController::class, 'index']);
Route::get('/jadwal-shalat/hari-ini', [JadwalShalatController::class, 'today']);
Route::post('/jadwal-shalat', [JadwalShalatController::class, 'store']);
Route::put('/jadwal-shalat/{id}', [JadwalShalatController::class, 'update']);
Route::delete('/jadwal-shalat/{id}', [JadwalShalatController::class, 'destroy']);
Route::get('/acara', [AcaraController::class, 'index']); // semua acara
Route::get('/acara/upcoming', [AcaraController::class, 'upcoming']); // acara terdekat
Route::post('/acara', [AcaraController::class, 'store']); // tambah acara
Route::put('/acara/{id}', [AcaraController::class, 'update']); // update
Route::delete('/acara/{id}', [AcaraController::class, 'destroy']); // hapus
});