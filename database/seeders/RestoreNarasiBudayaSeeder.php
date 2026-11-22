<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Narasi;
use App\Models\EkstraksiLlms;
use App\Models\Pengetahuan;

class RestoreNarasiBudayaSeeder extends Seeder
{
    public function run()
    {
        // Path menuju file narasi_budaya.json di root project
        $jsonPath = base_path('narasi_budaya.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("File narasi_budaya.json tidak ditemukan!");
            return;
        }

        $jsonData = json_decode(File::get($jsonPath), true);

        $this->command->info("Memproses " . count($jsonData) . " data narasi budaya Bengkulu...");

        foreach ($jsonData as $item) {
            // 1. Simpan ke tabel narasis
            $narasi = Narasi::create([
                'judul_narasi' => $item['judul_narasi'] ?? $item['name'] ?? 'Tanpa Judul',
                'isi_narasi' => $item['isi_narasi'] ?? $item['description'] ?? '',
                'jenis_sumber' => $item['jenis_sumber'] ?? 'Dokumentasi Lapangan',
                'nama_sumber' => $item['nama_sumber'] ?? 'Peneliti',
                'lokasi_penelitian' => $item['lokasi_penelitian'] ?? 'Bengkulu',
                'status_verifikasi' => 'Terverifikasi',
            ]);

            // 2. Simpan ke tabel ekstraksi_llms (Hasil AI Ollama)
            $ekstraksi = EkstraksiLlms::create([
                'narasi_id' => $narasi->id,
                'model_llm' => 'gemma:2b',
                'hasil_ekstraksi' => json_encode([
                    'name' => $item['name'] ?? '',
                    'category' => $item['category'] ?? '',
                    'description' => $item['description'] ?? '',
                    'location' => $item['location'] ?? 'Bengkulu'
                ]),
                'status_review' => 'Approved'
            ]);

            // 3. Simpan ke tabel pengetahuans (Lengkap dengan gambar aslinya)
            Pengetahuan::create([
                'narasi_id' => $narasi->id,
                'ekstraksi_llm_id' => $ekstraksi->id,
                'name' => $item['name'] ?? 'Budaya Bengkulu',
                'category' => $item['category'] ?? 'Umum',
                'description' => $item['description'] ?? '',
                'practice' => $item['practice'] ?? '',
                'cultural_term' => $item['cultural_term'] ?? '',
                'community' => $item['community'] ?? '',
                'people' => $item['people'] ?? '',
                'location' => $item['location'] ?? 'Bengkulu',
                'time' => $item['time'] ?? '',
                'meaning' => $item['meaning'] ?? '',
                'image' => $item['image'] ?? 'pengetahuan/default.jpg',
            ]);
        }

        $this->command->info("✨ Berhasil! Seluruh data narasi dan pengetahuan berhasil dipulihkan.");
    }
}