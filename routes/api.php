<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\InventarisLogController;
use App\Http\Controllers\PenyumbangInventarisController;
use App\Http\Controllers\JadwalShalatController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\PermintaanInventarisController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\RencanaAnggaranController;
use App\Http\Middleware\AuthToken;
use App\Http\Middleware\CheckRole;


// === AUTH ROUTES ===
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'requestResetPassword']);
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::get('/user-profile', function (Request $request) {
    return response()->json([
        'message' => 'Berhasil login',
        'user' => $request->auth_user,
    ]);
})->middleware(AuthToken::class);

// === GOOGLE AUTH ===
Route::get('/auth/redirect/google', [GoogleController::class, 'redirect']);
Route::get('/auth/callback/google', [GoogleController::class, 'callback']);

// === DASHBOARD ADMIN ===
Route::middleware([AuthToken::class, CheckRole::class . ':admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'overview']);
 
    // === KEGIATAN ===
    Route::get('/kegiatan', [KegiatanController::class, 'index']);
    Route::post('/kegiatan', [KegiatanController::class, 'store']);
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update']);
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy']);

    // === INVENTARIS ===
    Route::get('/inventaris', [InventarisController::class, 'index']);
    Route::get('/kategori-inventaris', [InventarisController::class, 'kategoriList']);
    Route::post('/inventaris', [InventarisController::class, 'store']);
    Route::put('/inventaris/{id}', [InventarisController::class, 'update']);
    Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy']);

    // === INVENTARIS LOG ===
    Route::get('/inventaris-log', [InventarisLogController::class, 'index']);
    Route::post('/inventaris-log', [InventarisLogController::class, 'store']);
    Route::get('/inventaris-log/{id}', [InventarisLogController::class, 'show']);
    Route::put('/inventaris-log/{id}', [InventarisLogController::class, 'update']);
    Route::delete('/inventaris-log/{id}', [InventarisLogController::class, 'destroy']);

    // === PENYUMBANG INVENTARIS ===
    Route::get('/penyumbang-inventaris', [PenyumbangInventarisController::class, 'index']);
    Route::post('/penyumbang-inventaris', [PenyumbangInventarisController::class, 'store']);
    Route::put('/penyumbang-inventaris/{id}', [PenyumbangInventarisController::class, 'update']);
    Route::delete('/penyumbang-inventaris/{id}', [PenyumbangInventarisController::class, 'destroy']);

    // === JADWAL SHOLAT ===
    Route::get('/jadwal-sholat', [JadwalShalatController::class, 'index']);
    Route::put('/jadwal-sholat/{id}', [JadwalShalatController::class, 'update']);

    // === ACARA ===
    Route::get('/acara', [AcaraController::class, 'index']);
    Route::get('/acara/upcoming', [AcaraController::class, 'upcoming']);
    Route::post('/acara', [AcaraController::class, 'store']);
    Route::put('/acara/{id}', [AcaraController::class, 'update']);
    Route::delete('/acara/{id}', [AcaraController::class, 'destroy']);

    // === PERMINTAAN INVENTARIS ===
    Route::get('/permintaan-inventaris', [PermintaanInventarisController::class, 'index']);
    Route::post('/permintaan-inventaris', [PermintaanInventarisController::class, 'store']);
    Route::put('/permintaan-inventaris/{id}', [PermintaanInventarisController::class, 'update']);
    Route::delete('/permintaan-inventaris/{id}', [PermintaanInventarisController::class, 'destroy']);

    // === KEUANGAN ===
    Route::get('/keuangan/ringkasan', [KeuanganController::class, 'ringkasan']);
    Route::post('/keuangan', [KeuanganController::class, 'store']);
    Route::put('/keuangan/{id}', [KeuanganController::class, 'update']);

    // === RENCANA ANGGARAN ===
    Route::get('/rencana-anggaran', [RencanaAnggaranController::class, 'index']);
    Route::get('/rencana-anggaran/{id}', [RencanaAnggaranController::class, 'show']);
    Route::post('/rencana-anggaran', [RencanaAnggaranController::class, 'store']);
    Route::put('/rencana-anggaran/{id}', [RencanaAnggaranController::class, 'update']);
});