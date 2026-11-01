<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'pesan' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Mengisi kedua kolom (pesan & isi_testimoni) sekaligus untuk mencegah error database
        $testimoni = new Testimoni();
        $testimoni->nama = $request->nama;
        $testimoni->pekerjaan = $request->pekerjaan;
        $testimoni->pesan = $request->pesan;         // Mengisi kolom 'pesan' yang dicari database
        $testimoni->isi_testimoni = $request->pesan; // Mengisi kolom 'isi_testimoni' cadangan
        $testimoni->rating = $request->rating;
        $testimoni->is_active = true;
        $testimoni->save();

        return back()->with('success', 'Terima kasih! Testimoni kamu berhasil dikirim.');
    }
}