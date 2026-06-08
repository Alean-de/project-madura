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
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalUnitStock ?? 0, 0, ',', '.') }}</h4>
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
                        <h4 class="fw-bold mb-0 {{ ($lowStockCount ?? 0) > 0 ? 'text-danger fw-extrabold' : 'text-dark' }}">{{ number_format($lowStockCount ?? 0, 0, ',', '.') }}</h4>
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
                        <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama produk, SKU, atau kode barang...">
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Status Kondisi</label>
                    <select class="form-select bg-light">
                        <option value="">Semua Status Stok</option>
                        <option value="cukup">Cukup (Aman)</option>
                        <option value="tipis">Tipis (Ambang Batas)</option>
                        <option value="rendah">Rendah (Kritis)</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Lokasi Penempatan</label>
                    <select class="form-select bg-light">
                        <option value="">Semua Gudang</option>
                        @foreach ($warehouses ?? [] as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
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
                            <th class="p-3 ps-4" style="width: 150px;">SKU</th>
                            <th>Spesifikasi Nama Produk</th>
                            <th style="width: 160px;">Stok Fisik</th>
                            <th style="width: 160px;">Batas Minimal</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @if(empty($product) || $product->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox text-light-emphasis display-5 d-block mb-2"></i>
                                    Tidak ditemukan data komoditas stok produk.
                                </td>
                            </tr>
                        @else
                            @foreach ($product as $p)
                            <tr>
                                <td class="p-3 ps-4">
                                    <span class="badge bg-light text-secondary border px-2 py-1.5 rounded-2 font-monospace fs-7">
                                        {{ $p->sku ?? 'PRD-' . str_pad($p->product_id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $p->product_name }}</span>
                                    <small class="text-muted fs-7"><i class="bi bi-tags me-1"></i>{{ optional($p->category)->category_name ?? 'Tanpa Kategori' }}</small>
                                </td>
                                <td class="fw-bold text-dark">{{ number_format($p->initial_stock, 0, ',', '.') }} <span class="text-muted fw-normal small">{{ $p->unit }}</span></td>
                                <td class="text-secondary fw-semibold">{{ number_format($p->minimum_stock, 0, ',', '.') }} <span class="text-muted fw-normal small">{{ $p->unit }}</span></td>
                                <td class="text-center">
                                    @if ($p->initial_stock <= 0)
                                        <span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-semibold small w-100">Habis</span>
                                    @elseif ($p->initial_stock < $p->minimum_stock)
                                        <span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-semibold small w-100">Rendah</span>
                                    @elseif ($p->initial_stock == $p->minimum_stock)
                                        <span class="badge bg-warning-subtle text-warning px-3 py-1.5 rounded-pill fw-semibold small w-100">Tipis</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-semibold small w-100">Cukup</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- EDIT STOCK BUTTON --}}
                                    <button type="button" class="btn btn-sm btn-light border-0 text-primary p-2 rounded-3" data-bs-toggle="modal" data-bs-target="#editStockModal{{ $p->product_id }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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