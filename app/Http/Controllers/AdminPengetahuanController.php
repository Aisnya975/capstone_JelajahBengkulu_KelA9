<?php

namespace App\Http\Controllers;

use App\Models\Pengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPengetahuanController extends Controller
{
    // Menampilkan daftar semua pengetahuan yang sudah dipublikasikan (dengan fitur pencarian)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil data pengetahuan dengan filter pencarian dan pagination
        $pengetahuans = Pengetahuan::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('category', 'like', "%{$search}%")
                             ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        // Ambil daftar kategori dan lokasi unik untuk filter/pencarian jika dibutuhkan di view
        $categories = Pengetahuan::whereNotNull('category')->distinct()->pluck('category');
        $locations = Pengetahuan::whereNotNull('location')->distinct()->pluck('location');

        return view('admin.pengetahuan.index', compact('pengetahuans', 'categories', 'locations', 'search'));
    }

    // Menampilkan form edit pengetahuan
    public function edit($id)
    {
        $pengetahuan = Pengetahuan::findOrFail($id);
        return view('admin.pengetahuan.edit', compact('pengetahuan'));
    }

    // Memperbarui data pengetahuan (mencegah duplikasi data)
    public function update(Request $request, $id)
    {
        $pengetahuan = Pengetahuan::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            'time_period' => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek jika ada unggahan gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada di storage
            if ($pengetahuan->image && Storage::disk('public')->exists($pengetahuan->image)) {
                Storage::disk('public')->delete($pengetahuan->image);
            }
            // Simpan gambar baru
            $validated['image'] = $request->file('image')->store('pengetahuan', 'public');
        }

        $pengetahuan->update($validated);

        return redirect()->route('admin.pengetahuan.manage')->with('success', 'Data pengetahuan budaya berhasil diperbarui.');
    }

    // Menghapus pengetahuan dari publik
    public function destroy(Pengetahuan $pengetahuan)
    {
        // Hapus file gambar terkait dari storage jika ada
        if ($pengetahuan->image && Storage::disk('public')->exists($pengetahuan->image)) {
            Storage::disk('public')->delete($pengetahuan->image);
        }

        $pengetahuan->delete();
        return redirect()->route('admin.pengetahuan.manage')->with('success', 'Pengetahuan budaya berhasil dihapus dari publik.');
    }
}