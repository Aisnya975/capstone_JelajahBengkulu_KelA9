<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pengetahuan->name ?? $pengetahuan->judul_pengetahuan }} — Jelajah Bengkulu</title>

    <style>
        :root {
            --bg-deep: #070b0d;
            --surface: #101a1e;
            --surface-card: #142228;
            --border: #1d333b;
            --text-main: #f7f5ed;
            --text-muted: #8ca1a3;
            --teal: #187b78;
            --teal-hover: #1f9e9a;
            --accent-gold: #ffb703;
            --danger: #ef4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg-deep);
            color: var(--text-main);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 7%;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand span {
            color: var(--teal);
        }

        .back {
            color: var(--teal);
            font-size: 14px;
            font-weight: 700;
            transition: color 0.2s;
        }

        .back:hover {
            color: var(--teal-hover);
        }

        main {
            max-width: 900px;
            margin: 45px auto;
            padding: 0 24px;
        }

        .header-section {
            margin-bottom: 30px;
        }

        .eyebrow {
            color: var(--teal);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        h1 {
            margin: 0 0 15px 0;
            font-size: clamp(28px, 5vw, 42px);
            line-height: 1.2;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .tag {
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(24, 123, 120, 0.15);
            border: 1px solid rgba(24, 123, 120, 0.3);
            color: #5eead4;
            font-size: 13px;
            font-weight: 600;
        }

        /* Gaya Tampilan Gambar Utama */
        .culture-image-container {
            margin-bottom: 35px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--surface);
            max-height: 420px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        .culture-image-container img {
            width: 100%;
            height: 100%;
            max-height: 420px;
            object-fit: cover;
            display: block;
        }

        /* Layout Kotak per Kotak */
        .card-grid {
            display: grid;
            gap: 24px;
        }

        .card-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: border-color 0.2s;
        }

        .card-box:hover {
            border-color: rgba(24, 123, 120, 0.5);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 14px;
            font-size: 18px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }

        .content {
            color: var(--text-muted);
            line-height: 1.8;
            font-size: 15px;
            white-space: pre-line;
        }

        .content strong {
            color: var(--text-main);
        }

        .empty-text {
            color: #64748b;
            font-style: italic;
            font-size: 14px;
        }

        /* Form & Testimoni Style */
        input[type="text"], textarea, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-deep);
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(24, 123, 120, 0.2);
        }

        select option {
            background: var(--surface);
            color: var(--text-main);
        }

        footer {
            margin-top: 80px;
            padding: 40px 7%;
            background: var(--surface);
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        footer strong {
            color: var(--text-main);
            font-size: 16px;
        }

        @media (max-width: 500px) {
            nav { padding: 18px 5%; }
            main { margin: 25px auto; padding: 0 14px; }
            .card-box { padding: 20px; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('home') }}" class="brand">
            JELAJAH<span>BENGKULU.</span>
        </a>
        <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.pengetahuan.manage') : route('home') }}" class="back">
            ← Kembali
        </a>
    </nav>

    <main>
        @php
            $namaPengetahuan = $pengetahuan->name ?? $pengetahuan->judul_pengetahuan;
            $kategoriVal = $pengetahuan->category ?? $pengetahuan->kategori;
            $deskripsiVal = $pengetahuan->description ?? $pengetahuan->deskripsi;
            $lokasiVal = $pengetahuan->location ?? $pengetahuan->lokasi;
            $waktuVal = $pengetahuan->time ?? $pengetahuan->waktu;
            $maknaVal = $pengetahuan->meaning ?? $pengetahuan->makna;

            $praktikVal = $pengetahuan->practice ?? $pengetahuan->praktik;
            $istilahVal = $pengetahuan->cultural_term ?? $pengetahuan->istilah_budaya;
            $komunitasVal = $pengetahuan->community ?? $pengetahuan->komunitas;
            $tokohVal = $pengetahuan->people ?? $pengetahuan->tokoh;
            
            $imageVal = $pengetahuan->image ?? $pengetahuan->foto ?? $pengetahuan->photo ?? $pengetahuan->gambar ?? null;
        @endphp

        <!-- 1. Hook & Judul -->
        <div class="header-section">
            <div class="eyebrow">Arsip Pengetahuan Budaya</div>
            <h1>{{ $namaPengetahuan }}</h1>
            
            <div class="tag-list">
                <span class="tag">📁 {{ $kategoriVal ? ucfirst(str_replace('_', ' ', $kategoriVal)) : 'Umum' }}</span>
                <span class="tag">📍 {{ $lokasiVal ?? 'Bengkulu' }}</span>
            </div>
        </div>

        <!-- 2. Tampilan Dokumentasi Gambar Utama -->
        <div class="culture-image-container">
            @if($imageVal)
                <img src="{{ asset('storage/' . $imageVal) }}" alt="{{ $namaPengetahuan }}">
            @else
                <div style="width: 100%; height: 280px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #122026; color: var(--teal); font-weight: 600;">
                    <span style="font-size: 38px; margin-bottom: 8px;">🏛️</span>
                    <span>Dokumentasi Budaya Bengkulu</span>
                </div>
            @endif
        </div>

        <div class="card-grid">

            <!-- 3. Deskripsi -->
            <div class="card-box">
                <h2>📝 Deskripsi</h2>
                <div class="content">
                    @if($deskripsiVal)
                        {{ $deskripsiVal }}
                    @else
                        <span class="empty-text">Belum ada deskripsi untuk pengetahuan ini.</span>
                    @endif
                </div>
            </div>

            <!-- 4. Ringkasan -->
            <div class="card-box">
                <h2>📌 Ringkasan Budaya</h2>
                <div class="content">
                    Kategori ini merangkum warisan tradisi dan kearifan lokal yang ada di wilayah <strong>{{ $lokasiVal ?? 'Bengkulu' }}</strong>, dilestarikan oleh masyarakat secara turun-temurun.
                </div>
            </div>

            <!-- 5. Praktik Budaya -->
            <div class="card-box">
                <h2>🌿 Praktik Budaya</h2>
                <div class="content">
                    @if(!empty($praktikVal) && is_iterable($praktikVal))
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($praktikVal as $p)
                                <li>{{ is_array($p) ? (implode(' — ', $p)) : $p }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="empty-text">{{ $praktikVal ?? 'Tidak ada data praktik budaya khusus yang tercatat.' }}</span>
                    @endif
                </div>
            </div>

            <!-- 6. Sumber Pengetahuan -->
            <div class="card-box">
                <h2>📚 Sumber Pengetahuan</h2>
                <div class="content">
                    @if(isset($pengetahuan->narasi) && $pengetahuan->narasi)
                        <strong>Judul Narasi:</strong> {{ $pengetahuan->narasi->judul_narasi }}<br>
                        <strong>Jenis Sumber:</strong> {{ ucfirst(str_replace('_', ' ', $pengetahuan->narasi->jenis_sumber ?? 'Dokumen')) }}<br>
                        <strong>Nama Sumber:</strong> {{ $pengetahuan->narasi->nama_sumber ?? 'Masyarakat Lokal' }}
                    @else
                        <span class="empty-text">Sumber arsip kurasi langsung melalui sistem ekstraksi cerdas.</span>
                    @endif
                </div>
            </div>

            <!-- 7. Istilah Budaya -->
            <div class="card-box">
                <h2>💬 Istilah Budaya</h2>
                <div class="content">
                    @if(!empty($istilahVal))
                        @if(is_array($istilahVal))
                            {{ implode(', ', $istilahVal) }}
                        @else
                            {{ $istilahVal }}
                        @endif
                    @else
                        <span class="empty-text">Tidak ada istilah budaya khusus yang tercatat.</span>
                    @endif
                </div>
            </div>

            <!-- 8. Tokoh / Pihak -->
            <div class="card-box">
                <h2>👤 Tokoh / Pihak Terkait</h2>
                <div class="content">
                    @if(!empty($tokohVal) && is_iterable($tokohVal))
                        @foreach($tokohVal as $t)
                            <div>{{ is_array($t) ? ($t['name'] ?? json_encode($t)) : $t }} @if(is_array($t) && isset($t['role'])) ({{ $t['role'] }}) @endif</div>
                        @endforeach
                    @else
                        <span class="empty-text">{{ $tokohVal ?? 'Melibatkan masyarakat umum dan pemangku adat setempat.' }}</span>
                    @endif
                </div>
            </div>

            <!-- 9. Lokasi -->
            <div class="card-box">
                <h2>📍 Lokasi</h2>
                <div class="content">
                    @if($lokasiVal)
                        {{ $lokasiVal }}
                    @else
                        <span class="empty-text">Wilayah Provinsi Bengkulu dan sekitarnya.</span>
                    @endif
                </div>
            </div>

            <!-- 10. Waktu Pelaksanaan -->
            <div class="card-box">
                <h2>⏳ Waktu Pelaksanaan</h2>
                <div class="content">
                    @if($waktuVal)
                        {{ $waktuVal }}
                    @else
                        <span class="empty-text">Dilakukan secara berkala atau sepanjang tahun.</span>
                    @endif
                </div>
            </div>

            <!-- 11. Makna Budaya -->
            <div class="card-box">
                <h2>💡 Makna Budaya</h2>
                <div class="content">
                    @if($maknaVal)
                        {{ $maknaVal }}
                    @else
                        <span class="empty-text">Sebagai bentuk pelestarian nilai luhur dan identitas lokal masyarakat.</span>
                    @endif
                </div>
            </div>

            <!-- BAGIAN TESTIMONI -->
            <div class="card-box" style="margin-top: 20px;">
                <h2 style="margin-bottom: 20px;">💬 Apa Kata Mereka tentang Budaya Bengkulu?</h2>
                
                @if(session('success'))
                    <div style="background: rgba(13, 89, 51, 0.2); border: 1px solid rgba(13, 89, 51, 0.4); color: #34d399; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 14px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('testimoni.store') }}" method="POST" style="margin-bottom: 35px; display: grid; gap: 15px;">
                    @csrf
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Tulis nama kamu...">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Pekerjaan / Status (Opsional)</label>
                        <input type="text" name="pekerjaan" placeholder="Misal: Mahasiswa / Peneliti Budaya">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Pesan / Testimoni</label>
                        <textarea name="pesan" rows="3" required placeholder="Tuliskan kesan atau ulasanmu..."></textarea>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Rating</label>
                        <select name="rating">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Baik)</option>
                            <option value="4">⭐⭐⭐⭐ (Baik)</option>
                            <option value="3">⭐⭐⭐ (Cukup)</option>
                        </select>
                    </div>
                    <button type="submit" style="background: var(--teal); color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: background 0.2s;">
                        Kirim Testimoni 🚀
                    </button>
                </form>

                <hr style="border: none; border-top: 1px solid var(--border); margin: 30px 0;">

                <div style="display: grid; gap: 15px;">
                    @php
                        $testimonis = \App\Models\Testimoni::where('is_active', true)->latest()->get();
                    @endphp

                    @forelse($testimonis as $t)
                        <div style="background: var(--bg-deep); padding: 18px; border-radius: 12px; border: 1px solid var(--border);">
                            <p style="margin: 0 0 12px 0; font-style: italic; color: var(--text-main); font-size: 14px; line-height: 1.6;">"{{ $t->pesan }}"</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                                <div>
                                    <strong style="color: var(--text-main);">{{ $t->nama }}</strong> 
                                    <span style="color: var(--text-muted);">({{ $t->pekerjaan ?? 'Pengunjung' }})</span>
                                </div>
                                <div style="color: var(--accent-gold); font-weight: 600;">
                                    {!! str_repeat('★', $t->rating) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 20px 0;">Belum ada testimoni yang ditampilkan. Jadilah yang pertama mengisi!</p>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <footer>
        <strong>JELAJAH BENGKULU</strong>
        <br>
        Dokumentasi pengetahuan budaya lokal Bengkulu.
    </footer>

</body>
</html>