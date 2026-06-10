<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.header')
    <style>
        /* 🔥 CUSTOM STYLE INVENTORIKU - SPLIT BLUE REGISTER LIGHT MODE */
        body {
            background-color: #f8f9fa !important; /* Latar luar murni cerah gess */
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .register-card {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            overflow: hidden; /* Biar sudut kelengkungan banner biru aman gak bocor Dro */
        }
        /* 🔥 KOTAK BANNER BIRU UNTUK BRANDING */
        .brand-banner {
            background-color: #17354D !important; /* Biru premium murni */
            padding: 24px;
            text-align: center;
        }
        .logo-branding {
            color: #ffffff !important; /* "Inventori" Menyala putih murni */
            font-weight: 800;
            font-size: 26px;
            letter-spacing: -1px;
            margin: 0;
        }
        .logo-branding span {
            color: #f2cb05 !important; /* "Ku" Menyala kuning mustard */
        }
        .brand-subtitle {
            color: rgba(255, 255, 255, 0.75) !important;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 0;
        }
        .register-title {
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
            padding: 9px 14px; /* Sedikit lebih ringkas biar form gak terlalu melorot kebawah cukk */
            font-size: 14px;
            transition: all 0.2s ease-in-out;
        }
        .form-control-custom:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
        }
        .btn-adddawn {
            background-color: #17354D !important; /* Hitam arang premium */
            border: none !important;
            color: #ffffff !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 11px;
            transition: all 0.2s ease;
        }
        .btn-adddawn:hover {
            background-color: #262626 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .text-link {
            color: #0d6efd !important;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .text-link:hover {
            color: #0b5ed7 !important;
            text-decoration: underline !important;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100 px-3 py-4">
        <div class="card register-card shadow-sm p-0 w-100" style="max-width: 400px;">
            
            <div class="brand-banner">
                <div class="logo-branding">
                    <i class="bi bi-box-seam-fill me-2" style="color: #f2cb05;"></i>Inventori<span>Ku</span>
                </div>
                <p class="brand-subtitle">Pendaftaran Akun Administrator Baru</p>
            </div>

            <div class="p-4 pt-3">
                <h5 class="register-title text-center mb-3">Buat Akun</h5>

                @if($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 small p-2 rounded-3 mb-3">
                        <ul class="mb-0 ps-3" style="font-size: 12px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pregister') }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <label for="name" class="form-label form-label-custom mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="form-control form-control-custom shadow-none" required autofocus>
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label form-label-custom mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="admin@gmail.com" class="form-control form-control-custom shadow-none" required>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label form-label-custom mb-1">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="form-control form-control-custom shadow-none" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label form-label-custom mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="form-control form-control-custom shadow-none" required>
                    </div>

                    <button type="submit" class="btn btn-adddawn w-100 mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-person-plus-fill"></i> Selesaikan Pendaftaran
                    </button>

                    <div class="text-center" style="font-size: 13px;">
                        <span class="text-muted">Sudah memiliki akun? </span>
                        <a href="{{ route('login') }}" class="text-link text-decoration-none fw-semibold" id="login-link">Masuk</a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>