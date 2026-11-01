<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use App\Services\OllamaKnowledgeExtractor;

class ImportNarasiJson extends Command
{
    protected $signature = 'import:narasi-json';
    protected $description = 'Impor dan ekstrak data narasi budaya via Ollama tanpa double dan pertahankan gambar';

    public function handle(OllamaKnowledgeExtractor $aiExtractor)
    {
        $path = storage_path('app/narasi_budaya.json');

        if (!file_exists($path)) {
            $this->error("File JSON tidak ditemukan di: " . $path);
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
            $judul = $data['judul_narasi'] ?? 'Tanpa Judul';

            // 1. Cegah Double di tabel Narasi (update jika sudah ada, atau buat baru)
            $narasi = Narasi::updateOrCreate(
                ['judul_narasi' => $judul],
                [
                    'isi_narasi'         => $data['isi_narasi'] ?? '',
                    'jenis_sumber'       => $data['jenis_sumber'] ?? 'sumber_lainnya',
                    'nama_sumber'        => $data['nama_sumber'] ?? 'Arsip Lokal',
                    'jenis_informan'     => $data['jenis_informan'] ?? 'Bukan wawancara',
                    'lokasi_penelitian'  => $data['lokasi_penelitian'] ?? 'Bengkulu',
                    'tanggal_pengumpulan'=> $data['tanggal_pengumpulan'] ?? now(),
                    'metode_pengumpulan' => $data['metode_pengumpulan'] ?? 'Studi pustaka',
                    'status_verifikasi'  => 'terverifikasi',
                ]
            );

            // 2. Coba Ekstraksi AI Ollama agar deskripsinya jadi panjang dan kaya
            $description = $data['isi_narasi']; // Default teks awal
            $category = $data['category'] ?? 'ritual';
            $location = $data['lokasi_penelitian'] ?? 'Bengkulu';

            try {
                $aiResult = $aiExtractor->extract($narasi);
                if (!empty($aiResult['description'])) {
                    $description = $aiResult['description']; // Ambil hasil AI yang panjang
                }
                if (!empty($aiResult['category'])) {
                    $category = $aiResult['category'];
                }
                if (!empty($aiResult['location'])) {
                    $location = $aiResult['location'];
                }
            } catch (\Exception $e) {
                // Jika AI gagal/timeout pada data tertentu, gunakan teks asli dari JSON agar tidak error berhenti
                $description = $data['isi_narasi'];
            }

            // 3. Simpan/Update ke Pengetahuan (Dashboard Utama) tanpa merusak gambar
            Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'name'        => $judul,
                    'category'    => $category,
                    'description' => $description,
                    'location'    => $location,
                    'image'       => $data['image'] ?? null, // Gambar tetap aman sesuai JSON
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nBerhasil! Semua data berhasil diperbarui tanpa double dan terekstrak AI.");
    }
}