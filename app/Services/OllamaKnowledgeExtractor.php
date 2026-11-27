<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaKnowledgeExtractor
{
    public function extract($narasi): array
    {
        $judul = is_object($narasi)
            ? ($narasi->judul ?? $narasi->name ?? 'Narasi Budaya Bengkulu')
            : 'Narasi Budaya Bengkulu';

        $isiNarasi = is_object($narasi)
            ? ($narasi->isi_narasi ?? $narasi->narasi ?? '')
            : (string) $narasi;

        // Daftar kategori yang diizinkan agar AI tidak asal melabeli "Ritual"
        $kategoriList = 'Ritual, Kuliner, Gotong Royong, Kesenian, Tradisi Lisan, Kerajinan';

        $prompt = "Bertindaklah sebagai sejarawan dan ahli budaya Bengkulu yang sangat teliti. "
            . "Analisis narasi budaya berikut dan ekstrak informasinya ke dalam format JSON yang valid (tanpa teks markdown tambahan seperti ```json) dengan atribut berikut:\n"
            . "- name: Judul atau nama budaya yang sesuai.\n"
            . "- category: Pilih HANYA salah satu dari kategori berikut yang paling tepat: [{$kategoriList}].\n"
            . "- description: Deskripsi naratif yang utuh, mendalam, dan kaya makna (minimal 2-3 paragraf penjelas).\n"
            . "- practice: Praktik atau tata cara pelaksanaan budaya tersebut secara rinci (jika tidak ada, tuliskan \"Tidak ada praktik khusus\").\n"
            . "- cultural_term: Istilah khas bahasa lokal yang digunakan (jika ada).\n"
            . "- community: Komunitas atau kelompok masyarakat yang menjalankan.\n"
            . "- people: Tokoh atau perannya (misal: pemangku adat, tokoh masyarakat, dll).\n"
            . "- location: Lokasi spesifik kejadian atau tradisi.\n"
            . "- time: Waktu pelaksanaan (misal: setiap 17 Agustus, bulan Muharram, sepanjang tahun, dll).\n"
            . "- meaning: Makna filosofis atau nilai budaya yang terkandung.\n\n"
            . "Judul Narasi Sumber: {$judul}\n"
            . "Isi Narasi Mentah:\n\"{$isiNarasi}\"\n\n";

        try {
            $response = Http::timeout(120)->post('http://localhost:11434/api/generate', [
                'model' => 'gemma:2b',
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($response->failed()) {
                throw new RuntimeException('Ollama lokal gagal merespons.');
            }

            $result =$response->json();
            $textOutput =$result['response'] ?? null;

            if (!$textOutput) {
                throw new RuntimeException('Respon dari Ollama kosong.');
            }

            // Bersihkan markdown code block
            $textOutput = trim($textOutput);$textOutput = preg_replace('/^```json\s*/i', '', $textOutput);
            $textOutput = preg_replace('/^```\s*/', '', $textOutput);$textOutput = preg_replace('/\s*```$/', '', $textOutput);

            // Ambil string di dalam kurung kurawal terluar
            if (preg_match('/\{[\s\S]*\}/', $textOutput, $matches)) {
                $textOutput = $matches[0];
            }

            // Decode JSON pertama kali
            $hasil = json_decode($textOutput, true);

            // Jika gagal karena karakter kontrol/kutip tidak valid, lakukan pembersihan lanjutan
            if (!is_array($hasil)) {
                $textOutput = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $textOutput);
                $hasil = json_decode($textOutput, true);
            }

            if (!is_array($hasil)) {
                throw new RuntimeException('Format JSON dari Ollama tidak valid.');
            }

            return $hasil;
        } catch (\Throwable $e) {
            throw new RuntimeException('Gagal mengekstrak via Ollama: ' . $e->getMessage(), 0, $e);
        }
    }
}