<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Jelajah Bengkulu</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: var(--surface);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        h2 { margin-top: 0; margin-bottom: 8px; color: var(--text-main); font-size: 26px; letter-spacing: -0.5px; }
        p { color: var(--text-muted); font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        .form-group { margin-bottom: 18px; }
        label { 
            display: block; 
            font-size: 13px; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: var(--text-muted); 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
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
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 15px;
            margin-top: 10px;
            transition: background 0.2s, transform 0.1s;
        }
        button[type="submit"]:hover { background: var(--teal-hover); }
        button[type="submit"]:active { transform: scale(0.98); }
        .error { color: #f87171; font-size: 12px; margin-top: 6px; display: block; }
        .footer-link { text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted); }
        .footer-link a { color: var(--teal); font-weight: 700; text-decoration: none; transition: color 0.2s; }
        .footer-link a:hover { color: var(--teal-hover); }
    </style>
</head>
<body>
    <div class="card">
        <h2>Buat Akun Baru</h2>
        <p>Daftar untuk mulai menjelajahi arsip budaya Bengkulu.</p>

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Aisyah Azzahrah">
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@domain.com">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password">
            </div>

            <button type="submit">Daftar Sekarang</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </div>
</body>
</html>