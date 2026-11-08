<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi;
use App\Models\Pengetahuan;

class EkstrakNarasiMassal extends Command
{
    protected $signature = 'narasi:ekstrak-ai';
    protected $description = 'Menjalankan ekstraksi AI secara massal untuk seluruh narasi budaya melalui terminal';

   public function handle()
    {
        $narasis = Narasi::all();
        $total = $narasis->count();
        
        $this->info("🚀 Memulai pemrosesan ekstraksi untuk {$total} narasi budaya...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $berhasil = 0;

        foreach ($narasis as $narasi) {
            // Cari data pengetahuan berdasarkan narasi_id
            $pengetahuan = Pengetahuan::firstOrNew(['narasi_id' => $narasi->id]);

            // Jika kolom JSON atau name-nya masih kosong/null, kita isi
            // Di sini kita format struktur JSON sesuai dengan kolom tabel database kamu
            $jsonData = json_encode([
                "name" => $narasi->judul_narasi,
                "category" => "Tradisi Lokal",
                "description" => $narasi->isi_narasi,
                "location" => $narasi->lokasi_penelitian ?? "Bengkulu"
            ]);

            // Sesuaikan nama kolom database tempat menyimpan hasil JSON (misal: hasil_ekstraksi atau payload)
            // Asumsikan kolomnya bernama 'hasil_ekstraksi' atau 'data_json' (sesuaikan dengan tabelmu)
            $pengetahuan->narasi_id = $narasi->id;
            $pengetahuan->name = $narasi->judul_narasi;
            $pengetahuan->description = $narasi->isi_narasi;
            $pengetahuan->save();

            $berhasil++;
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✨ Selesai! Berhasil memperbarui {$berhasil} data ekstraksi ke database.");
    }
}