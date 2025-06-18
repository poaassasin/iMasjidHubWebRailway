<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Import Carbon untuk mengelola tanggal

class JadwalShalatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tentukan tanggal yang akan diisi, contoh: hari ini
        $tanggalHariIni = Carbon::today();

        $data = [
            ['waktu_shalat' => 'Subuh', 'jam' => '04:14:00', 'imam' => 'Ahmad Rifqy'],
            ['waktu_shalat' => 'Dzuhur', 'jam' => '11:30:00', 'imam' => 'Furqon Hamid Fakaubun, S.T.'],
            ['waktu_shalat' => 'Ashar',  'jam' => '14:55:00', 'imam' => 'Syaikh Yusuf Ali Al-Jaberi'],
            ['waktu_shalat' => 'Maghrib', 'jam' => '17:29:00', 'imam' => 'Ahmad Rifqy'],
            ['waktu_shalat' => 'Isya',   'jam' => '18:37:00', 'imam' => 'Ahmad Rifqy'],
        ];

        foreach ($data as $item) {
            DB::table('jadwal_shalats')->updateOrInsert(
                [
                    // Kondisi: Cari baris yang waktu_shalat DAN tanggalnya cocok
                    'waktu_shalat' => $item['waktu_shalat'],
                    'tanggal'      => $tanggalHariIni,
                ],
                [
                    // Nilai: Data yang akan diisi atau diperbarui
                    'jam'        => $item['jam'],
                    'imam'       => $item['imam'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}