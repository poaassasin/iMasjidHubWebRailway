<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Masjid Al-Waqar',
            'email' => 'masjidalwaqar@gmail.com',
            'password' => Hash::make('alwaqar123'),
            'role' => 'admin', // sesuaikan jika ingin role lain
            'api_token' => Str::random(60),
        ]);
    }
}