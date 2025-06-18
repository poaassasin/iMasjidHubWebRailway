<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      {  Schema::create('jadwal_shalats', function (Blueprint $table) {
            $table->id();
            $table->string('waktu_shalat'); // subuh, dzuhur, ashar, maghrib, isya
            $table->time('jam');            // contoh: 04:30:00
            $table->string('imam')->nullable(); // nama imam (bisa kosong)
            $table->date('tanggal');        // tanggal jadwal
            $table->timestamps();
        });
        
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_shalats');
    }
};
