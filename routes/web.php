<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        // 'welcome' adalah file blade yang berisi <login-component>
        return view('welcome');
    })->name('login');

    // Rute yang menangani proses submit login dari Vue Component
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    //inventaris
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
        
        return view('editinventaris', [
            'inventoryItemId' => $id 
        ]);
    })->name('editinventaris');
    Route::get('/inventaris/penyumbang/edit/{id}', function ($id) {
        
        return view('editpenyumbang', [
            'penyumbangItemId' => $id 
        ]);
    })->name('editpenyumbang');
    Route::get('/inventaris/keluar-masuk/edit/{id}', function ($id) {
        
        return view('editmovement', [
            'movementItemId' => $id 
        ]);
    })->name('editmovement');
    //end of inventaris


    //kegiatan
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
        
        return view('editkegiatan', [
            'kegiatanItemId' => $id 
        ]);
    })->name('editkegiatan');
    //end of kegiatan


    //keuangan
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
        
        return view('edittransaksi', [
            'transaksiItemId' => $id 
        ]);
    })->name('edittransaksi');
    Route::get('/keuangan/rencana-anggaran/edit/{id}', function ($id) {
        
        return view('editanggaran', [
            'anggaranItemId' => $id 
        ]);
    })->name('editanggaran');
    //end of keuangan

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
