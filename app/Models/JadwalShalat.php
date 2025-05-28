<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalShalat extends Model
{
    use HasFactory;

    protected $fillable = [
        'waktu_shalat',
        'jam',
        'imam',
        'tanggal',
    ];
}