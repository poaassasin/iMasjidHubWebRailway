<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\JadwalShalat;

class FetchJadwalShalat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengambil data jadwal shalat dari API eksternal dan menyimpannya ke database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idKota = '1634'; // Ganti dengan ID kota Anda
        $tanggalHariIni = Carbon::today();
        $tanggalFormatted = $tanggalHariIni->format('Y-m-d');

        $this->info("Mengambil jadwal untuk tanggal: {$tanggalFormatted} untuk di-update.");

        try {
            $response = Http::get("https://api.myquran.com/v2/sholat/jadwal/{$idKota}/{$tanggalFormatted}");

            if ($response->successful() && $response->json()['status']) {
                $jadwalFromApi = $response->json()['data']['jadwal'];
                $this->info("Berhasil dapat data dari API. Meng-update 5 baris di database...");

                // Daftar nama shalat yang HARUS SAMA PERSIS dengan di database Anda
                $daftarShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];

                // Mapping dari nama di DB ke nama di API (yang huruf kecil)
                $apiNameMapping = [
                    'Subuh'   => 'subuh',
                    'Dzuhur'  => 'dzuhur',
                    'Ashar'   => 'ashar',
                    'Maghrib' => 'maghrib',
                    'Isya'    => 'isya',
                ];

                foreach ($daftarShalat as $namaShalatDiDb) {
                    $namaShalatDiApi = $apiNameMapping[$namaShalatDiDb];
                    $waktuShalat = $jadwalFromApi[$namaShalatDiApi];

                    // --- LOGIKA BARU: UPDATE-IN-PLACE ---
                    // Cari baris berdasarkan 'waktu_shalat', lalu update HANYA jam dan tanggal.
                    JadwalShalat::where('waktu_shalat', $namaShalatDiDb)
                        ->update([
                            'jam' => $waktuShalat . ':00',
                            'tanggal' => $tanggalHariIni,
                        ]);
                }

                $this->info('5 baris jadwal shalat berhasil di-update.');
                Log::info('Jadwal shalat berhasil di-update (in-place).');
                return 0; // Sukses

            } else {
                $this->error('Gagal mengambil data dari API eksternal.');
                Log::error('Gagal fetch jadwal shalat: ' . $response->body());
                return 1; // Error
            }
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Exception saat fetch jadwal shalat: ' . $e->getMessage());
            return 1; // Error
        }
    }
}
