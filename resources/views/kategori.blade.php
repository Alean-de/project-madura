@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Kategori Produk</h3>
            <p class="text-muted small mb-0">Kelola pengelompokan produk, kontrol status aktif, dan pantau volume persebaran barang.</p>
        </div>

        <button type="button" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 card-hover-animate" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#kategoriModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Kategori
        </button>
    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama klasifikasi kategori produk...">
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-muted border-bottom">
                        <tr>
                            <th class="p-3 ps-4">Nama Kategori</th>
                            <th class="text-center" style="width: 200px;">Jumlah Produk</th>
                            <th class="text-center" style="width: 180px;">Status</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @foreach ($category as $c)
                        <tr>
                            <td class="p-3 ps-4">
                                <span class="fw-bold text-dark">{{ $c->category_name }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fw-semibold fs-7">
                                    <i class="bi bi-box-seam text-secondary me-1"></i> {{ $c->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($c->status)
                                    <span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-semibold small">
                                        ● Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-semibold small">
                                        ● Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- EDIT BUTTON --}}
                                    <button type="button" class="btn btn-sm btn-light border-0 text-primary p-2 rounded-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $c->category_id }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>

                                    {{-- DELETE BUTTON --}}
                                    <button type="button" class="btn btn-sm btn-light border-0 text-danger p-2 rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $c->category_id }}">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-tags text-primary fs-4"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('category.create') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control py-2 rounded-3" placeholder="Contoh: Elektronik, Bahan Makanan, dll." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Jumlah Produk Awal</label>
                        <input type="number" name="product_count" class="form-control py-2 rounded-3" placeholder="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Status Aktivasi</label>
                        <select name="status" class="form-select py-2 rounded-3" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
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
@include('partials.footer')
@endsection

{{-- Custom Style Tambahan --}}
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