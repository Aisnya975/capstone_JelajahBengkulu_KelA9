<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Narasi — Jelajah Bengkulu</title>
    <style>
        :root {
            --bg-deep: #090e10;
            --surface: #121e22;
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
            padding: 22px 6%;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        header a { color: var(--text-main); text-decoration: none; font-weight: 700; letter-spacing: 1px; }
        header a:hover { color: var(--teal); }
        main { max-width: 850px; margin: 40px auto; padding: 0 20px; }
        .form-card {
            padding: clamp(22px, 5vw, 38px);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        .muted { color: var(--text-muted); line-height: 1.7; font-size: 14px; }
        .field { margin-top: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-deep);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }
        select option { background: var(--surface); color: var(--text-main); }
        textarea { min-height: 150px; resize: vertical; }
        .error { color: #f87171; font-size: 13px; margin-top: 6px; }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }
        button, .back {
            padding: 12px 20px;
            border: 0;
            border-radius: 10px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.1s;
        }
        button:active, .back:active { transform: scale(0.98); }
        button { background: var(--teal); color: white; }
        button:hover { background: var(--teal-hover); }
        .back { background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--border); }
        .back:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('admin.narasi.index') }}">
            JELAJAH <span style="color: var(--teal);">BENGKULU</span> / ADMIN
        </a>
    </header>

    <main>
        <section class="form-card">
            <h1 style="margin-top: 0; font-size: 28px; letter-spacing: -0.5px;">Tambah Narasi Budaya</h1>
            <p class="muted">
                Masukkan narasi sebagai sumber pengetahuan budaya. Setelah disimpan, narasi dapat diproses melalui AI.
            </p>

            <form action="{{ route('admin.narasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="judul_narasi">Judul narasi *</label>
                    <input
                        id="judul_narasi"
                        name="judul_narasi"
                        value="{{ old('judul_narasi') }}"
                        required
                        placeholder="Masukkan judul narasi budaya..."
                    >
                    @error('judul_narasi')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="isi_narasi">Isi narasi *</label>
                    <textarea
                        id="isi_narasi"
                        name="isi_narasi"
                        required
                        placeholder="Tuliskan detail atau transkrip narasi budaya di sini..."
                    >{{ old('isi_narasi') }}</textarea>
                    @error('isi_narasi')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="jenis_sumber">Jenis sumber *</label>
                    <select id="jenis_sumber" name="jenis_sumber" required>
                        <option value="">Pilih jenis sumber</option>
                        @foreach ([
                            'wawancara',
                            'observasi',
                            'dokumen',
                            'artikel',
                            'sumber_lainnya'
                        ] as $jenis)
                            <option
                                value="{{ $jenis }}"
                                @selected(old('jenis_sumber') === $jenis)
                            >
                                {{ ucfirst(str_replace('_', ' ', $jenis)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_sumber')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="nama_sumber">Nama sumber (opsional)</label>
                    <input
                        id="nama_sumber"
                        name="nama_sumber"
                        value="{{ old('nama_sumber') }}"
                        placeholder="Contoh: Narasumber / Pemangku adat"
                    >
                </div>

                <div class="field">
                    <label for="jenis_informan">Jenis informan (opsional)</label>
                    <input
                        id="jenis_informan"
                        name="jenis_informan"
                        value="{{ old('jenis_informan') }}"
                        placeholder="Contoh: nelayan, tokoh adat"
                    >
                </div>

                <div class="field">
                    <label for="lokasi_penelitian">Lokasi penelitian</label>
                    <input
                        id="lokasi_penelitian"
                        name="lokasi_penelitian"
                        value="{{ old('lokasi_penelitian') }}"
                        placeholder="Contoh: pesisir Kota Bengkulu"
                    >
                </div>

                <div class="field">
                    <label for="tanggal_pengumpulan">Tanggal pengumpulan</label>
                    <input
                        id="tanggal_pengumpulan"
                        type="date"
                        name="tanggal_pengumpulan"
                        value="{{ old('tanggal_pengumpulan') }}"
                    >
                </div>

                <div class="field">
                    <label for="metode_pengumpulan">Metode pengumpulan</label>
                    <input
                        id="metode_pengumpulan"
                        name="metode_pengumpulan"
                        value="{{ old('metode_pengumpulan') }}"
                        placeholder="Contoh: wawancara semi-terstruktur"
                    >
                </div>

                <div class="field">
                    <label>Foto / Dokumentasi Budaya</label>
                    <input type="file" name="image" accept="image/*" style="padding: 10px; background: var(--bg-deep);">
                </div>

                <div class="actions">
                    <button type="submit">Simpan Narasi</button>
                    <a class="back" href="{{ route('admin.narasi.index') }}">
                        Kembali
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>