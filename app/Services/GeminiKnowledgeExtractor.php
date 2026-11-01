<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiKnowledgeExtractor
{
    public function extract(string $narasi): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-3.8-flash');

        if (empty($apiKey)) {
            throw new RuntimeException(
                'API key Gemini belum diatur pada file .env.'
            );
        }

        $schema = [
            'name' => null,
            'category' => null,
            'description' => null,
            'practice' => [],
            'cultural_term' => [],
            'community' => [],
            'people' => [],
            'location' => null,
            'time' => null,
            'meaning' => null,
        ];

        $prompt = <<<PROMPT
Kamu adalah asisten ekstraksi pengetahuan budaya lokal pesisir Bengkulu.

Ekstrak informasi hanya berdasarkan narasi yang diberikan.
Jangan mengarang informasi yang tidak tersedia.
Jika informasi berupa teks tidak ditemukan, gunakan null.
Jika informasi berupa daftar tidak ditemukan, gunakan [].

Aturan kategori:
- ritual
- tradisi_nelayan
- kesenian
- kuliner
- festival
- kerajinan
- pengetahuan_lokal
- lainnya

Kembalikan hanya satu objek JSON yang valid, tanpa Markdown
dan tanpa teks tambahan.

Struktur JSON yang harus digunakan:
PROMPT;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                                . "\n"
                                . json_encode(
                                    $schema,
                                    JSON_UNESCAPED_UNICODE
                                )
                                . "\n\nNarasi:\n"
                                . $narasi,
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.1,
            ],
        ];

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
            . $model
            . ':generateContent';

        // Ditambahkan fungsi retry(2, 1500) untuk mengatasi error 503 sementara
        $response = Http::retry(2, 1500)->withHeaders([
            'x-goog-api-key' => $apiKey,
        ])
            ->timeout(60)
            ->post($url, $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'Gemini API gagal (HTTP '
                . $response->status()
                . '): '
                . $response->json('error.message', 'Kesalahan API')
            );
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Gemini tidak mengembalikan hasil teks.'
            );
        }

        $hasil = json_decode($text, true);

        if (!is_array($hasil) || json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Respons Gemini bukan JSON yang valid.'
            );
        }

        $kategoriValid = [
            'ritual',
            'tradisi_nelayan',
            'kesenian',
            'kuliner',
            'festival',
            'kerajinan',
            'pengetahuan_lokal',
            'lainnya',
        ];

        if (
            isset($hasil['category'])
            && $hasil['category'] !== null
            && !in_array($hasil['category'], $kategoriValid, true)
        ) {
            $hasil['category'] = 'lainnya';
        }

        foreach ([
            'name',
            'category',
            'description',
            'location',
            'time',
            'meaning',
        ] as $field) {
            if (!array_key_exists($field, $hasil)) {
                $hasil[$field] = null;
            }
        }

        foreach ([
            'practice',
            'cultural_term',
            'community',
            'people',
        ] as $field) {
            if (
                !isset($hasil[$field])
                || !is_array($hasil[$field])
            ) {
                $hasil[$field] = [];
            }
        }

        return $hasil;
    }
}