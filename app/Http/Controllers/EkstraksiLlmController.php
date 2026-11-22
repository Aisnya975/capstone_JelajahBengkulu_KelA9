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
        $imagePath = $pengetahuan ? $pengetahuan->image : null; 
        
        if ($request->hasFile('image')) {
            if ($pengetahuan && $pengetahuan->image && Storage::disk('public')->exists($pengetahuan->image)) {
                Storage::disk('public')->delete($pengetahuan->image);
            }
            $imagePath = $request->file('image')->store('pengetahuan', 'public');
        }

        // Data yang akan disimpan/diperbarui (Pastikan array di-json_encode agar tidak error)
        $payload = [
            'narasi_id'     => $narasi->id,
            'name'          => $namaPengetahuan,
            'category'      => $kategoriVal,
            'description'   => is_array($deskripsiVal) ? json_encode($deskripsiVal) : $deskripsiVal,
            'location'      => $lokasiVal,
            'time'          => is_array($waktuVal) ? json_encode($waktuVal) : $waktuVal,
            'meaning'       => is_array($maknaVal) ? json_encode($maknaVal) : $maknaVal,
            'practice'      => is_array($praktikVal) ? json_encode($praktikVal) : (is_string($praktikVal) ? $praktikVal : json_encode($praktikVal)),
            'cultural_term' => is_array($istilahVal) ? json_encode($istilahVal) : (is_string($istilahVal) ? $istilahVal : json_encode($istilahVal)),
            'community'     => is_array($komunitasVal) ? json_encode($komunitasVal) : (is_string($komunitasVal) ? $komunitasVal : json_encode($komunitasVal)),
            'people'        => is_array($tokohVal) ? json_encode($tokohVal) : (is_string($tokohVal) ? $tokohVal : json_encode($tokohVal)),
            'image'         => $imagePath,
        ];

        if ($pengetahuan) {
            $pengetahuan->update($payload);
        } else {
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

        // Ambil input dan pastikan jika berupa array, diubah ke string atau di-handle dengan aman
        $dataBaru = [
            'name'          => $request->input('name', $oldData['name'] ?? ''),
            'category'      => $request->input('category', $oldData['category'] ?? ''),
            'description'   => $request->input('description', $oldData['description'] ?? ''),
            'location'      => $request->input('location', $oldData['location'] ?? ''),
            'time'          => is_array($request->input('time')) ? implode(', ', $request->input('time')) : $request->input('time', $oldData['time'] ?? ''),
            'meaning'       => is_array($request->input('meaning')) ? implode(', ', $request->input('meaning')) : $request->input('meaning', $oldData['meaning'] ?? ''),
            'practice'      => $request->input('practice', $oldData['practice'] ?? []),
            'cultural_term' => $request->input('cultural_term', $oldData['cultural_term'] ?? []),
            'community'     => $request->input('community', $oldData['community'] ?? []),
            'people'        => $request->input('people', $oldData['people'] ?? []),
        ];

        // Update data pada tabel ekstraksi_llms
        $ekstraksi->update([
            'hasil_ekstraksi' => $dataBaru,
        ]);

        // Sinkronkan juga ke tabel pengetahuans agar data di website ikut terbarui tanpa error
        \App\Models\Pengetahuan::updateOrCreate(
            ['narasi_id' => $narasi->id],
            [
                'ekstraksi_llm_id' => $ekstraksi->id,
                'name'          => $dataBaru['name'],
                'category'      => $dataBaru['category'],
                'description'   => $dataBaru['description'],
                'location'      => $dataBaru['location'], // Diperbaiki dari $dataBatu menjadi $dataBaru
                'time'          => is_array($dataBaru['time']) ? json_encode($dataBaru['time']) : $dataBaru['time'],
                'meaning'       => is_array($dataBaru['meaning']) ? json_encode($dataBaru['meaning']) : $dataBaru['meaning'],
                'practice'      => is_array($dataBaru['practice']) ? json_encode($dataBaru['practice']) : $dataBaru['practice'],
                'cultural_term' => is_array($dataBaru['cultural_term']) ? json_encode($dataBaru['cultural_term']) : $dataBaru['cultural_term'],
                'community'     => is_array($dataBaru['community']) ? json_encode($dataBaru['community']) : $dataBaru['community'],
                'people'        => is_array($dataBaru['people']) ? json_encode($dataBaru['people']) : $dataBaru['people'],
            ]
        );

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