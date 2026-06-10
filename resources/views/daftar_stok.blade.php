@extends('layouts.app')

@section('title', 'Daftar Stok')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-extrabold text-dark tracking-tight mb-1">Daftar Stok</h3>
        <p class="text-muted small mb-0">Monitor pergerakan volume stok produk secara langsung, deteksi penipisan barang, dan kelola ambang batas aman.</p>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Total Unit Stok</small>
                        <h4 id="statTotalStock" class="fw-bold mb-0 text-dark">{{ number_format($totalUnitStock ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 {{ ($lowStockCount ?? 0) > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Stok Menipis (Low Stock)</small>
                        <h4 id="statLowStock" class="fw-bold mb-0 {{ ($lowStockCount ?? 0) > 0 ? 'text-danger fw-extrabold' : 'text-dark' }}">{{ number_format($lowStockCount ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-12 col-lg-5">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Cari Barang</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        {{-- FIX: Tambahkan id="searchStock" --}}
                        <input type="text" id="searchStock" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama produk, SKU, atau kode barang...">
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Status Kondisi</label>
                    {{-- FIX: Tambahkan id="filterStockKondisi" --}}
                    <select id="filterStockKondisi" class="form-select bg-light">
                        <option value="">Semua Status Stok</option>
                        <option value="cukup">Cukup</option>
                        <option value="tipis">Tipis</option>
                        <option value="rendah">Rendah</option>
                    </select>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Kategori Produk</label>
                    {{-- FIX: Ganti ke Kategori sesuai Controller & Tambahkan id="filterStockCategory" --}}
                    <select id="filterStockCategory" class="form-select bg-light">
                        <option value="">Semua Gudang</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-muted border-bottom">
                        <tr>
                            {{-- FIX: Total kolom disesuaikan menjadi 5 (Membuang kolom aksi karena ini page daftar stok) --}}
                            <th class="p-3 ps-4" style="width: 150px;">SKU</th>
                            <th>Spesifikasi Nama Produk</th>
                            <th style="width: 160px;">Stok Fisik</th>
                            <th style="width: 160px;">Batas Minimal</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody" class="border-0">
                        {{-- Render otomatis via AJAX stock.js --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- NAVIGASI PAGINASI --}}
    <div id="paginationLinks" class="d-flex gap-1 mt-3"></div>
</div>
@include('partials.footer')
@endsection

{{-- Custom Style Tambahan --}}
<style>
    .card-hover-animate {
        transition: all 0.2s ease-in-out;
    }
    .card-hover-animate:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
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
    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
    }
</style>

{{-- FIX: Samakan nama stack push-nya (Kemarin lo nulis @push('script') tanpa 's') --}}
@push('scripts')
    <script src="{{ asset('js/stock.js') }}?v={{ time() }}"></script>
@endpush