<?php

namespace App\Http\Controllers;

use App\Models\Pengetahuan;
use Illuminate\Http\Request;

class PengetahuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengetahuan::query();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('location')) {
            $query->where(
                'location',
                'like',
                '%' . $request->input('location') . '%'
            );
        }

        $pengetahuans = $query
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $categories = Pengetahuan::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $locations = Pengetahuan::query()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('pengetahuan.index', compact(
            'pengetahuans',
            'categories',
            'locations'
        ));
    }

    public function show(Pengetahuan $pengetahuan)
    {
        $pengetahuan->load('narasi');

        return view('pengetahuan.show', compact('pengetahuan'));
    }
}