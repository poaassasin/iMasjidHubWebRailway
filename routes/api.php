<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\JadwalShalatController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\PermintaanInventarisController;
use App\Http\Controllers\KeuanganController;
use App\Http\Middleware\CheckRole;

// === AUTH ROUTES ===
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/admin-only', [AuthController::class, 'adminOnly']);

// === GOOGLE AUTH ===
Route::get('/auth/redirect/google', [GoogleController::class, 'redirect']);
Route::get('/auth/callback/google', [GoogleController::class, 'callback']);

// === ADMIN DASHBOARD (OPTIONAL PROTECTED) ===
Route::get('/dashboard-admin', function () {
    return response()->json(['message' => 'Selamat datang admin']);
})->middleware(CheckRole::class . ':admin');

// === INVENTARIS ===
Route::get('/inventaris', [InventarisController::class, 'index']);
Route::post('/inventaris', [InventarisController::class, 'store']);
Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy']);

// === JADWAL SHALAT ===
Route::get('/jadwal-shalat', [JadwalShalatController::class, 'index']);
Route::get('/jadwal-shalat/hari-ini', [JadwalShalatController::class, 'today']);
Route::post('/jadwal-shalat', [JadwalShalatController::class, 'store']);
Route::put('/jadwal-shalat/{id}', [JadwalShalatController::class, 'update']);
Route::delete('/jadwal-shalat/{id}', [JadwalShalatController::class, 'destroy']);

// === ACARA ===
Route::get('/acara', [AcaraController::class, 'index']);
Route::get('/acara/upcoming', [AcaraController::class, 'upcoming']);
Route::post('/acara', [AcaraController::class, 'store']);
Route::put('/acara/{id}', [AcaraController::class, 'update']);
Route::delete('/acara/{id}', [AcaraController::class, 'destroy']);

// === PERMINTAAN INVENTARIS ===
Route::apiResource('permintaan-inventaris', PermintaanInventarisController::class);

// === KEUANGAN ===
Route::get('/keuangan', [KeuanganController::class, 'index']);
Route::post('/keuangan', [KeuanganController::class, 'store']);
Route::get('/keuangan/{id}', [KeuanganController::class, 'show']);
Route::put('/keuangan/{id}', [KeuanganController::class, 'update']);
Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy']);
Route::get('/keuangan/rekap', [KeuanganController::class, 'rekap']);
