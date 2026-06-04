@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div>
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Supplier</h3>
            <small class="text-muted">Kelola data vendor</small>
        </div>

        <button type="button" class="btn text-white px-4" style="background-color:#17354D" data-bs-toggle="modal" data-bs-target="#supplierModal">
            <i class="bi bi-plus-lg"></i> Tambah Supplier
        </button>
    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <input type="text" class="form-control" placeholder="Cari supplier...">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead style="background-color:#F4ECEC">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Kota</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier as $s)
                    <tr>
                        <td class="p-3">
                            SUP-{{ str_pad($s->supplier_id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="fw-semibold">
                            {{ $s->supplier_name }}
                        </td>
                        <td>
                            {{ preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $s->contacts) }}
                        </td>
                        <td>
                            {{ $s->city }}
                        </td>
                        <td>
                            <form action="{{ route('supplier.status', $s->supplier_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="1" {{ $s->status ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !$s->status ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                {{-- EDIT --}}
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $s->supplier_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- DELETE --}}
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $s->supplier_id }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade" id="editSupplierModal{{ $s->supplier_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4">
                                <form action="{{ route('supplier.update', $s->supplier_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Supplier</label>
                                            <input type="text" name="supplier_name" value="{{ $s->supplier_name }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kontak</label>
                                            <input type="text" name="contacts" value="{{ $s->contacts }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kota</label>
                                            <input type="text" name="city" value="{{ $s->city }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn text-white" style="background-color:#17354D">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- DELETE MODAL --}}
                    <div class="modal fade" id="deleteModal{{ $s->supplier_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4">
                                <div class="modal-body text-center p-4">
                                    <h5 class="fw-bold">Hapus Supplier</h5>
                                    <p class="text-muted">
                                        Yakin ingin menghapus <strong>{{ $s->supplier_name }}</strong> ?
                                    </p>
                                    <form action="{{ route('supplier.delete', $s->supplier_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Tidak ada data supplier.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="supplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form action="{{ route('supplier.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="supplier_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="contacts" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kota</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" style="background-color:#17354D">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection