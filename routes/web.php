<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Ini bisa dihapus jika tidak ada controller lain yg dipakai di sini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kita hanya mendefinisikan rute yang menampilkan halaman Blade.
| Logika login, logout, dan pengambilan data semuanya ada di routes/api.php
|
*/

// Rute untuk menampilkan halaman login
// Middleware 'guest' memastikan user yang sudah punya session tidak bisa ke sini lagi.
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        // 'welcome' adalah file blade yang berisi <login-component>
        return view('welcome');
    })->name('login');
});

// Rute untuk halaman-halaman yang bisa diakses setelah login via API.
// Kita HILANGKAN ->middleware('auth') agar halaman bisa dimuat.
// Keamanan datanya akan ditangani oleh Vue Component yang memanggil API dengan token.

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// === INVENTARIS ===
Route::get('/inventaris', function () {
    return view('inventaris');
});
Route::get('/inventaris/tambah', function () {
    return view('tbhinventaris');
});
Route::get('/inventaris/penyumbang/tambah', function () {
    return view('tbhsumbang');
});
Route::get('/inventaris/keluar-masuk/tambah', function () {
    return view('tbhkeluarmasuk');
});
Route::get('/inventaris/edit/{id}', function ($id) {
    return view('editinventaris', ['inventoryItemId' => $id]);
})->name('editinventaris');
Route::get('/inventaris/penyumbang/edit/{id}', function ($id) {
    return view('editpenyumbang', ['penyumbangItemId' => $id]);
})->name('editpenyumbang');
Route::get('/inventaris/keluar-masuk/edit/{id}', function ($id) {
    return view('editmovement', ['movementItemId' => $id]);
})->name('editmovement');

// === KEGIATAN ===
Route::get('/kegiatan', function () {
    return view('kegiatan');
});
Route::get('/kegiatan/tambah', function () {
    return view('tbhkegiatan');
});
Route::get('/edit-jadwal', function () {
    return view('editshalat');
});
Route::get('/kegiatan/edit/{id}', function ($id) {
    return view('editkegiatan', ['kegiatanItemId' => $id]);
})->name('editkegiatan');

// === KEUANGAN ===
Route::get('/keuangan', function () {
    return view('keuangan');
});
Route::get('/keuangan/transaksi/tambah', function () {
    return view('tbhtransaksi');
});
Route::get('/keuangan/rencana-anggaran/tambah', function () {
    return view('tbhanggaran');
});
Route::get('/keuangan/transaksi/edit/{id}', function ($id) {
    return view('edittransaksi', ['transaksiItemId' => $id]);
})->name('edittransaksi');
Route::get('/keuangan/rencana-anggaran/edit/{id}', function ($id) {
    return view('editanggaran', ['anggaranItemId' => $id]);
})->name('editanggaran');

// PENTING: Rute POST /login dan POST /logout kita HAPUS dari sini
// karena sudah ditangani sepenuhnya oleh routes/api.php