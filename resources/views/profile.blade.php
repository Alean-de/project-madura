@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Pengaturan Akun</h3>
            <p class="text-muted small mb-0">Kelola identitas personal kredensial, perbarui foto profil, dan proteksi keamanan kata sandi Anda.</p>
        </div>
    </div>

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-3 fs-5 text-success"></i>
            <div>
                <span class="fw-semibold text-success">Berhasil!</span> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: PROFILE PICTURE CARD --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                {{-- Banner Dekoratif Mini --}}
                <div style="height: 90px; background-color: #17354D; opacity: 0.85;"></div>
                
                <div class="card-body text-center px-4 pb-4 position-relative" style="margin-top: -60px;">
                    <form action="{{ route('profile.update-avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3 position-relative d-inline-block">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->username ?? auth()->user()->name) . '&background=17354D&color=fff&size=150' }}" 
                                 alt="Avatar" 
                                 class="rounded-circle img-thumbnail shadow border-4 border-white object-fit-cover" 
                                 style="width: 120px; height: 120px;">
                        </div>

                        <h5 class="fw-bold mb-1 text-dark">{{ auth()->user()->username ?? auth()->user()->name }}</h5>
                        <p class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small mb-4 fs-7">Pengguna Sistem Inventori</p>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM IDENTITAS & PASSWORD --}}
        <div class="col-lg-8">
            {{-- FORM USERNAME --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-gear text-primary fs-4"></i> Kredensial Identitas
                    </h5>
                    <p class="text-muted small mb-0">Ubah nama panggilan atau username otentikasi masuk Anda.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update-username') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">Username Sekarang</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-lock"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 ps-0 text-muted" value="{{ auth()->user()->username ?? auth()->user()->name }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">Username Baru</label>
                            <input type="text" name="username" class="form-control py-2 rounded-3 @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan susunan kata username baru Anda..." required>
                            @error('username')
                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm card-hover-animate" style="background-color: #17354D;">
                                Simpan Perubahan Username
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- FORM PASSWORD --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-primary fs-4"></i> Proteksi Keamanan Sandi
                    </h5>
                    <p class="text-muted small mb-0">Ganti password secara berkala untuk menjaga keamanan data inventori.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="form-control py-2 rounded-3 @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('current_password')
                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row border-top pt-3 mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-control py-2 rounded-3 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control py-2 rounded-3" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="text-end pt-2">
                            <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm card-hover-animate" style="background-color: #17354D;">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.footer')
@endsection

{{-- Custom Style --}}
<style>
    .card-hover-animate {
        transition: all 0.2s ease-in-out;
    }
    .card-hover-animate:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }
    .fs-7 {
        font-size: 0.75rem;
    }
    .tracking-wider {
        letter-spacing: 0.06em;
    }
    .fw-extrabold {
        font-weight: 800;
    }
</style>

