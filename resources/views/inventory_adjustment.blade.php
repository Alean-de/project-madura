@extends('layouts.app')

@section('title', 'Inventory Adjustment')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Inventory Adjustment</h3>
            <p class="text-muted small mb-0">Kelola histori pergerakan stok, catat barang masuk, barang keluar, log kerusakan, hingga produk kedaluwarsa.</p>
        </div>

        <button type="button" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 card-hover-animate" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#adjustmentModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Data Log
        </button>
    </div>

    {{-- SEARCH & FILTER STATUS --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Pencarian Produk</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchProduct" class="form-control bg-light border-start-0 ps-0" placeholder="Cari berdasarkan nama komoditas produk...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Filter Jenis Mutasi</label>
                    <select id="filterStatus" class="form-select bg-light">
                        <option value="">Semua Tipe Log</option>
                        <option value="barang_masuk">Barang Masuk</option>
                        <option value="barang_keluar">Barang Keluar</option>
                        <option value="rusak">Rusak</option>
                        <option value="exp">Expired</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
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
                            <th class="p-3 ps-4">Spesifikasi Nama Produk</th>
                            <th>Tanggal Kedaluwarsa</th>
                            <th>Kuantitas (Qty)</th>
                            <th class="text-center" style="width: 180px;">Jenis Status</th>
                        </tr>
                    </thead>
                    <tbody id="adjustmentTableBody" class="border-0">
                        {{-- Data dimuat otomatis via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINASI --}}
    <div id="paginationLinks" class="d-flex justify-content-end gap-2 mt-3"></div>

</div>

{{-- MODAL TAMBAH & EDIT --}}
<div class="modal fade" id="adjustmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0" id="modalTitle">
                    <i class="bi bi-sliders text-primary fs-4"></i> Tambah Adjustment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formAdjustment" action="{{ route('adjustment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="adj_id">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Pilih Produk</label>
                        <select name="product_id" id="adj_product_id" class="form-select py-2 rounded-3" required>
                            <option value="" selected disabled>Tentukan nama komoditas barang</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}">{{ $p->product_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Tanggal Kedaluwarsa (Expired Date)</label>
                        <input type="date" name="exp_date" id="adj_exp_date" class="form-control py-2 rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Jumlah Kuantitas Fisik</label>
                        <input type="number" name="qty" id="adj_qty" class="form-control py-2 rounded-3" min="1" placeholder="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Tipe Mutasi Log Status</label>
                        <select name="status" id="adj_status" class="form-select py-2 rounded-3" required>
                            <option value="barang_masuk">Barang Masuk</option>
                            <option value="barang_keluar">Barang Keluar</option>
                            <option value="rusak">Rusak</option>
                            <option value="exp">Expired</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitPo" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color:#17354D;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('partials.footer')
@endsection

@push('scripts')
    <script src="{{ asset('js/adjustment.js') }}?v={{ time() }}"></script>
@endpush

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