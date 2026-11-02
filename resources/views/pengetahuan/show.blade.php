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
            padding: 22px 7%;
            background: rgba(16, 26, 30, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
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

        .article-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 24px;
        }

        .article-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px auto;
        }

        .eyebrow {
            color: var(--teal);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        h1 {
            margin: 0 0 20px 0;
            font-size: clamp(32px, 5vw, 50px);
            line-height: 1.15;
            letter-spacing: -1px;
            color: var(--text-main);
        }

        .tag-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .tag {
            padding: 6px 16px;
            border-radius: 20px;
            background: rgba(24, 123, 120, 0.15);
            border: 1px solid rgba(24, 123, 120, 0.3);
            color: #5eead4;
            font-size: 13px;
            font-weight: 600;
        }

        .featured-image {
            width: 100%;
            height: 480px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--surface);
            margin-bottom: 50px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.6);
        }

        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .editorial-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .main-story {
            display: grid;
            gap: 30px;
        }

        .story-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
        }

        h3 {
            font-size: 18px;
            color: var(--text-main);
            margin: 0 0 14px 0;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        .story-content {
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.8;
            white-space: pre-line;
        }

        .story-content strong {
            color: var(--text-main);
        }

        .sidebar-meta {
            display: grid;
            gap: 20px;
            position: sticky;
            top: 100px;
        }

        .meta-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
        }

        .meta-title {
            font-size: 12px;
            font-weight: 800;
            color: var(--teal);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .meta-value {
            font-size: 14px;
            color: var(--text-main);
            font-weight: 600;
            line-height: 1.6;
        }

        .testimonial-section {
            margin-top: 60px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-deep);
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--teal);
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

        @media (max-width: 900px) {
            .editorial-grid {
                grid-template-columns: 1fr;
            }
            .sidebar-meta {
                position: static;
            }
            .featured-image {
                height: 320px;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('home') }}" class="brand">
            JELAJAH<span>BENGKULU</span>
        </a>
        <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.pengetahuan.manage') : route('home') }}" class="back">
            ← Kembali ke Beranda
        </a>
    </nav>

    <main class="article-container">
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

        <!-- Header Artikel -->
        <div class="article-header">
            <div class="eyebrow">Arsip Pengetahuan Budaya Lokal</div>
            <h1>{{ $namaPengetahuan }}</h1>
            
            <div class="tag-list">
                <span class="tag">📁 {{ $kategoriVal ? ucfirst(str_replace('_', ' ', $kategoriVal)) : 'Umum' }}</span>
                <span class="tag">📍 {{ $lokasiVal ?? 'Bengkulu' }}</span>
            </div>
        </div>

        <!-- Gambar Utama -->
        <div class="featured-image">
            @if($imageVal)
                <img src="{{ asset('storage/' . $imageVal) }}" alt="{{ $namaPengetahuan }}">
            @else
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #122026; color: var(--teal); font-weight: 600;">
                    <span style="font-size: 42px; margin-bottom: 10px;">🏛️</span>
                    <span>Dokumentasi Budaya Bengkulu</span>
                </div>
            @endif
        </div>

        <!-- Layout Kolom Editorial Lengkap -->
        <div class="editorial-grid">
            
            <!-- Kolom Kiri: Seluruh Konten Utama -->
            <div class="main-story">
                
                <!-- 1. Deskripsi -->
                <div class="story-box">
                    <h3>📝 Deskripsi & Narasi</h3>
                    <div class="story-content">
                        @if($deskripsiVal)
                            {{ $deskripsiVal }}
                        @else
                            <span style="color: #64748b; font-style: italic;">Belum ada deskripsi mendalam untuk pengetahuan budaya ini.</span>
                        @endif
                    </div>
                </div>

                <!-- 2. Makna Budaya -->
                <div class="story-box">
                    <h3>💡 Makna & Filosofi Budaya</h3>
                    <div class="story-content">
                        @if($maknaVal)
                            {{ $maknaVal }}
                        @else
                            <span style="color: #64748b; font-style: italic;">Sebagai bentuk pelestarian nilai luhur dan identitas lokal masyarakat.</span>
                        @endif
                    </div>
                </div>

                <!-- 3. Praktik Budaya -->
                <div class="story-box">
                    <h3>🌿 Praktik & Tradisi Budaya</h3>
                    <div class="story-content">
                        @if(!empty($praktikVal))
                            @if(is_iterable($praktikVal))
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($praktikVal as $p)
                                        <li>{{ is_array($p) ? (implode(' — ', $p)) : $p }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $praktikVal }}
                            @endif
                        @else
                            <span style="color: #64748b; font-style: italic;">Tidak ada data praktik budaya khusus yang tercatat.</span>
                        @endif
                    </div>
                </div>

                <!-- 4. Sumber Pengetahuan -->
                <div class="story-box">
                    <h3>📚 Sumber & Asal Usul Arsip</h3>
                    <div class="story-content">
                        @if(isset($pengetahuan->narasi) && $pengetahuan->narasi)
                            <strong>Judul Narasi:</strong> {{ $pengetahuan->narasi->judul_narasi }}<br>
                            <strong>Jenis Sumber:</strong> {{ ucfirst(str_replace('_', ' ', $pengetahuan->narasi->jenis_sumber ?? 'Dokumen')) }}<br>
                            <strong>Nama Sumber:</strong> {{ $pengetahuan->narasi->nama_sumber ?? 'Masyarakat Lokal' }}
                        @else
                            Kurasi data langsung dari dokumentasi arsip lokal komunitas masyarakat Bengkulu.
                        @endif
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Sidebar Informasi Lengkap -->
            <div class="sidebar-meta">
                <div class="meta-card">
                    <div class="meta-title">Lokasi Wilayah</div>
                    <div class="meta-value">📍 {{ $lokasiVal ?? 'Provinsi Bengkulu' }}</div>
                </div>

                <div class="meta-card">
                    <div class="meta-title">Waktu Pelaksanaan</div>
                    <div class="meta-value">⏳ {{ $waktuVal ?? 'Sepanjang Tahun / Berkala' }}</div>
                </div>

                <div class="meta-card">
                    <div class="meta-title">Istilah Khas Budaya</div>
                    <div class="meta-value">
                        @if(!empty($istilahVal))
                            @if(is_array($istilahVal))
                                {{ implode(', ', $istilahVal) }}
                            @else
                                {{ $istilahVal }}
                            @endif
                        @else
                            <span style="color: #64748b; font-style: italic;">Tidak ada istilah khusus.</span>
                        @endif
                    </div>
                </div>

                <div class="meta-card">
                    <div class="meta-title">Komunitas Terkait</div>
                    <div class="meta-value">
                        {{ $komunitasVal ?? 'Masyarakat Adat & Lokal Bengkulu' }}
                    </div>
                </div>

                <div class="meta-card">
                    <div class="meta-title">Tokoh / Pihak Terkait</div>
                    <div class="meta-value">
                        @if(!empty($tokohVal))
                            @if(is_iterable($tokohVal))
                                @foreach($tokohVal as $t)
                                    <div>{{ is_array($t) ? ($t['name'] ?? json_encode($t)) : $t }}</div>
                                @endforeach
                            @else
                                {{ $tokohVal }}
                            @endif
                        @else
                            Melibatkan pemangku adat dan masyarakat setempat.
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Bagian Testimoni Estetik -->
        <div class="testimonial-section">
            <h3 style="margin-bottom: 25px;">💬 Ruang Diskusi & Ulasan Pengunjung</h3>
            
            @if(session('success'))
                <div style="background: rgba(13, 89, 51, 0.2); border: 1px solid rgba(13, 89, 51, 0.4); color: #34d399; padding: 14px; border-radius: 10px; margin-bottom: 25px; font-weight: 600; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('testimoni.store') }}" method="POST" style="margin-bottom: 40px; display: grid; gap: 18px;">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Tulis nama kamu...">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Pekerjaan / Status</label>
                        <input type="text" name="pekerjaan" placeholder="Misal: Mahasiswa / Peneliti">
                    </div>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Ulasan / Kesan Pesan</label>
                    <textarea name="pesan" rows="3" required placeholder="Bagikan tanggapanmu mengenai warisan budaya ini..."></textarea>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="flex-grow: 1; max-width: 250px;">
                        <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Rating</label>
                        <select name="rating">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Menginspirasi)</option>
                            <option value="4">⭐⭐⭐⭐ (Baik)</option>
                            <option value="3">⭐⭐⭐ (Cukup)</option>
                        </select>
                    </div>
                    <button type="submit" style="background: var(--teal); color: white; border: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: background 0.2s; align-self: flex-end;">
                        Kirim Ulasan 🚀
                    </button>
                </div>
            </form>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 35px 0;">

            <div style="display: grid; gap: 16px;">
                @php
                    $testimonis = \App\Models\Testimoni::where('is_active', true)->latest()->get();
                @endphp

                @forelse($testimonis as $t)
                    <div style="background: var(--bg-deep); padding: 20px; border-radius: 12px; border: 1px solid var(--border);">
                        <p style="margin: 0 0 12px 0; font-style: italic; color: var(--text-main); font-size: 15px; line-height: 1.6;">"{{ $t->pesan }}"</p>
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
                    <p style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 20px 0;">Belum ada ulasan yang ditampilkan. Jadilah yang pertama memberikan kesan!</p>
                @endforelse
            </div>
        </div>

    </main>

    <footer>
        <strong>JELAJAH BENGKULU</strong>
        <br>
        Knowledge Explorer Budaya Pesisir Bengkulu
    </footer>

</body>
</html>