<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    // Menampilkan daftar testimoni di admin
    public function index()
    {
        $testimonis = Testimoni::latest()->paginate(10);
        return view('admin.testimonis.index', compact('testimonis'));
    }

    // Mengubah status aktif / non-aktif testimoni
    public function toggle($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->is_active = !$testimoni->is_active;
        $testimoni->save();

        return back()->with('success', 'Status testimoni berhasil diperbarui.');
    }

    // Menghapus testimoni
    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}