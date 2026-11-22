<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Narasi;
use App\Models\EkstraksiLlm;
use App\Models\Pengetahuan;
use App\Services\OllamaKnowledgeExtractor;

class ImportNarasiJson extends Command
{
    protected $signature = 'import:narasi-json';
    protected $description = 'Impor dan ekstrak data narasi budaya via Ollama ke tabel ekstraksi_llms tanpa double dan pertahankan gambar';

    public function handle(OllamaKnowledgeExtractor $aiExtractor)
    {
        $path = base_path('narasi_budaya.json');
        if (!file_exists($path)) {
            $path = storage_path('app/narasi_budaya.json');
        }

        if (!file_exists($path)) {
            $this->error("File narasi_budaya.json tidak ditemukan di root project maupun di storage/app!");
            return;
        }

        $json = file_get_contents($path);
        $dataList = json_decode($json, true);

        if (!$dataList) {
            $this->error("Format file JSON kosong atau tidak valid.");
            return;
        }

        $this->info("Memulai proses impor dan ekstraksi AI untuk " . count($dataList) . " data...");

        $bar = $this->output->createProgressBar(count($dataList));
        $bar->start();

        foreach ($dataList as $data) {
            $judul = $data['judul_narasi'] ?? $data['name'] ?? 'Tanpa Judul';
            $isiNarasi = $data['isi_narasi'] ?? $data['description'] ?? 'Tidak ada narasi.';

            // 1. Cegah Double di tabel Narasi
            $narasi = Narasi::updateOrCreate(
                ['judul_narasi' => $judul],
                [
                    'isi_narasi'         => $isiNarasi,
                    'jenis_sumber'       => $data['jenis_sumber'] ?? 'sumber_lainnya',
                    'nama_sumber'        => $data['nama_sumber'] ?? 'Arsip Lokal',
                    'jenis_informan'     => $data['jenis_informan'] ?? 'Bukan wawancara',
                    'lokasi_penelitian'  => $data['lokasi_penelitian'] ?? 'Bengkulu',
                    'tanggal_pengumpulan'=> $data['tanggal_pengumpulan'] ?? now(),
                    'metode_pengumpulan' => $data['metode_pengumpulan'] ?? 'Studi pustaka',
                    'status_verifikasi'  => 'terverifikasi',
                ]
            );

            // 2. Buat Prompt agar Ollama (Gemma:2b) menjelaskan dan memperkaya deskripsi budaya
            $description = $isiNarasi;
            $category = $data['category'] ?? 'ritual, Kuliner, Gotong Royong, Kesenian, Tradisi Lisan, Kerajinan';
            $location = $data['lokasi_penelitian'] ?? 'Bengkulu';
            $rawAiResult = [];

            try {
                // Prompt yang meminta AI Ollama menjelaskan budaya tersebut secara mendalam
                $prompt = "Bertindaklah sebagai sejarawan dan ahli budaya Bengkulu yang sangat teliti. " .
                  "Analisis narasi budaya berikut dan ekstrak informasinya ke dalam format JSON yang valid (tanpa teks markdown tambahan seperti ```json) dengan atribut berikut:\n" .
                  "- name: Judul atau nama budaya yang sesuai.\n" .
                  "- category: Pilih HANYA salah satu dari kategori berikut yang paling tepat: [{$kategoriList}].\n" .
                  "- description: Deskripsi naratif yang utuh, mendalam, dan kaya makna (minimal 2-3 paragraf penjelas).\n" .
                  "- practice: Praktik atau tata cara pelaksanaan budaya tersebut secara rinci (jika tidak ada, tuliskan \"Tidak ada praktik khusus\").\n" .
                  "- cultural_term: Istilah khas bahasa lokal yang digunakan (jika ada).\n" .
                  "- community: Komunitas atau kelompok masyarakat yang menjalankan.\n" .
                  "- people: Tokoh atau perannya (misal: pemangku adat, tokoh masyarakat, dll).\n" .
                  "- location: Lokasi spesifik kejadian atau tradisi.\n" .
                  "- time: Waktu pelaksanaan (misal: setiap 17 Agustus, bulan Muharram, sepanjang tahun, dll).\n" .
                  "- meaning: Makna filosofis atau nilai budaya yang terkandung.\n\n" .
                  "Judul Narasi Sumber: {$judul}\n" .
                  "Isi Narasi Mentah:\n\"{$isiNarasi}\"\n\n" .
                  "Pastikan semua atribut terisi lengkap dan akurat dalam format JSON.";

                $response = \Illuminate\Support\Facades\Http::timeout(60)->post('http://localhost:11434/api/generate', [
                    'model' => 'gemma:2b',
                    'prompt' => $prompt,
                    'stream' => false
                ]);

                if ($response->successful() && $response->json('response')) {
                    $aiText = trim($response->json('response'));
                    if (!empty($aiText)) {
                        $description = $aiText; // Deskripsi kini diisi oleh hasil penjelasan AI Ollama!
                    }
                }

                $rawAiResult = [
                    'name' => $judul,
                    'category' => $category,
                    'description' => $description,
                    'location' => $location
                ];

            } catch (\Exception $e) {
                // Jika Ollama offline/timeout, gunakan teks asli dari JSON
                $description = $isiNarasi;
            }

            // 3. SIMPAN KE TABEL EKSTRAKSI_LLMS
            $ekstraksi = EkstraksiLlm::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'model_llm' => 'gemma:2b',
                    'hasil_ekstraksi' => json_encode(!empty($rawAiResult) ? $rawAiResult : [
                        'name' => $judul,
                        'category' => $category,
                        'description' => $description,
                        'location' => $location
                    ]),
                ]
            );

            // 4. SIMPAN KE TABEL PENGETAHUAN
            Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'ekstraksi_llm_id' => $ekstraksi->id,
                    'name'        => $judul,
                    'category'    => $category,
                    'description' => $description,
                    'location'    => $location,
                    'image'       => $data['image'] ?? null,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✨ Berhasil! Semua data berhasil diperbarui dengan narasi asli, masuk ke tabel ekstraksi LLM, dan terekstrak AI.");
    }
}