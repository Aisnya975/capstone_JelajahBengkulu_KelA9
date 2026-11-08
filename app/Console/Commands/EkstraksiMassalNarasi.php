<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi;
use App\Models\Pengetahuan;
// Tambahkan *use* controller atau service AI kamu di sini jika diperlukan

class EkstraksiMassalNarasi extends Command
{
    protected $signature = 'narasi:ekstraksi-massal';
    protected $description = 'Menjalankan ekstraksi AI secara massal untuk seluruh narasi budaya';

    public function handle()
    {
        $narasis = Narasi::all(); // Atau filter sesuai kebutuhan
        $this->info("Memulai ekstraksi untuk " . $narasis->count() . " narasi...");

        foreach ($narasis as $narasi) {
            // Cek apakah sudah terekstrak
            $sudahTerekstrak = Pengetahuan::where('narasi_id', $narasi->id)->exists();

            if (!$sudahTerekstrak) {
                $this->line("Mengekstrak: " . $narasi->judul_narasi);
                
                // Panggil logika atau fungsi ekstraksi AI yang biasa kamu jalankan di controller
                // Contoh: Controller::panggilLogikaEkstraksi($narasi->id);
            }
        }

        $this->info("✨ Proses ekstraksi massal selesai!");
    }
}
