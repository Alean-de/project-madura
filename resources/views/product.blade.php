@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<div>
    {{-- HEADER & BUTTON TAMBAH --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Produk</h3>
        </div>
        <button type="button" class="btn px-4 text-white" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#inputModal">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </button>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="searchProduct" placeholder="Cari produk, SKU, atau barcode...">
                </div>
                <div class="col-md-3">
                    <select id="filterCategory" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($category as $c)
                            <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterSupplier" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($supplier as $s)
                            <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterStock" class="form-select">
                        <option value="">Semua stok</option>
                        <option value="safe">Stok Aman</option>
                        <option value="low">Stok Menipis</option>
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
                        <th class="p-3">No</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok Minimal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    {{-- Diisi secara otomatis oleh product.js --}}
                </tbody>
            </table>

            <div id="paginationLinks" class="mt-3"></div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formNewProduct" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="product_name" placeholder="Masukkan nama produk" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="" selected disabled>Pilih kategori</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="" selected disabled>Pilih supplier</option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="unit" placeholder="Contoh: Pcs, Box, Pack" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Beli</label>
                            <input type="number" min="0" step="1" name="purchase_price" placeholder="0" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual</label>
                            <input type="number" min="0" step="1" name="selling_price" placeholder="0" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" name="initial_stock" placeholder="0" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Minimum</label>
                            <input type="number" name="minimum_stock" placeholder="0" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #17354D;">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PRODUK (SINGLE MODAL - DATA DITEMBAK AJAX) --}}
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditProduct" method="POST">
                @csrf
                <input type="hidden" id="edit_product_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            {{-- Nama atribut name disamakan dengan controller backend --}}
                            <input type="text" name="product_name" id="edit_product_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" id="edit_category_id" class="form-select" required>
                                @foreach ($category as $c)
                                    <option value="{{ $c->category_id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier_id" id="edit_supplier_id" class="form-select" required>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="unit" id="edit_unit" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Beli</label>
                            <input type="number" min="0" step="1" name="purchase_price" id="edit_purchase_price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual</label>
                            <input type="number" min="0" step="1" name="selling_price" id="edit_selling_price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Minimum</label>
                            <input type="number" name="minimum_stock" id="edit_minimum_stock" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #17354D;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/product.js') }}?v={{ time() }}"></script>
@endpush