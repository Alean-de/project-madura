@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Supplier</h3>
            <p class="text-muted small mb-0">Kelola daftar pihak ketiga, vendor penyedia barang, dan integritas kontak operasional.</p>
        </div>

        <button type="button" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 card-hover-animate" style="background-color:#17354D" data-bs-toggle="modal" data-bs-target="#supplierModal">
            <i class="bi bi-plus-lg fw-bold"></i> Tambah Supplier
        </button>
    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama vendor, kode supplier, atau domisili kota...">
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
                            <th class="p-3 text-center" style="width: 100px;">Kode</th>
                            <th>Nama Supplier</th>
                            <th>Kontak Telepon</th>
                            <th>Kota Domisili</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($supplier as $s)
                        <tr>
                            <td class="p-3 text-center">
                                <span class="badge bg-light text-secondary border px-2 py-1.5 rounded-2 font-monospace fs-7">
                                    SUP-{{ str_pad($s->supplier_id, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $s->supplier_name }}</span>
                            </td>
                            <td class="text-secondary small">
                                <i class="bi bi-telephone text-muted me-1"></i>
                                {{ preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $s->contacts) }}
                            </td>
                            <td>
                                <span class="text-dark small"><i class="bi bi-geo-alt text-muted me-1"></i>{{ $s->city }}</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('supplier.status', $s->supplier_id) }}" method="POST" id="statusForm{{ $s->supplier_id }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm border-0 fw-semibold rounded-pill px-3 py-1 text-center {{ $s->status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" onchange="document.getElementById('statusForm{{ $s->supplier_id }}').submit()" style="cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                                        <option value="1" {{ $s->status ? 'selected' : '' }}>● Aktif</option>
                                        <option value="0" {{ !$s->status ? 'selected' : '' }}>● Nonaktif</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- EDIT BUTTON --}}
                                    <button type="button" class="btn btn-sm btn-light border-0 text-primary p-2 rounded-3" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $s->supplier_id }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>

                                    {{-- DELETE BUTTON --}}
                                    <button type="button" class="btn btn-sm btn-light border-0 text-danger p-2 rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $s->supplier_id }}">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- EDIT MODAL --}}
                        <div class="modal fade" id="editSupplierModal{{ $s->supplier_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <div class="modal-header border-0 px-4 pt-4 pb-0">
                                        <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                                            <i class="bi bi-pencil-style text-primary fs-4"></i> Edit Vendor Supplier
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('supplier.update', $s->supplier_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-secondary small mb-1">Nama Supplier</label>
                                                <input type="text" name="supplier_name" value="{{ $s->supplier_name }}" class="form-control py-2 rounded-3" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-secondary small mb-1">Kontak Telepon</label>
                                                <input type="text" name="contacts" value="{{ $s->contacts }}" class="form-control py-2 rounded-3" placeholder="Contoh: 0812xxxxxxxx" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-secondary small mb-1">Kota Asal</label>
                                                <input type="text" name="city" value="{{ $s->city }}" class="form-control py-2 rounded-3" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                            <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color:#17354D">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- DELETE MODAL --}}
                        <div class="modal fade" id="deleteModal{{ $s->supplier_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-danger mb-3">
                                            <i class="bi bi-x-circle-fill" style="font-size: 3.5rem;"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2">Hapus Supplier?</h5>
                                        <p class="text-muted small mb-4">
                                            Tindakan ini tidak bisa dibatalkan. Anda yakin ingin menghapus vendor <strong>{{ $s->supplier_name }}</strong>?
                                        </p>
                                        <form action="{{ route('supplier.delete', $s->supplier_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light px-3 py-2 text-secondary rounded-3 w-50 small" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger px-3 py-2 border-0 rounded-3 w-50 small shadow-sm">Ya, Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x text-light-emphasis display-5 d-block mb-2"></i>
                                Tidak ditemukan data record supplier di dalam database.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-truck-flatbed text-primary fs-4"></i> Tambah Supplier Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('supplier.create') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Nama Supplier</label>
                        <input type="text" name="supplier_name" placeholder="Masukkan nama PT / CV / Toko Vendor" class="form-control py-2 rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kontak Telepon</label>
                        <input type="text" name="contacts" class="form-control py-2 rounded-3" placeholder="Contoh: 08XXXXXXXXXX" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kota Operasional</label>
                        <input type="text" name="city" placeholder="Masukkan kota domisili pusat" class="form-control py-2 rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color:#17354D">Tambahkan Vendor</button>
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


