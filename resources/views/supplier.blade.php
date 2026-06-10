@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="container-fluid px-1 py-2">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Supplier</h3>
            <p class="text-muted small mb-0">Kelola daftar pihak ketiga, vendor penyedia barang, dan integritas kontak operasional.</p>
        </div>

        <button type="button" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 btn-custom-dark-hover" style="background-color: #17354D;" data-bs-toggle="modal" data-bs-target="#supplierModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Supplier
        </button>
    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-light-subtle border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="searchSupplier" class="form-control bg-white border-light-subtle border-start-0 ps-0 py-2 fs-6" placeholder="Cari nama vendor atau kontak supplier...">
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
                            <th class="p-3 text-center" style="width: 100px;">Kode</th>
                            <th>Nama Supplier</th>
                            <th>Kontak Telepon</th>
                            <th>Kota Domisili</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supplierTableBody" class="border-0">
                        
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary bg-white">
                                <i class="bi bi-folder-x text-muted display-5 d-block mb-3"></i>
                                <span class="fs-6 d-block fw-medium text-dark mb-1">Tidak Ada Data Supplier</span>
                                <p class="text-muted small mb-0">Belum ditemukan data record supplier di dalam database.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- GLOBAL EDIT MODAL (Target AJAX) --}}
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-pencil-square text-warning fs-4"></i> Modifikasi Data Supplier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditSupplier">
                <input type="hidden" id="edit_supplier_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Supplier</label>
                        <input type="text" id="edit_supplier_name" class="form-control py-2 rounded-3 border-light-subtle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kontak Telepon</label>
                        <input type="text" id="edit_contacts" class="form-control py-2 rounded-3 border-light-subtle" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kota Domisili</label>
                        <input type="text" id="edit_city" class="form-control py-2 rounded-3 border-light-subtle" required>
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

{{-- GLOBAL DELETE MODAL (Target AJAX) --}}
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-circle fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Hapus Supplier?</h5>
                <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus data supplier ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 d-flex gap-2 justify-content-center px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light w-50 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteSupplier" class="btn btn-danger w-50 py-2 rounded-3 shadow-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-truck text-warning fs-4"></i> Tambah Supplier Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahSupplier" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Supplier</label>
                        <input type="text" name="supplier_name" placeholder="Masukkan nama PT / CV / Toko Vendor" class="form-control py-2 rounded-3 border-light-subtle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kontak Telepon</label>
                        <input type="text" name="contacts" class="form-control py-2 rounded-3 border-light-subtle" placeholder="Contoh: 08XXXXXXXXXX" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kota Operasional</label>
                        <input type="text" name="city" placeholder="Masukkan kota domisili pusat" class="form-control py-2 rounded-3 border-light-subtle" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color: #17354D;">Tambahkan Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Custom Style Global --}}
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
    <script src="{{ asset('js/supplier.js') }}"></script>
@endpush