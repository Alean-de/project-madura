<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.header')
    <style>
        body {
            background-color: #f8f9fa !important; 
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .login-card {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            overflow: hidden; 
        }
        .brand-banner {
            background-color:#17354D !important; 
            padding: 24px;
            text-align: center;
        }
        .logo-branding {
            color: #ffffff !important; 
            font-weight: 800;
            font-size: 26px;
            letter-spacing: -1px;
            margin: 0;
        }
        .logo-branding span {
            color: #f2cb05 !important; 
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
            text-transform: uppercase;
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
            border-color: #17354D !important; 
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
            background-color: #122a3d !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
                <h5 class="login-title text-center mb-1">Kata Sandi Baru</h5>
                <p class="text-muted text-center small mb-3">Langkah terakhir untuk memulihkan akun Anda.</p>
                
                <div class="alert alert-info bg-info bg-opacity-10 text-dark border-0 small p-2 rounded-3 text-center mb-3" style="font-size: 12px;">
                    Mengubah sandi untuk: <strong class="text-primary">{{ session('reset_email') }}</strong>
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label form-label-custom mb-1">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="form-control form-control-custom shadow-none @error('password') is-invalid @enderror" required autofocus>
                        @error('password')
                            <div class="text-danger small mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label form-label-custom mb-1">Ulangi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="form-control form-control-custom shadow-none" required>
                    </div>

                    <button type="submit" class="btn btn-adddawn w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-all fs-5"></i> Simpan & Perbarui Sandi
                    </button>
                </form>
            </div>

        </div>
    </div>
    @include('partials.footer')
</body>
</html>