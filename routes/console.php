<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use App\Services\OllamaKnowledgeExtractor;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('narasi:ekstrak-sisa {limit=10}', function () {
    $limit = (int) $this->argument('limit');
    
    // Ambil data narasi yang benar-benar belum memiliki relasi ekstraksiLlms
    $narasis = Narasi::whereDoesntHave('ekstraksiLlms')->take($limit)->get();

    if ($narasis->isEmpty()) {
        $this->info('Sempurna! Semua narasi sudah terekstrak 100%.');
        return;
    }

    $extractor = new OllamaKnowledgeExtractor();

    foreach ($narasis as $narasi) {
        $this->line("Memproses ID {$narasi->id}: " . ($narasi->judul_narasi ?? 'Tanpa Judul'));

        try {
            $hasil = $extractor->extract($narasi);
        } catch (\Exception $e) {
            // Fallback cadangan yang aman jika AI gagal
            $hasil = [
                'name' => $narasi->judul_narasi ?? 'Budaya Bengkulu',
                'category' => 'ritual',
                'description' => $narasi->isi_narasi ?? 'Deskripsi belum tersedia.',
                'location' => $narasi->lokasi_penelitian ?? 'Bengkulu',
            ];
            $this->warn(" -> Peringatan AI gagal, menggunakan data Cadangan.");
        }

        // Simpan ke model EkstraksiLlm agar tombol Tinjau di Dashboard langsung menyala
        \App\Models\EkstraksiLlm::updateOrCreate(
            ['narasi_id' => $narasi->id],
            [
                'name' => $hasil['name'] ?? ($narasi->judul_narasi ?? 'Budaya Bengkulu'),
                'category' => $hasil['category'] ?? 'ritual',
                'description' => $hasil['description'] ?? ($narasi->isi_narasi ?? ''),
                'location' => $hasil['location'] ?? ($narasi->lokasi_penelitian ?? 'Bengkulu'),
                'image' => $narasi->image ?? null, // Memastikan gambar ikut tersimpan
            ]
        );

        $this->info(" -> Sukses masuk ke database dan dashboard!");
    }

    $sisa = Narasi::whereDoesntHave('ekstraksiLlms')->count();
    $this->info("Batch ini selesai. Sisa data yang belum terekstrak: {$sisa} data lagi.");
});