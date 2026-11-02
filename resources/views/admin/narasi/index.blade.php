<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin — Jelajah Bengkulu</title>
    <style>
        :root {
            --bg-deep: #090e10;
            --surface: #121e22;
            --border: #1e3338;
            --text-main: #f7f5ed;
            --text-muted: #8ca1a3;
            --teal: #187b78;
            --teal-hover: #1f9e9a;
            --accent-gold: #ffb703;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg-deep);
            color: var(--text-main);
            font-family: "Segoe UI", Arial, sans-serif;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 22px 6%;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }
        header a { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        header a:hover { color: var(--teal); }
        main { max-width: 1150px; margin: 40px auto; padding: 0 22px; }
        .eyebrow {
            color: var(--teal);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        h1 { margin-bottom: 8px; color: var(--text-main); letter-spacing: -0.5px; }
        .muted { color: var(--text-muted); }
        .actions { margin: 25px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .button {
            display: inline-block;
            padding: 12px 18px;
            background: var(--teal);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: background 0.2s, transform 0.1s;
        }
        .button:hover { background: var(--teal-hover); }
        .button:active { transform: scale(0.98); }
        .search-form {
            display: flex;
            gap: 10px;
            flex: 1;
            max-width: 500px;
        }
        .search-form input {
            flex: 1;
            padding: 11px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
        }
        .search-form input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        .search-form button {
            padding: 11px 18px;
            background: var(--surface);
            color: var(--text-main);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .search-form button:hover { border-color: var(--teal); }
        .btn-reset {
            padding: 11px 16px;
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .btn-reset:hover { color: var(--text-main); background: rgba(255,255,255,0.1); }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin: 25px 0;
        }
        .stat, .panel {
            padding: 24px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        .stat strong {
            display: block;
            margin-top: 12px;
            font-size: 32px;
            color: var(--text-main);
        }
        .panel { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            padding: 16px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        th { color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 1px; background: rgba(255,255,255,0.02); }
        tr:hover td { background: rgba(255,255,255,0.01); }
        .status {
            display: inline-block;
            padding: 6px 10px;
            background: rgba(255, 183, 3, 0.15);
            color: var(--accent-gold);
            border: 1px solid rgba(255, 183, 3, 0.3);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .alert {
            padding: 14px 18px;
            margin-bottom: 25px;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid transparent;
        }
/* --- PERBAIKAN TOTAL: SEMBUNYIKAN SEMUA SVG DI DALAM NAVIGASI HALAMAN --- */
        nav[role="navigation"], .flex.items-center.justify-between, nav div {
            /* Menyesuaikan kontainer navigasi */
        }
        
        /* Menyembunyikan seluruh ikon SVG (termasuk panah ungu) di dalam area navigasi pagination */
        nav[role="navigation"] svg {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Merapikan kotak nomor halaman agar konsisten dengan tema gelap */
        nav[role="navigation"] span[aria-current="page"] span {
            background: var(--teal) !important;
            border-color: var(--teal) !important;
            color: white !important;
            padding: 8px 14px !important;
            border-radius: 8px !important;
        }

        nav[role="navigation"] a, nav[role="navigation"] span {
            background: var(--bg-deep) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
            padding: 8px 14px !important;
            border-radius: 8px !important;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        nav[role="navigation"] a:hover {
            border-color: var(--teal) !important;
            color: #5eead4 !important;
            background: rgba(24, 123, 120, 0.2) !important;
        }
        @media(max-width: 768px) {
            .stats { grid-template-columns: 1fr; }
            header { align-items: flex-start; flex-direction: column; }
            .actions { flex-direction: column; align-items: stretch; }
            .search-form { max-width: 100%; }
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="{{ route('admin.narasi.index') }}">
                <strong style="letter-spacing: 1px;">JELAJAH <span style="color: var(--teal);">BENGKULU.</span></strong>
                <div style="font-size:11px; margin-top:3px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Admin Panel</div>
            </a>
        </div>
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('admin.users.index') }}" style="color: var(--accent-gold); font-weight: 600;">👥 Manajemen Akun</a>
            <a href="{{ route('admin.testimonis.index') }}" style="color: var(--accent-gold); font-weight: 600;">💬 Kelola Testimoni</a>
            <a href="{{ route('admin.pengetahuan.manage') }}" style="color: #81ecec; font-weight: 600;">📚 Kelola Publik</a>
            <a href="{{ route('home') }}" style="font-weight: 600;">Lihat Website ↗</a>

            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" style="background: rgba(155, 28, 28, 0.2); color: #f87171; border: 1px solid rgba(155, 28, 28, 0.4); padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main>
        <div class="eyebrow">Pusat pengelolaan data</div>
        <h1 style="font-size: 32px;">Dashboard Admin</h1>
        <p class="muted">
            Kelola narasi budaya lokal sebagai sumber ekstraksi pengetahuan AI.
        </p>

        @if (session('success'))
            <div class="alert" style="background: rgba(13, 89, 51, 0.2); border-color: rgba(13, 89, 51, 0.4); color: #34d399;">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert" style="background: rgba(155, 28, 28, 0.2); border-color: rgba(155, 28, 28, 0.4); color: #f87171;">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert" style="background: rgba(155, 28, 28, 0.2); border-color: rgba(155, 28, 28, 0.4); color: #f87171;">
                Ada data yang perlu diperiksa sebelum disimpan.
            </div>
        @endif

        <div class="actions">
            <a class="button" href="{{ route('admin.narasi.create') }}">
                + Tambah Narasi
            </a>

            <form action="{{ route('admin.narasi.index') }}" method="GET" class="search-form">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul narasi atau lokasi...">
                <button type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.narasi.index') }}" class="btn-reset">Reset</a>
                @endif
            </form>
        </div>

        <section class="stats">
            <div class="stat">
                <span class="muted" style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600;">Total narasi</span>
                <strong>{{ $totalNarasi }}</strong>
            </div>
            <div class="stat">
                <span class="muted" style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600;">Menunggu verifikasi</span>
                <strong>{{ $menunggu }}</strong>
            </div>
            <div class="stat">
                <span class="muted" style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600;">Terverifikasi</span>
                <strong>{{ $terverifikasi }}</strong>
            </div>
        </section>

        <section class="panel">
            <h2 style="margin-top: 0; font-size: 20px; margin-bottom: 20px;">Data Narasi Budaya</h2>

            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi AI</th>
                    </tr>
                </thead>
                <tbody>
    @forelse ($narasis as $narasi)
        @php
            $sudahTerekstrak = \App\Models\Pengetahuan::where('narasi_id', $narasi->id)->exists() 
                               || method_exists($narasi, 'ekstraksiLlms') && $narasi->ekstraksiLlms()->exists();
        @endphp
        <tr>
            <td><strong style="color: var(--text-main);">{{ $narasi->judul_narasi }}</strong></td>
            <td style="color: var(--text-muted);">{{ $narasi->lokasi_penelitian ?? '-' }}</td>
            <td style="color: var(--text-muted);">
                {{ $narasi->tanggal_pengumpulan ? \Carbon\Carbon::parse($narasi->tanggal_pengumpulan)->format('d M Y') : '-' }}
            </td>
            <td>
                <span class="status">
                    {{ ucfirst($narasi->status_verifikasi) }}
                </span>
            </td>
            <td style="text-align: right; white-space: nowrap;">
                @if(!$sudahTerekstrak)
                    <form action="{{ route('admin.narasi.extract', $narasi->id) }}" method="POST" style="display: inline-block; margin-right: 5px;">
                        @csrf
                        <button type="submit" style="background: var(--teal); color: white; border: none; padding: 7px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600;">
                            ⚡ Ekstrak AI
                        </button>
                    </form>
                @endif

                @if($sudahTerekstrak)
                    <a href="{{ route('admin.narasi.review', $narasi->id) }}" style="display: inline-block; background: #2b6cb0; color: white; padding: 7px 14px; border-radius: 8px; font-size: 12px; text-decoration: none; font-weight: 600;">
                        🔍 Tinjau Hasil
                    </a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                @if(request('search'))
                    Pencarian untuk "{{ request('search') }}" tidak ditemukan.
                @else
                    Belum ada narasi. Silakan tambahkan narasi pertama.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>
            </table>

            <div style="margin-top:25px">
                {{ $narasis->links() }}
            </div>
        </section>
    </main>

    <!-- Script Pembersih Total SVG Paginasi Laravel -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Menargetkan semua SVG atau elemen path yang ada di dalam paginasi
            const elementsToRemove = document.querySelectorAll('.pagination svg, .pagination path, .pagination polygon');
            elementsToRemove.forEach(el => el.remove());
        });
    </script>
</body>
</html>