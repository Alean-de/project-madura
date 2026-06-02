@extends('layouts.app')

@section('title', 'Supplier')

@section('content')

<div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Supplier
            </h3>

            <small class="text-muted">
                Kelola data vendor
            </small>

        </div>

        {{-- BUTTON TAMBAH --}}
        <button
            type="button"
            class="btn text-white px-4"
            style="background-color: #17354D;"
            data-bs-toggle="modal"
            data-bs-target="#supplierModal">

            <i class="bi bi-plus-lg"></i>
            Tambah Supplier

        </button>

    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <input
                type="text"
                class="form-control"
                placeholder="Cari supplier...">

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <table class="table align-middle mb-0">

                <thead style="background-color: #F4ECEC;">

                    <tr>

                        <th class="p-3">
                            Kode
                        </th>

                        <th>
                            Nama supplier
                        </th>

                        <th>
                            Kontak
                        </th>

                        <th>
                            Kota
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($supplier as $s)

                    <tr>

                        <td class="p-3">
                            SUP-00{{ $s->supplier_id }}
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

                            @if ($s->status)

                                <span class="badge bg-success px-3 py-2">
                                    Open
                                </span>

                            @else

                                <span class="badge bg-secondary px-3 py-2">
                                    Close
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- EDIT --}}
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSupplierModal{{ $s->supplier_id }}">

                                    <i class="bi bi-pencil-fill"></i>

                                </button>

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $s->supplier_id }}">

                                    <i class="bi bi-trash-fill"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                    {{-- DELETE MODAL --}}
                    <div
                        class="modal fade"
                        id="deleteModal{{ $s->supplier_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-body p-4 text-center">

                                    <h5 class="fw-bold mb-3">
                                        Hapus Supplier
                                    </h5>

                                    <p class="text-muted">
                                        Yakin ingin menghapus
                                        {{ $s->supplier_name }} ?
                                    </p>

                                    <form
                                        action="{{ route('category.deleteSupplier', $s->supplier_id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <div class="d-flex justify-content-center gap-3">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">

                                                Batal

                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-danger">

                                                Hapus

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- EDIT MODAL --}}
                    <div
                        class="modal fade"
                        id="editSupplierModal{{ $s->supplier_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-header border-0">

                                    <h5 class="fw-bold">
                                        Edit Supplier
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <form
                                    action="{{ route('supplier.updateData', $s->supplier_id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Supplier
                                            </label>

                                            <input
                                                type="text"
                                                value="{{ $s->supplier_name }}"
                                                name="supplier_name"
                                                class="form-control"
                                                required>

                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label">
                                                Kontak
                                            </label>

                                            <input
                                                type="text"
                                                value="{{ $s->contacts }}"
                                                name="contacts"
                                                class="form-control"
                                                required>

                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label">
                                                Kota
                                            </label>

                                            <input
                                                type="text"
                                                value="{{ $s->city }}"
                                                name="city"
                                                class="form-control"
                                                required>

                                        </div>

                                    </div>

                                    <div class="modal-footer border-0">

                                        <button
                                            type="submit"
                                            class="btn text-white px-4"
                                            style="background-color: #17354D;">

                                            Simpan

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL TAMBAH --}}
<form
    action="{{ route('supplier.newSupplier') }}"
    method="POST">

    @csrf

    <div
        class="modal fade"
        id="supplierModal"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-header border-0">

                    <h5 class="fw-bold">
                        Tambah Supplier
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Supplier
                        </label>

                        <input
                            type="text"
                            name="supplier_name"
                            class="form-control"
                            placeholder="Masukkan supplier"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Kontak
                        </label>

                        <input
                            type="text"
                            name="contacts"
                            class="form-control"
                            placeholder="08xxxxxxxxxx"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Kota
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            placeholder="Jakarta"
                            required>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button
                        type="submit"
                        class="btn text-white px-4"
                        style="background-color: #17354D;">

                        Tambahkan

                    </button>

                </div>

            </div>

        </div>

    </div>

</form>

@endsection