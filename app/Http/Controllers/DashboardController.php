<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use App\Models\Keuangan;
use App\Models\JadwalShalat;
use App\Models\PermintaanInventaris;
use App\Models\Acara;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function overview()
    {
        // Statistik inventaris per kategori
        $kategoriStats = Inventaris::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        // Total pemasukan dan pengeluaran
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');

        // Jadwal Sholat dan Imam hari ini (diasumsikan satu data per hari)
        $jadwalSholat = JadwalShalat::whereDate('tanggal', Carbon::today())->get(); // atau bisa difilter berdasarkan tanggal

        // Permintaan Inventaris hari ini
        $permintaanHariIni = PermintaanInventaris::whereDate('created_at', Carbon::today())
            ->with('user', 'inventaris')
            ->get();

        // Acara terdekat
        $acaraTerdekat = Acara::whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal')
            ->first();

        // Format tanggal hari ini
        $tanggalHariIni = Carbon::now()->translatedFormat('l, d F Y');

        return response()->json([
            'kategori_inventaris' => $kategoriStats,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'jadwal_sholat' => $jadwalSholat,
            'permintaan_inventaris' => $permintaanHariIni,
            'acara_terdekat' => $acaraTerdekat,
            'tanggal_hari_ini' => $tanggalHariIni,
        ]);
    }
}