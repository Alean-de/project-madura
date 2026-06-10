<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.header')
    <style>
        /* 🔥 CUSTOM STYLE INVENTORIKU - SPLIT BLUE LIGHT MODE */
        body {
            background-color: #f8f9fa !important; /* Latar luar tetep cerah bersih Dro */
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .login-card {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            overflow: hidden; /* Biar ujung background biru di atas tetep melengkung rapi gess */
        }
        /* 🔥 KOTAK BANNER BIRU UNTUK LOGO INVENTORIKU */
        .brand-banner {
            background-color:#17354D !important; /* Biru murni premium */
            padding: 24px;
            text-align: center;
        }
        .logo-branding {
            color: #ffffff !important; /* "Inventori" MENYALA PUTIH MURNI, AMAN DRO! */
            font-weight: 800;
            font-size: 26px;
            letter-spacing: -1px;
            margin: 0;
        }
        .logo-branding span {
            color: #f2cb05 !important; /* "Ku" MENYALA KUNING MUSTARD */
        }
        .brand-subtitle {
            color: rgba(255, 255, 255, 0.75) !important;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 0;
        }
        .login-title {
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .form-label-custom {
            color: #6c757d;
            font-size: 11px;
            font-weight: 600;
            text-uppercase: true;
            letter-spacing: 0.5px;
        }
        .form-control-custom {
            background-color: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            color: #212529 !important;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
        }
        .form-control-custom:focus {
            border-color: #17354D !important; /* Fokus ikut warna biru biar matching Dro */
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
        }
        .btn-adddawn {
            background-color: #17354D !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 11px;
            transition: all 0.2s ease;
        }
        .btn-adddawn:hover {
            background-color: #17354D !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .text-link {
            color: #0d6efd !important;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .text-link:hover {
            color: #0b5ed7 !important;
            text-decoration: underline !important;
        }
        .form-check-input-custom:checked {
            background-color: #17354D !important;
            border-color: #17354D !important;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100 px-3">
        <div class="card login-card shadow-sm p-0 w-100" style="max-width: 380px;">
            
            <div class="brand-banner">
                <div class="logo-branding">
                    <i class="bi bi-box-seam-fill me-2" style="color: #f2cb05;"></i>Inventori<span>Ku</span>
                </div>
                <p class="brand-subtitle">Pusat Kendali Manajemen InventoriKu</p>
            </div>

            <div class="p-4 pt-3">
                <h5 class="login-title text-center mb-3">Sign In</h5>

                @if(session('error'))
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 small p-2 rounded-3 text-center mb-3">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('plogin') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label form-label-custom mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email" placeholder="admin@gmail.com" class="form-control form-control-custom shadow-none" required autocomplete="email" autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label form-label-custom mb-1">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="form-control form-control-custom shadow-none" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check m-0">
                            <input type="checkbox" name="remember" class="form-check-input form-check-input-custom shadow-none" id="remember">
                            <label class="form-check-label text-muted small user-select-none" for="remember">Ingat Saya</label>
                        </div>
                        <a href="/forgot-password" class="text-link text-decoration-none small">Lupa sandi?</a>
                    </div>

                    <button type="submit" class="btn btn-adddawn w-100 mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-shield-lock-fill"></i> Masuk Dashboard
                    </button>

                    <div class="text-center">
                        <span class="text-muted small">Belum punya akun? </span>
                        <a href="/register" class="text-link text-decoration-none small fw-semibold" id="register-link">Daftar Akun</a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>