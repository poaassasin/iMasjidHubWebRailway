<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori');
            $table->decimal('jumlah', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
