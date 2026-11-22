<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NarasiController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap kata kunci pencarian dari input search
        $search = $request->input('search');

        $totalNarasi = Narasi::count();

        $menunggu = Narasi::where(
            'status_verifikasi',
            'menunggu'
        )->count();

        $terverifikasi = Narasi::where(
            'status_verifikasi',
            'terverifikasi'
        )->count();

        // Query data narasi dengan tambahan filter pencarian (judul atau lokasi)
        $narasis = Narasi::when($search, function ($query, $search) {
                return $query->where('judul_narasi', 'like', "%{$search}%")
                           ->orWhere('lokasi_penelitian', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]); // Menjaga parameter search tetap ada saat pindah halaman pagination

        return view('admin.narasi.index', compact(
            'totalNarasi',
            'menunggu',
            'terverifikasi',
            'narasis',
            'search'
        ));
    }

    public function create()
    {
        return view('admin.narasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_narasi' => ['required', 'string', 'max:255'],
            'isi_narasi' => ['required', 'string', 'min:20'],
            'jenis_sumber' => ['required', 'string', 'max:255'],
            'nama_sumber' => ['nullable', 'string', 'max:255'],
            'jenis_informan' => ['nullable', 'string', 'max:255'],
            'lokasi_penelitian' => ['nullable', 'string', 'max:255'],
            'tanggal_pengumpulan' => ['nullable', 'date'],
            'metode_pengumpulan' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['status_verifikasi'] = 'menunggu';

        Narasi::create($validated);

        return redirect()
            ->route('admin.narasi.index')
            ->with('success', 'Narasi budaya berhasil disimpan.');
    }

    // Menampilkan form edit narasi
    public function edit(Narasi $narasi)
    {
        return view('admin.narasi.edit', compact('narasi'));
    }

    // Memperbarui data narasi
    // Memperbarui dan menyetujui hasil revisi admin
    public function update(Request $request, Narasi $narasi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'practice' => 'nullable|string',
            'cultural_term' => 'nullable|string',
            'community' => 'nullable|string',
            'people' => 'nullable|string',
            'location' => 'nullable|string',
            'time' => 'nullable|string',
            'meaning' => 'nullable|string',
        ]);

        // 1. Update data dasar di tabel narasis (jika judul/lokasi diubah)
        $narasi->update([
            'judul_narasi' => $request->name,
            'lokasi_penelitian' => $request->location,
            'status_verifikasi' => 'terverifikasi',
        ]);

        // 2. Simpan atau perbarui data final ke tabel pengetahuans (yang tampil di Knowledge Explorer)
        Pengetahuan::updateOrCreate(
            ['narasi_id' => $narasi->id],
            [
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'practice' => $request->practice ?? 'Tidak ada praktik khusus',
                'cultural_term' => $request->cultural_term ?? '-',
                'community' => $request->community ?? 'Masyarakat Lokal Bengkulu',
                'people' => $request->people ?? 'Narasumber lokal',
                'location' => $request->location ?? 'Bengkulu',
                'time' => $request->time ?? 'Turun-temurun',
                'meaning' => $request->meaning ?? 'Pelestarian nilai budaya lokal.',
            ]
        );

        return redirect()->route('admin.narasi.index')->with('success', 'Narasi dan hasil ekstraksi berhasil direvisi serta dipublikasikan.');
    }

    // Menghapus narasi
    public function destroy(Narasi $narasi)
    {
        $narasi->delete();
        return redirect()->route('admin.narasi.index')->with('success', 'Narasi berhasil dihapus.');
    }

    // Method untuk Ekstraksi Massal Menggunakan LLM Ollama (gemma:2b)
    public function ekstrakMassal()
    {
        $narasis = Narasi::all();
        $berhasil = 0;
        $kategoriList = "Ritual, Kuliner, Gotong Royong, Kesenian, Tradisi Lisan, Kerajinan";

        foreach ($narasis as $narasi) {
            $prompt = "Bertindaklah sebagai sejarawan dan ahli budaya Bengkulu yang sangat teliti. " .
                      "Analisis narasi budaya berikut dan ekstrak informasinya ke dalam format JSON yang valid (tanpa teks markdown tambahan seperti ```json) dengan atribut berikut:\n" .
                      "- name: Judul atau nama budaya yang sesuai.\n" .
                      "- category: Pilih HANYA salah satu dari kategori berikut yang paling tepat: [{$kategoriList}].\n" .
                      "- description: Deskripsi naratif yang utuh, mendalam, dan kaya makna.\n" .
                      "- practice: Praktik atau tata cara pelaksanaan budaya tersebut secara rinci (jika tidak ada, tuliskan \"Tidak ada praktik khusus\").\n" .
                      "- cultural_term: Istilah khas bahasa lokal yang digunakan (jika tidak ada, tuliskan \"-\").\n" .
                      "- community: Komunitas atau kelompok masyarakat yang menjalankan.\n" .
                      "- people: Tokoh atau perannya (misal: pemangku adat, tokoh masyarakat, dll).\n" .
                      "- location: Lokasi spesifik kejadian atau tradisi.\n" .
                      "- time: Waktu pelaksanaan.\n" .
                      "- meaning: Makna filosofis atau nilai budaya yang terkandung.\n\n" .
                      "Judul Narasi Sumber: {$narasi->judul_narasi}\n" .
                      "Isi Narasi Mentah:\n\"{$narasi->isi_narasi}\"\n\n" .
                      "Pastikan semua atribut terisi lengkap dan akurat dalam format JSON.";

            try {
                $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
                    'model' => 'gemma:2b',
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

                if ($response->successful()) {$rawContent = $response->json('response');$cleanedJson = str_replace(['```json', '```'], '', $rawContent);
                    $dataJson = json_decode(trim($cleanedJson), true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($dataJson)) {
                        Pengetahuan::updateOrCreate(
                            ['narasi_id' => $narasi->id],
                            [
                                'name' => $dataJson['name'] ?? $narasi->judul_narasi,
                                'category' => $dataJson['category'] ?? 'Tradisi Lisan',
                                'description' => $dataJson['description'] ?? $narasi->isi_narasi,
                                'practice' => $dataJson['practice'] ?? 'Tidak ada praktik khusus',
                                'cultural_term' => $dataJson['cultural_term'] ?? '-',
                                'community' => $dataJson['community'] ?? 'Masyarakat Lokal Bengkulu',
                                'people' => $dataJson['people'] ?? ($narasi->nama_sumber ?? 'Narasumber lokal'),
                                'location' => $dataJson['location'] ?? ($narasi->lokasi_penelitian ?? 'Bengkulu'),
                                'time' => $dataJson['time'] ?? 'Turun-temurun',
                                'meaning' => $dataJson['meaning'] ?? 'Pelestarian nilai budaya lokal.'
                            ]
                        );
                        $berhasil++;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()
            ->route('admin.narasi.index')
            ->with('success', "Berhasil mengekstrak {$berhasil} data narasi menggunakan LLM gemma:2b!");
    }

    // Method untuk Menerima dan Mengekstrak Semua Narasi Sekaligus via LLM
    public function terimaSemua()
    {
        $narasis = Narasi::all();$berhasil = 0;
        $kategoriList = "Ritual, Kuliner, Gotong Royong, Kesenian, Tradisi Lisan, Kerajinan";

        foreach ($narasis as $narasi) { // 1. Ubah status verifikasi narasi menjadi terverifikasi$narasi->update(['status_verifikasi' => 'terverifikasi']);

            // 2. Kirim ke Ollama untuk ekstraksi otomatis yang kaya dan lengkap
            $prompt = "Bertindaklah sebagai sejarawan dan ahli budaya Bengkulu yang sangat teliti. " .
                      "Analisis narasi budaya berikut dan ekstrak informasinya ke dalam format JSON yang valid (tanpa teks markdown tambahan seperti ```json) dengan atribut berikut:\n" .
                      "- name: Judul atau nama budaya yang sesuai.\n" .
                      "- category: Pilih HANYA salah satu dari kategori berikut yang paling tepat: [{$kategoriList}].\n" .
                      "- description: Deskripsi naratif yang utuh, mendalam, dan kaya makna.\n" .
                      "- practice: Praktik atau tata cara pelaksanaan budaya tersebut secara rinci.\n" .
                      "- cultural_term: Istilah khas bahasa lokal yang digunakan.\n" .
                      "- community: Komunitas atau kelompok masyarakat yang menjalankan.\n" .
                      "- people: Tokoh atau perannya.\n" .
                      "- location: Lokasi spesifik kejadian atau tradisi.\n" .
                      "- time: Waktu pelaksanaan.\n" .
                      "- meaning: Makna filosofis atau nilai budaya yang terkandung.\n\n" .
                      "Judul Narasi Sumber: {$narasi->judul_narasi}\n" .
                      "Isi Narasi Mentah:\n\"{$narasi->isi_narasi}\"\n\n" .
                      "Pastikan semua atribut terisi lengkap dan akurat dalam format JSON.";

            try {
                $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
                    'model' => 'gemma:2b',
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

                $dataJson = [];
                if ($response->successful()) {
                    $rawContent = $response->json('response');
                    $cleanedJson = str_replace(['```json', '```'], '', $rawContent);
                    $dataJson = json_decode(trim($cleanedJson), true);
                }

                // 3. Simpan ke tabel pengetahuans berdasarkan hasil ekstraksi LLM
                Pengetahuan::updateOrCreate(
                    ['narasi_id' => $narasi->id],
                    [
                        'name' => $dataJson['name'] ?? $narasi->judul_narasi,
                        'category' => $dataJson['category'] ?? 'Tradisi Lisan',
                        'description' => $dataJson['description'] ?? $narasi->isi_narasi,
                        'practice' => $dataJson['practice'] ?? 'Tidak ada praktik khusus',
                        'cultural_term' => $dataJson['cultural_term'] ?? '-',
                        'community' => $dataJson['community'] ?? 'Masyarakat Lokal Bengkulu',
                        'people' => $dataJson['people'] ?? ($narasi->nama_sumber ?? 'Narasumber lokal'),
                        'location' => $dataJson['location'] ?? ($narasi->lokasi_penelitian ?? 'Bengkulu'),
                        'time' => $dataJson['time'] ?? 'Turun-temurun',
                        'meaning' => $dataJson['meaning'] ?? 'Pelestarian nilai budaya lokal.'
                    ]
                );

                $berhasil++;
            } catch (\Exception $e) {
                // Fallback jika Ollama timeout / mati agar data tetap masuk
                Pengetahuan::updateOrCreate(
                    ['narasi_id' => $narasi->id],
                    [
                        'name' => $narasi->judul_narasi,
                        'category' => 'Tradisi Lisan',
                        'description' => $narasi->isi_narasi,
                        'practice' => 'Tidak ada praktik khusus',
                        'cultural_term' => '-',
                        'community' => 'Masyarakat Lokal Bengkulu',
                        'people' => $narasi->nama_sumber ?? 'Narasumber lokal',
                        'location' => $narasi->lokasi_penelitian ?? 'Bengkulu',
                        'time' => 'Turun-temurun',
                        'meaning' => 'Pelestarian nilai budaya lokal.'
                    ]
                );
                $berhasil++;
            }
        }

        return redirect()
            ->route('admin.narasi.index')
            ->with('success', "Berhasil menerima dan memproses {$berhasil} data narasi secara massal dengan ekstraksi LLM!");
    }
}