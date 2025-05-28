<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permintaan_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peminta');
            $table->string('item');
            $table->integer('jumlah');
            $table->time('jam');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('permintaan_inventaris');
    }
};

