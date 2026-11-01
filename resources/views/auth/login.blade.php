<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Jelajah Bengkulu</title>
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
        .brand-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--teal);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .brand-link:hover { color: var(--teal-hover); }
        h2 { margin-top: 0; margin-bottom: 8px; color: var(--text-main); font-size: 26px; letter-spacing: -0.5px; }
        p { color: var(--text-muted); font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            font-size: 13px; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: var(--text-muted); 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
        input[type="email"], input[type="password"] {
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
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 25px;
        }
        .remember-row input {
            width: 16px;
            height: 16px;
            accent-color: var(--teal);
            cursor: pointer;
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
        <a href="{{ route('home') }}" class="brand-link">← Kembali ke Beranda</a>
        <h2>Masuk ke Akun</h2>
        <p>Silakan masukkan email dan password untuk melanjutkan akses sistem.</p>

        @if(session('status'))
            <div style="background: rgba(13, 89, 51, 0.2); border: 1px solid rgba(13, 89, 51, 0.4); color: #34d399; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; font-weight: 600;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@jelajahbengkulu.test">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin-bottom: 0; font-weight: normal; cursor: pointer; color: var(--text-main);">Ingat saya</label>
            </div>

            <button type="submit">Masuk</button>
        </form>

        <div class="footer-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>
</body>
</html>