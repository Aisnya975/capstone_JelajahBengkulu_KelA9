<?php

namespace App\Http\Controllers;

use App\Models\Narasi;
use App\Models\Pengetahuan;
use App\Services\OllamaKnowledgeExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EkstraksiLlmController extends Controller
{
    public function extract(
        Narasi $narasi,
        OllamaKnowledgeExtractor $ollama
    ): RedirectResponse {
        try {
            $hasil = $ollama->extract($narasi->isi_narasi);

            $narasi->ekstraksiLlms()->create([
                'model_llm' => 'ollama-gemma:2b',
                'hasil_ekstraksi' => $hasil,
                'status_review' => 'menunggu',
            ]);

            return back()->with(
                'success',
                'Ekstraksi AI lokal (Ollama) berhasil disimpan. Silakan tinjau hasilnya.'
            );
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Ekstraksi gagal: Pastikan aplikasi Ollama di komputer sudah menyala.'
            );
        }
    }

    public function review(Narasi $narasi)
    {
        // Ambil data ekstraksi LLM terakhir untuk narasi ini
        $ekstraksi = $narasi->ekstraksiLlms()->latest()->first();

        if (!$ekstraksi) {
            return redirect()->route('admin.narasi.index')->with('error', 'Belum ada hasil ekstraksi AI untuk narasi ini.');
        }

        return view('admin.narasi.review', compact('narasi', 'ekstraksi'));
    }

    public function approve(Request $request, Narasi $narasi)
    {
        // 1. Validasi file gambar opsional yang diunggah saat review
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ekstraksi = $narasi->ekstraksiLlms()->latest()->first();

        if (!$ekstraksi) {
            return back()->with('error', 'Data ekstraksi tidak ditemukan.');
        }

        $dataAI = $ekstraksi->hasil_ekstraksi;

        // Ambil data dari input form revisi admin, atau fallback ke hasil ekstraksi AI asli
        $namaPengetahuan = $request->input('name', $dataAI['name'] ?? $narasi->judul_narasi);
        $kategoriVal = $request->input('category', $dataAI['category'] ?? 'lainnya');
        $deskripsiVal = $request->input('description', $dataAI['description'] ?? null);
        $lokasiVal = $request->input('location', $dataAI['location'] ?? $narasi->lokasi_penelitian);
        $waktuVal = $request->input('time', $dataAI['time'] ?? null);
        $maknaVal = $request->input('meaning', $dataAI['meaning'] ?? null);

        // Menangkap data array (praktik, istilah, komunitas, tokoh)
        $praktikVal = $request->input('practice', $dataAI['practice'] ?? []);
        $istilahVal = $request->input('cultural_term', $dataAI['cultural_term'] ?? []);
        $komunitasVal = $request->input('community', $dataAI['community'] ?? []);
        $tokohVal = $request->input('people', $dataAI['people'] ?? []);

        // 2. Cek apakah sudah ada data pengetahuan sebelumnya untuk narasi ini (Mencegah Duplikasi)
        $pengetahuan = Pengetahuan::where('narasi_id', $narasi->id)->first();

        // 3. Tangkap file gambar baru jika ada yang diunggah
        $imagePath = $pengetahuan ? $pengetahuan->image : null; // Pertahankan gambar lama jika ada
        
        if ($request->hasFile('image')) {
            // Hapus gambar lama di storage jika ada untuk menghemat ruang
            if ($pengetahuan && $pengetahuan->image && Storage::disk('public')->exists($pengetahuan->image)) {
                Storage::disk('public')->delete($pengetahuan->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('pengetahuan', 'public');
        }

        // Data yang akan disimpan/diperbarui
        $payload = [
            'narasi_id' => $narasi->id,
            'name' => $namaPengetahuan,
            'category' => $kategoriVal,
            'description' => $deskripsiVal,
            'location' => $lokasiVal,
            'time' => $waktuVal,
            'meaning' => $maknaVal,
            'practice' => is_string($praktikVal) ? json_decode($praktikVal, true) : $praktikVal,
            'cultural_term' => is_string($istilahVal) ? json_decode($istilahVal, true) : $istilahVal,
            'community' => is_string($komunitasVal) ? json_decode($komunitasVal, true) : $komunitasVal,
            'people' => is_string($tokohVal) ? json_decode($tokohVal, true) : $tokohVal,
            'image' => $imagePath,
        ];

        if ($pengetahuan) {
            // Jika sudah ada, cukup update (Memperbarui data tanpa membuat duplikat)
            $pengetahuan->update($payload);
        } else {
            // Jika belum ada, buat baru
            Pengetahuan::create($payload);
        }

        // Update status verifikasi narasi menjadi terverifikasi
        $narasi->update(['status_verifikasi' => 'terverifikasi']);
        $ekstraksi->update(['status_review' => 'disetujui']);

        return redirect()->route('admin.narasi.index')->with('success', 'Revisi pengetahuan budaya berhasil disetujui dan dipublikasikan!');
    }

    public function update(Request $request, Narasi $narasi)
    {
        $ekstraksi = $narasi->ekstraksiLlms()->latest()->first();

        if (!$ekstraksi) {
            return back()->with('error', 'Data ekstraksi tidak ditemukan.');
        }

        $oldData = $ekstraksi->hasil_ekstraksi;

        $dataBaru = [
            'name' => $request->input('name', $oldData['name'] ?? ''),
            'category' => $request->input('category', $oldData['category'] ?? ''),
            'description' => $request->input('description', $oldData['description'] ?? ''),
            'location' => $request->input('location', $oldData['location'] ?? ''),
            'time' => $request->input('time', $oldData['time'] ?? ''),
            'meaning' => $request->input('meaning', $oldData['meaning'] ?? ''),
            'practice' => $request->input('practice', $oldData['practice'] ?? []),
            'cultural_term' => $request->input('cultural_term', $oldData['cultural_term'] ?? []),
            'community' => $request->input('community', $oldData['community'] ?? []),
            'people' => $request->input('people', $oldData['people'] ?? []),
        ];

        $ekstraksi->update([
            'hasil_ekstraksi' => $dataBaru,
        ]);

        return back()->with('success', 'Hasil ekstraksi berhasil direvisi/diperbarui.');
    }

    public function reject(Narasi $narasi)
    {
        $ekstraksi = $narasi->ekstraksiLlms()->latest()->first();

        if ($ekstraksi) {
            $ekstraksi->update(['status_review' => 'ditolak']);
        }

        $narasi->update(['status_verifikasi' => 'ditolak']);

        return redirect()->route('admin.narasi.index')->with('success', 'Ekstraksi berhasil ditolak.');
    }
}