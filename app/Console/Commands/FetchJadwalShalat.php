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
        $idKota = '1634'; // ID Kota Bandung
        $tanggal = Carbon::today();
        $tanggalFormatted = $tanggal->format('Y-m-d');

        $this->info("Mencoba mengambil jadwal shalat untuk kota ID: {$idKota} pada tanggal: {$tanggalFormatted}");

        try {
            $response = Http::get("https://api.myquran.com/v2/sholat/jadwal/{$idKota}/{$tanggalFormatted}");

            if ($response->successful() && $response->json()['status']) {
                $jadwalFromApi = $response->json()['data']['jadwal'];

                $this->info("Berhasil mendapatkan data dari API. Memproses penyimpanan...");

                $waktuShalatPenting = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

                foreach ($waktuShalatPenting as $waktu) {
                    $namaShalat = ucfirst($waktu);
                    
                    JadwalShalat::updateOrInsert(
                        [
                            'tanggal'      => $tanggal,
                            'waktu_shalat' => $namaShalat
                        ],
                        [
                            'jam'        => $jadwalFromApi[$waktu] . ':00',
                        ]
                    );
                }

                $this->info('Jadwal shalat untuk hari ini berhasil disimpan ke database.');
                Log::info('Jadwal shalat berhasil di-fetch dan disimpan.');
                return 0;

            } else {
                $this->error('Gagal mengambil data dari API eksternal.');
                Log::error('Gagal fetch jadwal shalat: ' . $response->body());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Exception saat fetch jadwal shalat: ' . $e->getMessage());
            return 1;
        }
    }
}