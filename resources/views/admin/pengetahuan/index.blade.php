<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Publik — Admin Jelajah Bengkulu</title>
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
        a { color: inherit; text-decoration: none; transition: color 0.2s; }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            gap: 20px; padding: 22px 7%; background: var(--surface); flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }
        .brand { font-size: 20px; font-weight: 800; color: var(--text-main); }
        .brand span { color: var(--teal); }
        .nav-actions { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 7%; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        h1 { margin: 0; font-size: 28px; letter-spacing: -0.5px; }
        
        /* Style untuk Form Search Bar */
        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            background: var(--surface);
            padding: 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .search-form input {
            flex: 1;
            padding: 12px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-form input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        .search-form button {
            background: var(--teal);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-form button:hover { background: var(--teal-hover); }
        .btn-reset {
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            border: 1px solid var(--border);
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-reset:hover { color: var(--text-main); background: rgba(255,255,255,0.1); }

        .btn {
            background: var(--teal); color: white; padding: 8px 14px;
            border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; display: inline-block;
            transition: background 0.2s, transform 0.1s;
        }
        .btn:hover { background: var(--teal-hover); }
        .btn:active { transform: scale(0.98); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: var(--danger); color: white; }
        
        .table-card { 
            background: var(--surface); 
            border-radius: 16px; 
            border: 1px solid var(--border); 
            overflow: hidden; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4); 
        }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th, td { padding: 18px 20px; border-bottom: 1px solid var(--border); }
        th { background: rgba(255,255,255,0.02); color: var(--text-muted); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        tr:hover td { background: rgba(255,255,255,0.01); }
        tr:last-child td { border-bottom: none; }
        .alert-success {
            background: rgba(13, 89, 51, 0.2); border: 1px solid rgba(13, 89, 51, 0.4); color: #34d399; padding: 14px 18px;
            border-radius: 10px; margin-bottom: 25px; font-weight: 600; font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR KHUSUS ADMIN -->
    <nav class="navbar">
        <a href="{{ route('admin.narasi.index') }}" class="brand">
            JELAJAH<span>BENGKULU</span> <span style="font-size: 11px; color: var(--teal); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">(Admin Panel)</span>
        </a>

        <div class="nav-actions">
            <a href="{{ route('admin.users.index') }}" style="font-weight: 600; color: var(--accent-gold);">👥 Manajemen Akun</a>
            <a href="{{ route('admin.pengetahuan.manage') }}" style="font-weight: 600; color: #81ecec;">📚 Kelola Publik</a>
            <a href="{{ route('admin.testimonis.index') }}" style="color: var(--accent-gold); font-weight: 600;">💬 Kelola Testimoni</a>
            <a href="{{ route('home') }}" style="font-weight: 600;">Lihat Website ↗</a>
            
            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger" style="padding: 7px 14px; font-size: 12px;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="admin-header">
            <div>
                <h1>Kelola Pengetahuan Publik</h1>
                <p style="color: var(--text-muted); margin: 6px 0 0 0; font-size: 14px;">Daftar seluruh pengetahuan budaya yang tampil di beranda utama untuk dikelola atau dihapus.</p>
            </div>
            <span style="font-weight: 700; color: var(--teal); background: rgba(24,123,120,0.1); padding: 8px 14px; border-radius: 8px; border: 1px solid rgba(24,123,120,0.3);">Total: {{ $pengetahuans->total() }} data</span>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form Kotak Pencarian (Search Bar) -->
        <form action="{{ route('admin.pengetahuan.manage') }}" method="GET" class="search-form">
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}" 
                placeholder="Cari berdasarkan judul pengetahuan, kategori, atau lokasi..."
            >
            <button type="submit">Cari ↗</button>
            @if(request('search'))
                <a href="{{ route('admin.pengetahuan.manage') }}" class="btn-reset">Reset</a>
            @endif
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Judul Pengetahuan</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengetahuans as $item)
                    <tr>
                        <td><strong style="color: var(--text-main);">{{ $item->name }}</strong></td>
                        <td style="color: var(--text-muted);">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</td>
                        <td style="color: var(--text-muted);">{{ $item->location ?? '-' }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('pengetahuan.show', $item->id) }}" target="_blank" class="btn" style="background: rgba(24,123,120,0.2); color: #5eead4; border: 1px solid rgba(24,123,120,0.4); padding: 6px 12px; margin-right: 6px;">Lihat</a>
                            <a href="{{ route('admin.pengetahuan.edit', $item->id) }}" class="btn" style="background: rgba(255,183,3,0.15); color: var(--accent-gold); border: 1px solid rgba(255,183,3,0.3); padding: 6px 12px; margin-right: 6px;">Edit</a>
                            
                            <form action="{{ route('admin.pengetahuan.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus pengetahuan ini dari publik?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            @if(request('search'))
                                Tidak ditemukan data pengetahuan yang cocok dengan kata kunci "{{ request('search') }}".
                            @else
                                Belum ada pengetahuan yang dipublikasikan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 25px;">
            {{ $pengetahuans->links() }}
        </div>
    </div>

</body>
</html>