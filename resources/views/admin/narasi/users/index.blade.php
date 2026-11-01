<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Akun Admin — Jelajah Bengkulu</title>
    <style>
        :root {
            --bg-deep: #090e10;
            --surface: #121e22;
            --surface-hover: #162428;
            --border: #1e3338;
            --text-main: #f7f5ed;
            --text-muted: #8ca1a3;
            --teal: #187b78;
            --teal-hover: #1f9e9a;
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
        main { max-width: 1000px; margin: 40px auto; padding: 0 22px; }
        .panel {
            background: var(--surface);
            padding: 28px;
            border-radius: 16px;
            border: 1px solid var(--border);
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        h1, h3 { margin-top: 0; color: var(--text-main); letter-spacing: -0.5px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        .button {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s, transform 0.1s;
        }
        .button:active { transform: scale(0.98); }
        .btn-primary { background: var(--teal); color: white; }
        .btn-primary:hover { background: var(--teal-hover); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 6px 14px; font-size: 12px; }
        .btn-danger:hover { background: var(--danger); color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid var(--border); font-size: 14px; }
        th { background: rgba(255,255,255,0.02); color: var(--text-muted); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        tr:hover td { background: rgba(255,255,255,0.01); }
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 25px; font-size: 14px; border: 1px solid transparent; }
        .alert-success { background: rgba(13, 89, 51, 0.2); border-color: rgba(13, 89, 51, 0.4); color: #34d399; }
        .alert-error { background: rgba(155, 28, 28, 0.2); border-color: rgba(155, 28, 28, 0.4); color: #f87171; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('admin.narasi.index') }}">
            <strong style="letter-spacing: 1px;">JELAJAH <span style="color: var(--teal);">BENGKULU</span></strong>
            <div style="font-size:11px; margin-top:3px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Admin Panel — Manajemen Akun</div>
        </a>
        <a href="{{ route('admin.narasi.index') }}" style="font-size: 14px; font-weight: 600; color: var(--teal);">← Dashboard Narasi</a>
    </header>

    <main>
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; margin-bottom: 8px;">Manajemen Akun Administrator</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 15px;">Kelola daftar pengguna yang memiliki akses penuh ke panel admin sistem.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Form Tambah Akun Baru -->
        <div class="panel">
            <h3 style="font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">+ Tambah Akun Admin Baru</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Aisyah Azzahrah">
                </div>
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" required placeholder="admin@jelajahbengkulu.test">
                </div>
                <div class="form-group">
                    <label>Kata Sandi (Password)</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter">
                </div>
                <button type="submit" class="button btn-primary">+ Simpan Akun Baru</button>
            </form>
        </div>

        <!-- Tabel Daftar Akun -->
        <div class="panel">
            <h3 style="font-size: 18px; margin-bottom: 10px;">Daftar Pengguna Terdaftar</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Dibuat</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td><strong style="color: var(--text-main);">{{ $u->name }}</strong></td>
                            <td style="color: var(--text-muted);">{{ $u->email }}</td>
                            <td style="color: var(--text-muted);">{{ $u->created_at->format('d M Y') }}</td>
                            <td style="text-align: right;">
                                @if(auth()->id() !== $u->id)
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button btn-danger">Hapus</button>
                                    </form>
                                @else
                                    <span style="font-size: 12px; color: var(--text-muted); font-style: italic; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 4px;">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 25px;">
                {{ $users->links() }}
            </div>
        </div>
    </main>
</body>
</html>