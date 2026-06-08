@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER & BUTTON TAMBAH --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Manajemen Produk</h3>
            <p class="text-muted small mb-0">Kelola informasi barang, harga, kategori, dan kontrol ambang batas minimum stok.</p>
        </div>
        <button type="button" class="btn px-4 py-2 text-white shadow-sm d-flex align-items-center gap-2 border-0 rounded-3 card-hover-animate" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#inputModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Produk
        </button>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-12 col-lg-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-start-0 ps-0" id="searchProduct" placeholder="Cari nama produk, SKU, atau barcode...">
                    </div>
                </div>
                <div class="col-sm-4 col-lg-2">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Kategori</label>
                    <select id="filterCategory" class="form-select bg-light">
                        <option value="">Semua Kategori</option>
                        @foreach ($category as $c)
                            <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Supplier</label>
                    <select id="filterSupplier" class="form-select bg-light">
                        <option value="">Semua Supplier</option>
                        @foreach ($supplier as $s)
                            <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Status Stok</label>
                    <select id="filterStock" class="form-select bg-light">
                        <option value="">Semua Status Stok</option>
                        <option value="safe">Stok Aman</option>
                        <option value="low">Stok Menipis (Kritis)</option>
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
                            <th class="p-3 text-center" style="width: 60px;">No</th>
                            <th>Info Produk</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Stok Minimal</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody" class="border-0">
                        {{-- Diisi secara otomatis oleh product.js --}}
                        {{-- Contoh struktur baris tabel yang harus dirender via AJAX di product.js:
                        <tr>
                            <td class="text-center text-muted small">1</td>
                            <td>
                                <span class="fw-bold text-dark d-block">Nama Barang</span>
                                <small class="text-muted fs-7">Unit: Pcs</small>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary rounded-pill">Kategori</span></td>
                            <td class="text-secondary small">Nama Supplier</td>
                            <td class="text-end fw-semibold text-dark">Rp 10.000</td>
                            <td class="text-end fw-semibold text-success">Rp 15.000</td>
                            <td class="text-center"><span class="badge bg-danger-subtle text-danger">5</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border-0 text-primary me-1"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-light border-0 text-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        --}}
                    </tbody>
                </table>
            </div>

            <div id="paginationLinks" class="d-flex justify-content-between align-items-center p-3 border-top">
                {{-- Diisi secara otomatis oleh product.js --}}
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-box-seam text-primary fs-4"></i> Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formNewProduct" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nama Produk</label>
                            <input type="text" name="product_name" placeholder="Masukkan nama komoditas/produk" class="form-control py-2 rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Kategori</label>
                            <select name="category_id" class="form-select py-2 rounded-3" required>
                                <option value="" selected disabled>Pilih klasifikasi kategori</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Supplier Utama</label>
                            <select name="supplier_id" class="form-select py-2 rounded-3" required>
                                <option value="" selected disabled>Pilih vendor pengirim</option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Satuan Unit</label>
                            <input type="text" name="unit" placeholder="Contoh: Pcs, Box, Pack, Kilogram" class="form-control py-2 rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Beli (Modal)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="purchase_price" placeholder="0" class="form-control py-2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="selling_price" placeholder="0" class="form-control py-2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Stok Awal Fisik</label>
                            <input type="number" name="initial_stock" placeholder="0" class="form-control py-2 rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Ambang Batas Stok Minimum</label>
                            <input type="number" name="minimum_stock" placeholder="0" class="form-control py-2 rounded-3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color: #17354D;">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PRODUK --}}
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-pencil-square text-primary fs-4"></i> Modifikasi Data Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditProduct" method="POST">
                @csrf
                <input type="hidden" id="edit_product_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nama Produk</label>
                            <input type="text" name="product_name" id="edit_product_name" class="form-control py-2 rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Kategori</label>
                            <select name="category_id" id="edit_category_id" class="form-select py-2 rounded-3" required>
                                @foreach ($category as $c)
                                    <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Supplier Utama</label>
                            <select name="supplier_id" id="edit_supplier_id" class="form-select py-2 rounded-3" required>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Satuan Unit</label>
                            <input type="text" name="unit" id="edit_unit" class="form-control py-2 rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Beli (Modal)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="purchase_price" id="edit_purchase_price" class="form-control py-2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="selling_price" id="edit_selling_price" class="form-control py-2" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Ambang Batas Stok Minimum</label>
                            <input type="number" name="minimum_stock" id="edit_minimum_stock" class="form-control py-2 rounded-3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color: #17354D;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials.footer')
@endsection

{{-- Custom Styles Tambahan --}}
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
    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
    }
</style>

@push('scripts')
    <script src="{{ asset('js/product.js') }}?v={{ time() }}"></script>
@endpush