<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Testimoni — Admin Panel</title>
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
            padding: 22px 6%; 
            background: var(--surface); 
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }
        header a { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        header a:hover { color: var(--teal); }
        main { max-width: 1150px; margin: 40px auto; padding: 0 22px; }
        .panel { 
            background: var(--surface); 
            padding: 28px; 
            border-radius: 16px; 
            border: 1px solid var(--border); 
            overflow-x: auto; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        h1 { margin-top: 0; font-size: 28px; letter-spacing: -0.5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { 
            padding: 16px 14px; 
            text-align: left; 
            border-bottom: 1px solid var(--border); 
            font-size: 14px; 
        }
        th { 
            color: var(--text-muted); 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            background: rgba(255,255,255,0.02); 
        }
        tr:hover td { background: rgba(255,255,255,0.01); }
        .btn { 
            padding: 7px 14px; 
            border-radius: 8px; 
            font-size: 12px; 
            font-weight: 600; 
            cursor: pointer; 
            text-decoration: none; 
            border: none; 
            display: inline-block;
            transition: background 0.2s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-toggle { 
            background: rgba(255, 183, 3, 0.15); 
            color: var(--accent-gold); 
            border: 1px solid rgba(255, 183, 3, 0.3); 
        }
        .btn-toggle:hover { background: var(--accent-gold); color: #173b43; }
        .btn-delete { 
            background: rgba(239, 68, 68, 0.15); 
            color: #f87171; 
            border: 1px solid rgba(239, 68, 68, 0.3); 
        }
        .btn-delete:hover { background: var(--danger); color: white; }
        .alert { 
            padding: 14px 18px; 
            margin-bottom: 25px; 
            border-radius: 10px; 
            background: rgba(13, 89, 51, 0.2); 
            border: 1px solid rgba(13, 89, 51, 0.4);
            color: #34d399; 
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="{{ route('admin.narasi.index') }}">
                <strong style="letter-spacing: 1px;">JELAJAH <span style="color: var(--teal);">BENGKULU</span></strong>
                <div style="font-size:11px; margin-top:3px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Admin Panel - Kelola Testimoni</div>
            </a>
        </div>
        <div>
            <a href="{{ route('admin.narasi.index') }}" style="font-size: 14px; font-weight: 600; color: var(--teal);">← Kembali ke Dashboard</a>
        </div>
    </header>

    <main>
        <div style="margin-bottom: 30px;">
            <h1>Manajemen Testimoni Pengunjung</h1>
            <p style="color: var(--text-muted); margin: 6px 0 0 0; font-size: 14px;">Setujui atau hapus ulasan yang dikirimkan oleh pengguna pada halaman detail budaya.</p>
        </div>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <section class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Pekerjaan</th>
                        <th>Pesan</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonis as $t)
                        <tr>
                            <td><strong style="color: var(--text-main);">{{ $t->nama }}</strong></td>
                            <td style="color: var(--text-muted);">{{ $t->pekerjaan ?? '-' }}</td>
                            <td style="color: var(--text-main); max-width: 300px;">{{ Str::limit($t->pesan, 60) }}</td>
                            <td style="color: var(--accent-gold); font-weight: 600;">⭐ {{ $t->rating }} / 5</td>
                            <td>
                                @if($t->is_active)
                                    <span style="color: #34d399; font-weight: 600; background: rgba(52, 211, 153, 0.1); padding: 4px 10px; border-radius: 20px; font-size: 11px; border: 1px solid rgba(52, 211, 153, 0.2);">Aktif (Tampil)</span>
                                @else
                                    <span style="color: var(--text-muted); font-weight: 600; background: rgba(255, 255, 255, 0.05); padding: 4px 10px; border-radius: 20px; font-size: 11px; border: 1px solid var(--border);">Disembunyikan</span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <!-- Tombol Toggle Status -->
                                <form action="{{ route('admin.testimonis.toggle', $t->id) }}" method="POST" style="display:inline; margin-right: 6px;">
                                    @csrf
                                    <button type="submit" class="btn btn-toggle">
                                        {{ $t->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.testimonis.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                Belum ada testimoni yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 25px;">
                {{ $testimonis->links() }}
            </div>
        </section>
    </main>
</body>
</html>