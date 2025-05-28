<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kategori'); // contoh: elektronik, alat shalat, kebersihan, dll
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
