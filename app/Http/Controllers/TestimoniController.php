<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'pesan'     => 'required|string|max:1000',
            'rating'    => 'required|integer|min:1|max:5',
        ]);

        // Simpan langsung ke kolom yang tersedia di database
        $testimoni = new Testimoni();
        $testimoni->nama = $request->nama;
        $testimoni->pekerjaan = $request->pekerjaan;
        $testimoni->pesan = $request->pesan;
        $testimoni->rating = $request->rating;
        $testimoni->is_active = true;
        $testimoni->save();

        return back()->with('success', 'Terima kasih! Testimoni kamu berhasil dikirim.');
    }
}