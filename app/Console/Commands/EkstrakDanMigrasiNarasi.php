<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi; // Sesuaikan jika model tabel mentahmu berbeda
use App\Models\Pengetahuan;
use App\Services\OllamaKnowledgeExtractor;

class EkstrakDanMigrasiNarasi extends Command
{
    protected $signature = 'narasi:ekstrak-semua';
    protected $description = 'Mengekstrak seluruh narasi mentah menggunakan Ollama AI secara otomatis';

    public function handle(OllamaKnowledgeExtractor $extractor)
    : void
    {
        $this->info('Memulai ekstraksi massal narasi budaya dengan Ollama...');

        $narasis = Narasi::all();

        if ($narasis->isEmpty()) {
            $this->warn('Tidak ada data narasi yang ditemukan.');
            return;
        }

        $this->info('Total data ditemukan: ' . count($narasis) . '. Proses sedang berjalan, mohon tunggu...');

        foreach ($narasis as $index => $narasi) {
            $num = $index + 1;
            $this->line("Memproses data ke-{$num}: " . ($narasi->judul ?? 'Tanpa Judul'));

            try {
                // Panggil layanan ekstraksi AI
                $hasil = $extractor->extract($narasi);
            } catch (\Exception $e) {
                // Fallback jika AI gagal/JSON tidak valid: gunakan data mentah aslinya agar tidak macet
                $hasil = [
                    'name' => $narasi->judul ?? 'Budaya Bengkulu ' . $num,
                    'category' => 'ritual',
                    'description' => $narasi->isi_narasi ?? 'Deskripsi belum tersedia.',
                    'location' => 'Bengkulu',
                ];
                $this->warn(" -> Peringatan AI gagal, menggunakan data Cadangan/Fallback.");
            }

            // Simpan ke tabel publik
            Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id], // Menjadikan narasi_id sebagai acuan unik
                [
                    'name' => $hasil['name'] ?? ($narasi->judul ?? 'Budaya Bengkulu'),
                    'category' => $hasil['category'] ?? 'ritual',
                    'description' => $hasil['description'] ?? ($narasi->isi_narasi ?? ''),
                    'location' => $hasil['location'] ?? 'Bengkulu',
                    'image' => $narasi->image ?? null,
                ]
            );

            $this->info(" -> Berhasil disimpan ke database!");
        }
}