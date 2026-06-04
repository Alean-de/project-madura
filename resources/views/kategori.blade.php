@extends('layouts.app')

@section('title', 'Kategori')

@section('content')

<div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Kategori
            </h3>

            <small class="text-muted">
                Kelola kategori produk
            </small>

        </div>

        {{-- BUTTON TAMBAH --}}
        <button
            type="button"
            class="btn text-white px-4"
            style="background-color: #17354D;"
            data-bs-toggle="modal"
            data-bs-target="#kategoriModal">

            <i class="bi bi-plus-lg"></i>
            Tambah Kategori

        </button>

    </div>

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <input
                type="text"
                class="form-control"
                placeholder="Cari kategori...">

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <table class="table align-middle mb-0">

                <thead style="background-color: #F4ECEC;">

                    <tr>

                        <th class="p-3">
                            Nama Kategori
                        </th>

                        <th>
                            Jumlah produk
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

                    @foreach ($category as $c)

                    <tr>

                        <td class="p-3 fw-semibold">
                            {{ $c->category_name }}
                        </td>

                        <td>
                            {{ $c->product_count }}
                        </td>

                        <td>

                            @if ($c->status)

                                <span class="badge bg-success px-3 py-2">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-secondary px-3 py-2">
                                    Nonaktif
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
                                    data-bs-target="#editModal{{ $c->category_id }}">

                                    <i class="bi bi-pencil-fill"></i>

                                </button>

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $c->category_id }}">

                                    <i class="bi bi-trash-fill"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                    {{-- DELETE MODAL --}}
                    <div
                        class="modal fade"
                        id="deleteModal{{ $c->category_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-body p-4 text-center">

                                    <h5 class="fw-bold mb-3">
                                        Hapus Kategori
                                    </h5>

                                    <p class="text-muted">
                                        Yakin ingin menghapus kategori ini?
                                    </p>

                                    <form
                                        action="{{ route('category.deleteCategory', $c->category_id) }}"
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
                        id="editModal{{ $c->category_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-header border-0">

                                    <h5 class="fw-bold">
                                        Edit Kategori
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <form
                                    action="{{ route('kategori.update', $c->category_id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">

                                        {{-- NAMA --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Kategori
                                            </label>

                                            <input
                                                type="text"
                                                name="category_name"
                                                class="form-control"
                                                value="{{ $c->category_name }}"
                                                required>

                                        </div>

                                        {{-- JUMLAH PRODUK --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Jumlah Produk
                                            </label>

                                            <input
                                                type="number"
                                                name="product_count"
                                                class="form-control"
                                                value="{{ $c->product_count }}">

                                        </div>

                                        {{-- STATUS --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Status
                                            </label>

                                            <select
                                                name="status"
                                                class="form-select">

                                                <option
                                                    value="1"
                                                    {{ $c->status ? 'selected' : '' }}>

                                                    Aktif

                                                </option>

                                                <option
                                                    value="0"
                                                    {{ !$c->status ? 'selected' : '' }}>

                                                    Nonaktif

                                                </option>

                                            </select>

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
    action="{{ route('kategori.newKategori') }}"
    method="POST">

    @csrf

    <div
        class="modal fade"
        id="kategoriModal"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-header border-0">

                    <h5 class="fw-bold">
                        Tambah Kategori
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    {{-- NAMA --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="category_name"
                            class="form-control"
                            placeholder="Masukkan kategori"
                            required>

                    </div>

                    {{-- JUMLAH PRODUK --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Jumlah Produk
                        </label>

                        <input
                            type="number"
                            name="product_count"
                            class="form-control"
                            placeholder="0"
                            required>

                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required>

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

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