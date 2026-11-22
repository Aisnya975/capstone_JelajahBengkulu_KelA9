<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi; // Sesuaikan dengan model narasi kamu
use App\Models\EkstraksiLlm; // Model tempat menyimpan hasil ekstraksi
use Illuminate\Support\Facades\Http;

class ReextractNarratives extends Command
{
    protected $signature = 'narratives:reextract';
    protected $description = 'Melakukan ekstraksi ulang 100 data narasi menggunakan Ollama (gemma:2b)';

    public function handle()
    {
        $this->info('Memulai proses re-ekstraksi 100 narasi...');

        // Ambil semua data narasi (atau batasi 100 data pertama N001-N100)
        $narasis = Narasi::take(100)->get();
        
        $bar =$this->output->createProgressBar(count($narasis));$bar->start();

        $kategoriList = "Ritual, Kuliner, Gotong Royong, Kesenian, Tradisi Lisan, Kerajinan";

        foreach ($narasis as$narasi) {
            // Prompt yang sudah disempurnakan agar tidak missout & tidak salah kategori
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
                      "Judul Narasi Sumber: {$narasi->judul}\n" .
                      "Isi Narasi Mentah:\n\"{$narasi->isi_narasi}\"\n\n" .
                      "Pastikan semua atribut terisi lengkap dan akurat dalam format JSON.";

            try {
                // Request ke Ollama lokal
                $response = Http::timeout(90)->post('http://localhost:11434/api/generate', [
                    'model' => 'gemma:2b',
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    $rawContent = $response->json('response');
                    
                    // Bersihkan tag markdown jika model masih memunculkannya
                    $cleanedJson = str_replace(['```json', '```'], '', $rawContent);
                    $dataJson = json_decode(trim($cleanedJson), true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($dataJson)) {
                        // Simpan atau perbarui data ke tabel ekstraksi_llms berdasarkan id narasi
                        EkstraksiLlm::updateOrCreate(
                            ['narasi_id' => $narasi->id],
                            [
                                'name' => $dataJson['name'] ?? $narasi->judul,
                                'category' => $dataJson['category'] ?? 'Tradisi Lisan',
                                'description' => $dataJson['description'] ?? '',
                                'practice' => $dataJson['practice'] ?? '',
                                'cultural_term' => $dataJson['cultural_term'] ?? '',
                                'community' => $dataJson['community'] ?? '',
                                'people' => $dataJson['people'] ?? '',
                                'location' => $dataJson['location'] ?? '',
                                'time' => $dataJson['time'] ?? '',
                                'meaning' => $dataJson['meaning'] ?? '',
                                'status' => 'pending' // Menunggu tinjauan Admin kembali
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                // Jika terjadi timeout atau error pada baris tertentu, catat atauabaikan agar perulangan lanjut
                $this->error(" Gagal pada ID {$narasi->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Proses re-ekstraksi 100 data selesai!');
    }
}