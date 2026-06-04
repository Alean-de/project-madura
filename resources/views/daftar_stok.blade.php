@extends('layouts.app')

@section('title', 'Daftar Stok')

@section('content')
<div>
    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Stok</h3>
        <small class="text-muted">Monitor stok produk</small>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center justify-content-center gap-3 py-3">
                    <i class="bi bi-box-seam fs-2 text-primary"></i>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $totalUnitStock ?? 0 }}</h5>
                        <small class="text-muted">Total Unit Stock</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center justify-content-center gap-3 py-3">
                    <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $lowStockCount ?? 0 }}</h5>
                        <small class="text-muted">Low Stock</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Cari produk...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">Semua Status Stok</option>
                        <option value="cukup">Cukup</option>
                        <option value="tipis">Tipis</option>
                        <option value="rendah">Rendah</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select">
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
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead style="background-color: #F4ECEC;">
                    <tr>
                        <th class="p-3" style="width: 15%;">SKU</th>
                        <th style="width: 35%;">Produk</th>
                        <th style="width: 15%;">Stok</th>
                        <th style="width: 15%;">Min. Stok</th>
                        <th style="width: 12%;">Status</th>
                        <th class="text-center" style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @empty($product)
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Belum ada data stok produk.
                            </td>
                        </tr>
                    @else
                        @foreach ($product as $p)
                        <tr>
                            <td class="p-3 text-muted">{{ $p->sku ?? 'PRD-' . str_pad($p->product_id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="fw-semibold">{{ $p->product_name }}</td>
                            <td>{{ $p->initial_stock }} {{ $p->unit }}</td>
                            <td>{{ $p->minimum_stock }} {{ $p->unit }}</td>
                            <td>
                                @if ($p->initial_stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif ($p->initial_stock < $p->minimum_stock)
                                    <span class="badge bg-danger">Rendah</span>
                                @elseif ($p->initial_stock == $p->minimum_stock)
                                    <span class="badge bg-warning text-dark">Tipis</span>
                                @else
                                    <span class="badge bg-success">Cukup</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editStockModal{{ $p->product_id }}">
                                    <i class="bi bi-pencil-fill text-secondary"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection