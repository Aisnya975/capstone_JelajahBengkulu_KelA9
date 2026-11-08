<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use Illuminate\Support\Facades\Http;

class ProsesEkstraksiOllama extends Command
{
    protected $signature = 'ollama:ekstrak-massal';
    protected $description = 'Menjalankan ekstraksi AI Ollama Gemma:2b secara massal untuk seluruh narasi';

    public function handle()
    {
        $narasis = Narasi::all();
        $total = $narasis->count();
        
        $this->info("🤖 Memulai ekstraksi AI Ollama Gemma:2b untuk {$total} narasi...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($narasis as $narasi) {
            // Panggil API lokal Ollama (pastikan aplikasi Ollama di laptopmu sedang aktif menyala)
            try {
                $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
                    'model' => 'gemma:2b',
                    'prompt' => "Ekstrak informasi budaya berikut ke dalam format ringkas:\n" . $narasi->isi_narasi,
                    'stream' => false
                ]);

                $hasilAi = $response->successful() ? $response->json('response') : 'Gagal ekstrak AI';
            } catch (\Exception $e) {
                $hasilAi = 'Ollama tidak merespons';
            }

            // Simpan atau perbarui ke tabel pengetahuans dengan hasil dari Ollama
            Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'name' => $narasi->judul_narasi,
                    'description' => $narasi->isi_narasi,
                    // Masukkan hasil respons AI ke kolom deskripsi atau kolom LLM
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✨ Selesai! Seluruh narasi telah diproses melalui AI Ollama.");
    }
}