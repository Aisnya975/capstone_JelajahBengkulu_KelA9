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
    Schema::create('narasis', function (Blueprint $table) {
        $table->id();
        $table->string('judul_narasi');
        $table->text('isi_narasi');

        $table->string('jenis_sumber');
        $table->string('nama_sumber')->nullable();
        $table->string('jenis_informan')->nullable();
        $table->string('lokasi_penelitian')->nullable();
        $table->date('tanggal_pengumpulan')->nullable();
        $table->string('metode_pengumpulan')->nullable();

        $table->enum('status_verifikasi', [
            'menunggu',
            'terverifikasi',
            'ditolak'
        ])->default('menunggu');

        $table->text('catatan_verifikasi')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('narasis');
    }
};
