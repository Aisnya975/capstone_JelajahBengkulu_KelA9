<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengetahuan — Jelajah Bengkulu</title>
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
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg-deep);
            color: var(--text-main);
            font-family: "Segoe UI", Arial, sans-serif;
        }
        header {
            padding: 22px 6%;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        header a { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        header a:hover { color: var(--teal); }
        main { max-width: 850px; margin: 40px auto; padding: 0 22px; }
        .panel {
            background: var(--surface);
            padding: 35px;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        h1 { margin-top: 0; font-size: 28px; letter-spacing: -0.5px; }
        .muted { color: var(--text-muted); font-size: 14px; line-height: 1.6; }
        .form-group { margin-bottom: 22px; }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        textarea { resize: vertical; min-height: 140px; }
        .image-preview-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .image-preview-box img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border);
        }
        .btn {
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: var(--teal); color: white; }
        .btn-primary:hover { background: var(--teal-hover); }
        .btn-secondary { background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--border); margin-right: 10px; }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('admin.pengetahuan.manage') }}">
            <strong style="letter-spacing: 1px;">JELAJAH <span style="color: var(--teal);">BENGKULU</span></strong>
            <div style="font-size:11px; margin-top:3px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Admin Panel — Edit Pengetahuan</div>
        </a>
    </header>

    <main>
        <div class="panel">
            <h1>Edit Pengetahuan Budaya</h1>
            <p class="muted" style="margin-bottom: 25px;">Perbarui data pengetahuan budaya yang sudah tayang tanpa membuat duplikat baru.</p>

            <form action="{{ route('admin.pengetahuan.update', $pengetahuan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Judul Pengetahuan</label>
                    <input type="text" name="title" value="{{ old('title', $pengetahuan->title ?? $pengetahuan->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $pengetahuan->category ?? $pengetahuan->kategori) }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="6" required>{{ old('description', $pengetahuan->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $pengetahuan->location ?? $pengetahuan->lokasi) }}">
                </div>

                <div class="form-group">
                    <label>Periode / Waktu</label>
                    <input type="text" name="time_period" value="{{ old('time_period', $pengetahuan->time_period) }}">
                </div>

                <div class="form-group">
                    <label>Foto Dokumentasi Saat Ini</label>
                    @if(!empty($pengetahuan->image))
                        <div class="image-preview-box">
                            <img src="{{ asset('storage/' . $pengetahuan->image) }}" alt="Preview">
                            <span style="font-size: 13px; color: var(--text-muted);">Foto saat ini tersimpan di server. Unggah file baru di bawah jika ingin menggantinya.</span>
                        </div>
                    @else
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 10px;">Belum ada foto yang diunggah.</p>
                    @endif
                    
                    <label style="margin-top: 15px;">Ganti Foto (Opsional)</label>
                    <input type="file" name="image" accept="image/*" style="padding: 10px; background: var(--bg-deep);">
                </div>

                <div style="margin-top: 30px; display: flex; align-items: center;">
                    <a href="{{ route('admin.pengetahuan.manage') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>