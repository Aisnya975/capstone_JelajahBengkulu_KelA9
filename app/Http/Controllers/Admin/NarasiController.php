<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use Illuminate\Http\Request;

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
    public function update(Request $request, Narasi $narasi)
    {
        $request->validate([
            'judul_narasi' => 'required|string|max:255',
            'isi_narasi' => 'required|string',
            'lokasi_penelitian' => 'nullable|string|max:255',
        ]);

        $narasi->update([
            'judul_narasi' => $request->judul_narasi,
            'isi_narasi' => $request->isi_narasi,
            'lokasi_penelitian' => $request->lokasi_penelitian,
        ]);

        return redirect()->route('admin.narasi.index')->with('success', 'Narasi berhasil diperbarui.');
    }

    // Menghapus narasi
    public function destroy(Narasi $narasi)
    {
        $narasi->delete();
        return redirect()->route('admin.narasi.index')->with('success', 'Narasi berhasil dihapus.');
    }

    // Method untuk Ekstraksi Massal Seluruh Narasi ke Tabel Pengetahuan
    public function ekstrakMassal()
    {
        $narasis = Narasi::all();
        $berhasil = 0;

        foreach ($narasis as $narasi) {
            // Cek apakah narasi ini sudah terekstrak sebelumnya
            $cekSudahAda = Pengetahuan::where('narasi_id', $narasi->id)->exists();

            if (!$cekSudahAda) {
                // Simpan ke tabel pengetahuans sebagai data hasil ekstraksi
                Pengetahuan::create([
                    'narasi_id' => $narasi->id,
                    'name' => $narasi->judul_narasi,
                    'category' => 'Tradisi Lokal', // Kategori default atau sesuaikan kolom
                    'description' => $narasi->isi_narasi,
                    'location' => $narasi->lokasi_penelitian ?? 'Bengkulu',
                ]);
                
                $berhasil++;
            }
        }

        return redirect()
            ->route('admin.narasi.index')
            ->with('success', "Berhasil memproses dan mengekstrak $berhasil data narasi secara massal!");
    }
    // Method untuk Menerima dan Mengekstrak Semua Narasi Sekaligus
    public function terimaSemua()
    {
        $narasis = Narasi::all();
        $berhasil = 0;

        foreach ($narasis as $narasi) {
            // 1. Ubah status verifikasi narasi menjadi terverifikasi/disetujui
            $narasi->update(['status_verifikasi' => 'terverifikasi']);

            // 2. Masukkan atau perbarui data ke tabel pengetahuans agar tidak [NULL]
            \App\Models\Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'name' => $narasi->judul_narasi,
                    'category' => 'Tradisi & Budaya Lokal',
                    'description' => $narasi->isi_narasi,
                    'practice' => 'Praktik atau ritual adat berdasarkan narasi budaya.',
                    'cultural_term' => 'Istilah Budaya Pesisir',
                    'community' => 'Masyarakat Lokal Bengkulu',
                    'people' => $narasi->nama_sumber ?? 'Tokoh Adat / Narasumber',
                    'location' => $narasi->lokasi_penelitian ?? 'Bengkulu',
                    'time' => $narasi->tanggal_pengumpulan ? \Carbon\Carbon::parse($narasi->tanggal_pengumpulan)->format('Y') : 'Turun-temurun',
                    'meaning' => 'Pelestarian nilai luhur budaya lokal.'
                ]
            );

            $berhasil++;
        }

        return redirect()
            ->route('admin.narasi.index')
            ->with('success', "Berhasil menerima dan memproses {$berhasil} data narasi secara massal!");
    }
}