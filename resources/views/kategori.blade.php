@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="container-fluid px-1 py-2">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Kategori Produk</h3>
            <p class="text-muted small mb-0">Kelola pengelompokan produk, kontrol status aktif, dan pantau volume persebaran barang.</p>
        </div>

        <button type="button" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 btn-custom-dark-hover" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#kategoriModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Kategori
        </button>
    </div>

    {{-- SEARCHBAR --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-light-subtle border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchCategory" class="form-control bg-white border-light-subtle border-start-0 ps-0 py-2 fs-6" placeholder="Cari nama klasifikasi kategori produk...">
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-secondary border-bottom border-light-subtle">
                        <tr>
                            <th class="p-3 ps-4">Nama Kategori</th>
                            <th class="text-center" style="width: 220px;">Jumlah Produk</th>
                            <th class="text-center" style="width: 180px;">Status</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody" class="border-0">
                        @forelse ($category as $c)
                        <tr id="row-{{ $c->category_id }}">
                            <td class="p-3 ps-4 row-category-name">
                                <span class="fw-bold text-dark fs-6">{{ $c->category_name }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border border-light-subtle px-3 py-2 rounded-2 fw-medium fs-7 shadow-sm">
                                    <i class="bi bi-box-seam text-secondary me-1.5"></i> {{ number_format($c->products_count, 0, ',', '.') }} Produk
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-toggle-status badge {{ $c->status ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-3 py-1.5 rounded-pill fw-semibold small border-0" data-id="{{ $c->id }}" data-status="{{ $c->status }}">
                                    <i class="bi bi-circle-fill me-1 small" style="font-size: 0.5rem; vertical-align: middle;"></i> 
                                    <span>{{ $c->status ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm bg-light text-primary border border-light-subtle rounded-2 p-2 inline-flex align-items-center btn-edit-category" data-id="{{ $c->id }}" data-name="{{ $c->category_name }}" data-status="{{ $c->status }}">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="4" class="text-center py-5 text-secondary bg-white">
                                <i class="bi bi-tags text-muted display-5 d-block mb-3"></i>
                                <span class="fs-6 d-block fw-medium text-dark mb-1">Tidak Ada Kategori</span>
                                <p class="text-muted small mb-0">Belum ada data klasifikasi kategori barang yang tersimpan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADD --}}
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-tags text-warning fs-4"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddCategory">
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control py-2 rounded-3 border-light-subtle" placeholder="Contoh: Elektronik, Bahan Makanan, dll." required>
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

{{-- MODAL EDIT --}}
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-pencil-square text-warning fs-4"></i> Modifikasi Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditCategory">
                <input type="hidden" id="editCategoryId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Kategori</label>
                        <input type="text" id="editCategoryName" name="category_name" class="form-control py-2 rounded-3 border-light-subtle" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-secondary small mb-1">Status Aktivasi</label>
                        <select id="editCategoryStatus" name="status" class="form-select py-2 rounded-3 border-light-subtle" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
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

@endsection

@push('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
@endpush