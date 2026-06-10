@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container-fluid px-1 py-2">

    {{-- HEADER & BUTTON TAMBAH --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Manajemen Produk</h3>
            <p class="text-muted small mb-0">Kelola informasi barang, harga, kategori, dan kontrol ambang batas minimum stok.</p>
        </div>
        <button type="button" class="btn px-4 py-2 text-white shadow-sm d-flex align-items-center gap-2 border-0 rounded-3 btn-custom-dark-hover" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#inputModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Produk
        </button>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-12 col-lg-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase tracking-wider fs-7">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-light-subtle border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-white border-light-subtle border-start-0 ps-0 fs-6 py-2" id="searchProduct" placeholder="Cari nama produk, SKU, atau barcode...">
                    </div>
                </div>
                <div class="col-sm-4 col-lg-2">
                    <label class="form-label small fw-semibold text-secondary text-uppercase tracking-wider fs-7">Kategori</label>
                    <select id="filterCategory" class="form-select bg-white border-light-subtle py-2">
                        <option value="">Semua Kategori</option>
                        @foreach ($category as $c)
                            <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small fw-semibold text-secondary text-uppercase tracking-wider fs-7">Supplier</label>
                    <select id="filterSupplier" class="form-select bg-white border-light-subtle py-2">
                        <option value="">Semua Supplier</option>
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}">{{ $s->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small fw-semibold text-secondary text-uppercase tracking-wider fs-7">Status Stok</label>
                    <select id="filterStock" class="form-select bg-white border-light-subtle py-2">
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
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-secondary border-bottom border-light-subtle">
                        <tr class="">
                            <th class="p-3 text-center" style="width: 70px;">No</th>
                            <th class="p-3 text-center" style="width: 150px;">Info Produk</th>
                            <th class="p-3 text-center" style="width: 150px;">Kategori</th>
                            <th class="p-3 text-center" style="width: 150px;">Unit</th>
                            <th class="p-3 text-center" style="width: 150px;">Harga Beli</th>
                            <th class="p-3 text-center" style="width: 150px;">Harga Jual</th>
                            <th class="p-3 text-center" style="width: 140px;">Stok Minimal</th>
                            <th class="p-3 text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody" class="border-0">

                    </tbody>
                </table>
            </div>

            <div id="paginationLinks" class="d-flex justify-content-between align-items-center p-3 border-top border-light-subtle bg-light-subtle">
                
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-box-seam text-warning fs-4"></i> Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formNewProduct" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nama Produk</label>
                            <input type="text" name="product_name" placeholder="Masukkan nama komoditas / merek produk" class="form-control py-2 rounded-3 border-light-subtle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Kategori Klasifikasi</label>
                            <select name="category_id" class="form-select py-2 rounded-3 border-light-subtle" required>
                                <option value="" selected disabled>Pilih klasifikasi kategori</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Supplier Utama</label>
                            <select name="supplier_id" class="form-select py-2 rounded-3 border-light-subtle" required>
                                <option value="" selected disabled>Pilih vendor pengirim</option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Satuan Unit</label>
                            <input type="text" name="unit" placeholder="Contoh: Pcs, Box, Pack, Kilogram" class="form-control py-2 rounded-3 border-light-subtle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Beli (Modal)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary border-light-subtle fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="purchase_price" placeholder="0" class="form-control py-2 border-light-subtle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary border-light-subtle fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="selling_price" placeholder="0" class="form-control py-2 border-light-subtle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Stok Awal Fisik</label>
                            <input type="number" name="initial_stock" placeholder="0" class="form-control py-2 rounded-3 border-light-subtle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Ambang Batas Stok Minimum</label>
                            <input type="number" name="minimum_stock" placeholder="0" class="form-control py-2 rounded-3 border-light-subtle" required>
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-pencil-square text-warning fs-4"></i> Modifikasi Data Produk
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
                            <input type="text" name="product_name" id="edit_product_name" class="form-control py-2 rounded-3 border-light-subtle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Kategori</label>
                            <select name="category_id" id="edit_category_id" class="form-select py-2 rounded-3 border-light-subtle" required>
                                @foreach ($category as $c)
                                    <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Supplier Utama</label>
                            <select name="supplier_id" id="edit_supplier_id" class="form-select py-2 rounded-3 border-light-subtle" required>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Satuan Unit</label>
                            <input type="text" name="unit" id="edit_unit" class="form-control py-2 rounded-3 border-light-subtle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Beli (Modal)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary border-light-subtle fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="purchase_price" id="edit_purchase_price" class="form-control py-2 border-light-subtle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary border-light-subtle fw-medium small">Rp</span>
                                <input type="number" min="0" step="1" name="selling_price" id="edit_selling_price" class="form-control py-2 border-light-subtle" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Ambang Batas Stok Minimum</label>
                            <input type="number" name="minimum_stock" id="edit_minimum_stock" class="form-control py-2 rounded-3 border-light-subtle" required>
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

{{-- MODAL DELETE PRODUCT --}}
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-circle fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Hapus Produk?</h5>
                <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus data produk ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 d-flex gap-2 justify-content-center px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light w-50 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteProduct" class="btn btn-danger w-50 py-2 rounded-3 shadow-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Custom Styles Global --}}
<style>
    .btn-custom-dark-hover {
        transition: opacity 0.2s ease-in-out;
    }
    .btn-custom-dark-hover:hover {
        opacity: 0.9;
    }
    .fs-7 {
        font-size: 0.72rem;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #17354D !important;
        box-shadow: 0 0 0 0.25rem rgba(23, 53, 77, 0.1) !important;
    }
</style>

@push('scripts')
    <script src="{{ asset('js/product.js') }}?v={{ time() }}"></script>
@endpush