<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalShalatSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['waktu_shalat' => 'Shubuh', 'jam' => '04:14:00', 'imam' => 'Ust. Jalaluddin'],
            ['waktu_shalat' => 'Dhuhur', 'jam' => '11:29:00', 'imam' => 'Ust. Jalaluddin'],
            ['waktu_shalat' => 'Ashar',  'jam' => '14:50:00', 'imam' => 'Ust. Jalaluddin'],
            ['waktu_shalat' => 'Magrib', 'jam' => '17:22:00', 'imam' => 'Ust. Jalaluddin'],
            ['waktu_shalat' => 'Isha',   'jam' => '18:34:00', 'imam' => 'Ust. Jalaluddin'],
        ];

        foreach ($data as $item) {
            DB::table('jadwal_shalat')->updateOrInsert(
                ['waktu_shalat' => $item['waktu_shalat']],
                array_merge($item, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}