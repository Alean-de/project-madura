@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Produk
            </h3>

            <small class="text-muted">
                Kelola data produk inventori
            </small>
        </div>

        <button
            type="button"
            class="btn px-4 text-white"
            style="background-color: #17354D;"
            data-bs-toggle="modal"
            data-bs-target="#inputModal">

            <i class="bi bi-plus-lg"></i>
            Tambah Produk

        </button>

    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari produk, SKU, atau barcode...">

                </div>

                <div class="col-md-3">

                    <select class="form-select">

                        <option>
                            Semua kategori
                        </option>

                        @foreach ($category as $c)

                            <option>
                                {{ $c->category_name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <select class="form-select">

                        <option>
                            Semua stok
                        </option>

                        <option>
                            Stok Aman
                        </option>

                        <option>
                            Stok Menipis
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <table class="table align-middle mb-0">

                <thead style="background-color: #F4ECEC;">

                    <tr>

                        <th class="p-3">Produk</th>
                        <th>Kategori</th>
                        <th>Harga beli</th>
                        <th>Harga jual</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($product as $p)

                    <tr>

                        <td class="p-3 fw-semibold">
                            {{ $p->product_name }}
                        </td>

                        <td>
                            {{ optional($p->category)->category_name ?? '-' }}
                        </td>

                        <td>
                            Rp.{{ number_format($p->purchase_price, 0, ',', '.') }}
                        </td>

                        <td>
                            Rp.{{ number_format($p->selling_price, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $p->initial_stock }} {{ $p->unit }}
                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- EDIT --}}
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $p->product_id }}">

                                    <i class="bi bi-pencil-fill"></i>

                                </button>

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    class="btn btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $p->product_id }}">

                                    <i class="bi bi-trash-fill"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                    {{-- DELETE MODAL --}}
                    <div
                        class="modal fade"
                        id="deleteModal{{ $p->product_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-body p-4 text-center">

                                    <h5 class="fw-bold mb-3">
                                        Hapus Produk
                                    </h5>

                                    <p class="text-muted">
                                        Yakin ingin menghapus produk ini?
                                    </p>

                                    <form
                                        action="{{ route('produk.destroy', $p->product_id) }}"
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
                        id="editModal{{ $p->product_id }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-header border-0">

                                    <h5 class="fw-bold">
                                        Edit Produk
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <form
                                    action="{{ route('produk.update', $p->product_id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">

                                        <input
                                            type="text"
                                            name="nama_produk"
                                            value="{{ $p->product_name }}"
                                            class="form-control mb-3"
                                            placeholder="Nama Produk">

                                        <input
                                            type="number"
                                            name="harga_beli"
                                            value="{{ $p->purchase_price }}"
                                            class="form-control mb-3"
                                            placeholder="Harga Beli">

                                        <input
                                            type="number"
                                            name="harga_jual"
                                            value="{{ $p->selling_price }}"
                                            class="form-control mb-3"
                                            placeholder="Harga Jual">

                                        <input
                                            type="text"
                                            name="stok_awal"
                                            value="{{ $p->initial_stock }}"
                                            class="form-control mb-3"
                                            placeholder="Stok">

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
<div
    class="modal fade"
    id="inputModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0">

                <h5 class="fw-bold">
                    Tambah Produk
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                action="{{ route('produk.store') }}"
                method="POST">

                @csrf

                <div class="modal-body">

                    <input
                        type="text"
                        name="product_name"
                        class="form-control mb-3"
                        placeholder="Nama Produk">

                    <select
                        name="category_id"
                        class="form-select mb-3">

                        <option selected disabled>
                            Pilih kategori
                        </option>

                        @foreach ($category as $c)

                            <option value="{{ $c->category_id }}">
                                {{ $c->category_name }}
                            </option>

                        @endforeach

                    </select>

                    <select
                        name="supplier_id"
                        class="form-select mb-3">

                        <option selected disabled>
                            Pilih supplier
                        </option>

                        @foreach ($supplier as $s)

                            <option value="{{ $s->supplier_id }}">
                                {{ $s->supplier_name }}
                            </option>

                        @endforeach

                    </select>

                    <input
                        type="number"
                        name="purchase_price"
                        class="form-control mb-3"
                        placeholder="Harga Beli">

                    <input
                        type="number"
                        name="selling_price"
                        class="form-control mb-3"
                        placeholder="Harga Jual">

                    <input
                        type="text"
                        name="initial_stock"
                        class="form-control mb-3"
                        placeholder="Stok Awal">

                    <input
                        type="text"
                        name="minimum_stock"
                        class="form-control mb-3"
                        placeholder="Stok Minimum">

                    <input
                        type="text"
                        name="unit"
                        class="form-control"
                        placeholder="Satuan">

                </div>

                <div class="modal-footer border-0">

                    <button
                        type="submit"
                        class="btn text-white px-4"
                        style="background-color: #17354D;">

                        Tambahkan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection