<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah Budaya Bengkulu</title>
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

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 22px 7%;
            background: rgba(16, 26, 30, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand span {
            color: var(--teal);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .nav-link:hover {
            color: var(--text-main);
        }

        .btn-auth {
            background: var(--teal);
            color: white;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: background 0.2s;
        }

        .btn-auth:hover {
            background: var(--teal-hover);
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        /* Hero Section dengan Slideshow Background Otomatis */
        .hero {
            position: relative;
            padding: 90px 7% 80px;
            border-bottom: 1px solid var(--border);
            overflow: hidden;
            min-height: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 1;
        }

        .hero-slide.active {
            opacity: 1;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(7, 11, 13, 0.75) 0%, rgba(7, 11, 13, 0.95) 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
        }

        .eyebrow {
            color: var(--teal);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        /* Tombol Badge Interaktif Menuju Detail Budaya Aktif */
        .hero-featured-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            padding: 6px 14px;
            background: rgba(24, 123, 120, 0.2);
            border: 1px solid rgba(24, 123, 120, 0.4);
            border-radius: 20px;
            color: #5eead4;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }

        .hero-featured-badge:hover {
            background: rgba(24, 123, 120, 0.4);
            color: white;
        }

        h1 {
            max-width: 800px;
            margin: 18px 0;
            font-size: clamp(36px, 5.5vw, 62px);
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--text-main);
        }

        .hero p {
            max-width: 650px;
            color: var(--text-muted);
            line-height: 1.8;
            font-size: 16px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 12px;
            max-width: 1050px;
            margin-top: 30px;
            padding: 14px;
            background: rgba(16, 26, 30, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        input, select, button {
            min-width: 0;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-deep);
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--teal);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%238ca1a3%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 10px auto;
            padding-right: 35px;
            cursor: pointer;
        }

        select option {
            background: var(--surface);
            color: var(--text-main);
        }

        button[type="submit"] {
            background: var(--teal);
            color: white;
            border: 0;
            cursor: pointer;
            font-weight: 700;
            padding: 0 24px;
            transition: background 0.2s, transform 0.1s;
        }

        button[type="submit"]:hover {
            background: var(--teal-hover);
        }

        button[type="submit"]:active {
            transform: scale(0.98);
        }

        .section {
            padding: 60px 7% 90px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 35px;
        }

        h2 {
            margin: 8px 0;
            font-size: clamp(28px, 4vw, 38px);
            letter-spacing: -0.5px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 25px;
        }

        .card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
            border-color: var(--teal);
        }

        .card-img-wrapper {
            width: 100%;
            height: 200px;
            background: #192a30;
            overflow: hidden;
            position: relative;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s;
        }

        .card:hover .card-img-wrapper img {
            transform: scale(1.05);
        }

        .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 24px;
        }

        .card-category {
            align-self: flex-start;
            padding: 6px 12px;
            background: rgba(24, 123, 120, 0.15);
            border: 1px solid rgba(24, 123, 120, 0.3);
            border-radius: 20px;
            color: #5eead4;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card h3 {
            margin: 16px 0 12px;
            font-size: 20px;
            color: var(--text-main);
            letter-spacing: -0.3px;
        }

        .card p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .card-location {
            margin-top: auto;
            padding-top: 15px;
            font-size: 12px;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
        }

        .read-more {
            margin-top: 16px;
            color: var(--teal);
            font-size: 14px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .read-more:hover {
            color: var(--teal-hover);
        }

        .empty {
            grid-column: 1 / -1;
            padding: 60px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            text-align: center;
        }

        .pagination {
            margin-top: 35px;
        }

        .footer {
            padding: 40px 7%;
            background: var(--surface);
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .footer strong {
            color: var(--text-main);
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        @media (max-width: 850px) {
            .search-form {
                grid-template-columns: 1fr 1fr;
            }

            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .navbar {
                padding: 18px 5%;
            }

            .hero, .section {
                padding-left: 5%;
                padding-right: 5%;
            }

            .search-form, .grid {
                grid-template-columns: 1fr;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }
            /* Styling untuk Merapikan Paginasi di Bagian Bawah */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 40px;
        }

        .pagination nav {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
        }

        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            color: var(--text-muted);
        }

        .pagination span, .pagination a {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
            padding: 8px 14px !important;
            border-radius: 8px !important;
            font-size: 14px;
            font-weight: 600;
        }

        .pagination span[aria-current="page"] {
            background: var(--teal) !important;
            border-color: var(--teal) !important;
            color: white !important;
        }

        .pagination a:hover {
            border-color: var(--teal) !important;
            color: #5eead4 !important;
        }
        }
    </style>
</head>
<body>

    <!-- NAVBAR UTAMA PUBLIK -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="brand">
            JELAJAH<span>BENGKULU</span>
        </a>

        <div class="nav-actions">
            <a href="#koleksi" class="nav-link">Jelajahi Budaya ↗</a>

            @auth
                <span style="font-size: 14px; font-weight: 600; color: #5eead4;">
                    Halo, {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn-auth">Daftar</a>
            @endauth
        </div>
    </nav>

    <!-- HERO SECTION DENGAN SLIDESHOW OTOMATIS & TOMBOL KE DETAIL -->
    @php
        $heroItems = \App\Models\Pengetahuan::whereNotNull('image')->inRandomOrder()->take(5)->get();
    @endphp

    <header class="hero">
        @if($heroItems->count() > 0)
            @foreach($heroItems as $index => $item)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ asset('storage/' . $item->image) }}');"
                     data-title="{{ $item->name ?? $item->judul_pengetahuan }}"
                     data-url="{{ route('pengetahuan.show', $item) }}">
                </div>
            @endforeach
        @else
            <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?q=80&w=1600&auto=format&fit=crop');"></div>
        @endif

        <div class="hero-overlay"></div>

        <div class="hero-content">
            @if($heroItems->count() > 0)
                <a id="heroDetailBtn" href="{{ route('pengetahuan.show', $heroItems->first()) }}" class="hero-featured-badge">
                    ✨ Sedang Ditampilkan: <span id="heroTitleText">{{ $heroItems->first()->name ?? $heroItems->first()->judul_pengetahuan }}</span> →
                </a>
            @else
                <div class="eyebrow">Warisan • Cerita • Masyarakat</div>
            @endif

            <h1>Menjelajahi cerita di balik budaya Bengkulu.</h1>

            <p>
                Temukan tradisi, pengetahuan lokal, kesenian, dan kehidupan
                masyarakat pesisir Bengkulu melalui narasi budaya yang
                dihimpun dan didokumentasikan secara terstruktur.
            </p>

            <form action="{{ route('home') }}" method="GET" class="search-form">
                <input
                    type="search"
                    name="search"
                    placeholder="Cari pengetahuan budaya..."
                    value="{{ request('search') }}"
                >

                <select name="category">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category }}"
                            @selected(request('category') === $category)
                        >
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>

                <select name="location">
                    <option value="">Semua lokasi</option>
                    @foreach ($locations as $location)
                        <option
                            value="{{ $location }}"
                            @selected(request('location') === $location)
                        >
                            {{ $location }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Cari ↗</button>
            </form>
        </div>
    </header>

    <!-- KOLEKSI ARSIP / GRID SECTION -->
    <main class="section" id="koleksi">
        <div class="section-heading">
            <div>
                <div class="eyebrow">Arsip pengetahuan lokal</div>
                <h2>Jelajahi budaya</h2>
                <p style="color: var(--text-muted); margin: 6px 0 0 0; font-size: 15px;">
                    Cerita lokal, makna, dan praktik budaya masyarakat.
                </p>
            </div>

            <span style="background: var(--surface); border: 1px solid var(--border); padding: 8px 16px; border-radius: 8px; font-weight: 600; color: #5eead4;">
                {{ $pengetahuans->total() }} pengetahuan
            </span>

        </div>

        <div class="grid">
            @forelse ($pengetahuans as $pengetahuan)
                <article class="card">
                    <div class="card-img-wrapper">
                        @if(!empty($pengetahuan->image))
                            <img src="{{ asset('storage/' . $pengetahuan->image) }}" alt="{{ $pengetahuan->name }}">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #122026; color: var(--teal); font-weight: 600; font-size: 13px;">
                                <span style="font-size: 28px; margin-bottom: 6px;">🏛️</span>
                                <span>Jelajah Bengkulu</span>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <span class="card-category">
                            {{ ucfirst(str_replace('_', ' ', $pengetahuan->category ?? 'Budaya lokal')) }}
                        </span>

                        <h3>{{ $pengetahuan->name }}</h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit(
                                $pengetahuan->description ?? 'Belum ada deskripsi untuk pengetahuan ini.',
                                140
                            ) }}
                        </p>

                        <div class="card-location">
                            📍 {{ $pengetahuan->location ?: 'Bengkulu' }}
                        </div>

                        <a
                            class="read-more"
                            href="{{ route('pengetahuan.show', $pengetahuan) }}"
                        >
                            Baca pengetahuan →
                        </a>
                    </div>
                </article>
            @empty
                <div class="empty">
                    <h3 style="margin-top: 0; color: var(--text-main);">Belum ada pengetahuan untuk ditampilkan</h3>
                    <p style="color: var(--text-muted); margin-bottom: 0;">
                        Data akan muncul di sini setelah pengetahuan tersimpan di database.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $pengetahuans->links() }}
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <strong>JELAJAH BENGKULU</strong>

    </footer>

    <!-- SCRIPT SLIDESHOW HERO & TOMBOL DETAIL -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length <= 1) return;

            let currentIndex = 0;
            const titleText = document.getElementById('heroTitleText');
            const detailBtn = document.getElementById('heroDetailBtn');

            setInterval(() => {
                slides[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].classList.add('active');

                if (titleText && detailBtn) {
                    const activeSlide = slides[currentIndex];
                    const newTitle = activeSlide.getAttribute('data-title');
                    const newUrl = activeSlide.getAttribute('data-url');
                    
                    if (newTitle) titleText.textContent = newTitle;
                    if (newUrl) detailBtn.href = newUrl;
                }
            }, 5000);
        });
    </script>
</body>
</html>