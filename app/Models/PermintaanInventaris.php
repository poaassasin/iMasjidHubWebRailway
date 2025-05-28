<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanInventaris extends Model
{
    use HasFactory;

    protected $table = 'permintaan_inventaris';

    protected $fillable = [
        'nama_peminta',
        'item',
        'jumlah',
        'jam',
    ];
}
