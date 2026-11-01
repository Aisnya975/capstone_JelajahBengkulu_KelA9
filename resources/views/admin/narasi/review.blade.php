<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tinjau & Revisi Ekstraksi AI — Jelajah Bengkulu</title>
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
        main { max-width: 900px; margin: 40px auto; padding: 0 22px; }
        .panel {
            background: var(--surface);
            padding: 28px;
            border-radius: 16px;
            border: 1px solid var(--border);
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        h1, h3 { margin-top: 0; color: var(--text-main); letter-spacing: -0.5px; }
        .muted { color: var(--text-muted); font-size: 14px; line-height: 1.6; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        select option { background: var(--surface); color: var(--text-main); }
        textarea { resize: vertical; height: 120px; }
        .btn-group { display: flex; flex-direction: column; gap: 15px; margin-top: 30px; }
        .button {
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            transition: background 0.2s, transform 0.1s;
        }
        .button:active { transform: scale(0.98); }
        .btn-primary { background: #059669; color: white; }
        .btn-primary:hover { background: #10b981; }
        .btn-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: var(--danger); color: white; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('admin.narasi.index') }}">
            <strong style="letter-spacing: 1px;">JELAJAH <span style="color: var(--teal);">BENGKULU</span></strong>
            <div style="font-size:11px; margin-top:3px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Admin Panel — Review & Revisi AI</div>
        </a>
        <a href="{{ route('admin.narasi.index') }}" style="font-size: 14px; font-weight: 600; color: var(--teal);">← Kembali ke Dashboard</a>
    </header>

    <main>
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; margin-bottom: 8px;">Tinjau & Revisi Hasil Ekstraksi AI</h1>
            <p class="muted">Narasi sumber: <strong style="color: var(--text-main);">{{ $narasi->judul_narasi }}</strong></p>
        </div>

        <div class="panel">
            <h3 style="font-size: 18px; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Informasi Narasi Asli</h3>
            <p style="margin-bottom: 10px;"><strong style="color: var(--text-muted);">Lokasi:</strong> {{ $narasi->lokasi_penelitian ?? '-' }}</p>
            <p style="margin-bottom: 8px;"><strong style="color: var(--text-muted);">Isi Narasi:</strong></p>
            <div style="background: var(--bg-deep); padding: 16px; border-radius: 10px; border: 1px solid var(--border); color: var(--text-main); font-size: 14px; line-height: 1.7;">
                {{ $narasi->isi_narasi }}
            </div>
        </div>

        <div class="panel">
            <h3 style="font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Formulir Revisi Hasil Ekstraksi <span style="font-size: 13px; color: var(--accent-gold); font-weight: normal;">(Model: {{ $ekstraksi->model_llm }})</span></h3>
            
            @php
                $data = $ekstraksi->hasil_ekstraksi;
            @endphp

            <!-- Perhatikan penambahan enctype="multipart/form-data" di sini agar file gambar bisa terkirim -->
            <form action="{{ route('admin.narasi.approve', $narasi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Judul Pengetahuan</label>
                    <input type="text" name="name" value="{{ $data['name'] ?? $narasi->judul_narasi }}" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" required>
                        @foreach(['ritual', 'tradisi_nelayan', 'kesenian', 'kuliner', 'festival', 'kerajinan', 'pengetahuan_lokal', 'lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ (isset($data['category']) && $data['category'] === $cat) ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $cat)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description">{{ $data['description'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="location" value="{{ $data['location'] ?? $narasi->lokasi_penelitian }}">
                </div>

                <div class="form-group">
                    <label>Waktu / Periode</label>
                    <input type="text" name="time" value="{{ $data['time'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Makna / Filosofi</label>
                    <textarea name="meaning">{{ $data['meaning'] ?? '' }}</textarea>
                </div>

                <!-- Input Upload Gambar yang menyatu dalam form -->
                <div class="form-group" style="margin-top: 25px; padding: 20px; background: var(--bg-deep); border: 1px dashed var(--border); border-radius: 12px;">
                    <label style="margin-bottom: 10px;">Upload Foto Dokumentasi Budaya</label>
                    <input type="file" name="image" accept="image/*" style="padding: 8px 0; border: none; background: transparent; width: 100%; color: var(--text-main);">
                    <small class="muted" style="display: block; margin-top: 8px;">Format yang didukung: JPG, PNG, JPEG (Maks. 2MB).</small>
                </div>

                <div class="btn-group">
                    <button type="submit" class="button btn-primary">
                        ✓ Simpan Revisi, Setujui & Publikasikan
                    </button>
                </div>
            </form>

            <form action="{{ route('admin.narasi.reject', $narasi->id) }}" method="POST" style="margin-top: 15px;" onsubmit="return confirm('Apakah Anda yakin ingin menolak hasil ekstraksi ini?')">
                @csrf
                <button type="submit" class="button btn-danger" style="width: 100%;">
                    ✕ Tolak Ekstraksi (Hapus / Reset)
                </button>
            </form>
        </div>
    </main>
</body>
</html>