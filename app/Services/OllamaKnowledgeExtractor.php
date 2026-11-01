<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaKnowledgeExtractor
{
    public function extract($narasi): array
    {
        $teksNarasi = is_object($narasi) ? ($narasi->isi_narasi ?? '') : (string) $narasi;

        // Prompt dibuat lebih ringkas dan to the point agar gemma:2b tidak kewalahan
        $prompt = "Ekstrak informasi budaya Bengkulu berikut ke dalam format JSON murni.\n" .
            "Jangan gunakan tanda kutip ganda di dalam teks deskripsi agar JSON tidak rusak.\n" .
            "Struktur JSON harus persis seperti ini:\n" .
            "{\n" .
            "  \"name\": \"Judul budaya\",\n" .
            "  \"category\": \"ritual\",\n" .
            "  \"description\": \"Ringkasan deskripsi lengkap budaya tersebut\",\n" .
            "  \"location\": \"Bengkulu\"\n" .
            "}\n\n" .
            "Teks Narasi:\n\"{$teksNarasi}\"";

        try {
            $response = Http::timeout(180)
                ->connectTimeout(15)
                ->post('http://127.0.0.1:11434/api/generate', [
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
                // Perbaiki kutip internal yang berpotensi merusak JSON
                $hasil = json_decode($textOutput, true);
            }

            if (!is_array($hasil)) {
                throw new RuntimeException('Format JSON dari Ollama tidak valid.');
            }

            return $hasil;
        } catch (\Exception $e) {
            throw new RuntimeException('Gagal mengekstrak via Ollama: ' . $e->getMessage());
        }
    }
}