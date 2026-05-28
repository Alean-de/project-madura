@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')

<div class="d-flex">


    {{-- Main Content --}}
    <div class="flex-grow-1 p-4" style="background-color: #f5f5f5; min-height: 100vh;">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">Purchase Order</h3>
                <small class="text-muted">
                    Kelola pembelian ke supplier
                </small>
            </div>

            {{-- Button Tambah PO --}}
            <button class="btn text-white px-4"
                style="background-color: #183B56"
                data-bs-toggle="modal"
                data-bs-target="#modalTambahPO">

                <i class="bi bi-plus-lg"></i> Buat PO
            </button>

        </div>

        {{-- Filter --}}
        <div class="row mb-4">

            <div class="col-md-4">
                <input type="text"
                    class="form-control"
                    placeholder="Cari supplier...">
            </div>

            <div class="col-md-3">
                <select class="form-select">
                    <option>Semua supplier</option>
                    <option>Toko Sinar Terang</option>
                    <option>Toko Berkat</option>
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-select">
                    <option>Semua status</option>
                    <option>Selesai</option>
                    <option>Pending</option>
                    <option>Batal</option>
                </select>
            </div>

        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-0">

                <table class="table align-middle mb-0">

                    <thead style="background-color: #F2EDED">

                        <tr>
                            <th class="p-3">No.PO</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Item</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td class="p-3">PO-202512-0002</td>
                            <td>31 Desember 2025</td>
                            <td>Toko Sinar Terang</td>
                            <td>17 item</td>
                            <td>
                                <span class="badge bg-success">
                                    Selesai
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">PO-202603-0003</td>
                            <td>11 Maret 2026</td>
                            <td>Toko Berkat</td>
                            <td>12 item</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">PO-202602-0004</td>
                            <td>15 Februari 2026</td>
                            <td>OJS Grosir</td>
                            <td>20 item</td>
                            <td>
                                <span class="badge bg-success">
                                    Selesai
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">PO-202602-0005</td>
                            <td>18 Februari 2026</td>
                            <td>XYZ Grosir</td>
                            <td>25 item</td>
                            <td>
                                <span class="badge bg-danger">
                                    Batal
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- Modal Tambah PO --}}
<div class="modal fade"
    id="modalTambahPO"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4 border-0">

            <div class="modal-body p-5">

                <h4 class="text-center fw-bold mb-5">
                    Tambah Purchase Order
                </h4>

                <form>

                    <div class="mb-3">
                        <input type="text"
                            class="form-control"
                            placeholder="Nama Supplier">
                    </div>

                    <div class="mb-3">
                        <input type="date"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <input type="text"
                            class="form-control"
                            placeholder="Item">
                    </div>

                    <div class="mb-4">
                        <select class="form-select">
                            <option>Status</option>
                            <option>Selesai</option>
                            <option>Pending</option>
                            <option>Batal</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center gap-3">

                        <button type="submit"
                            class="btn btn-success px-4">
                            Submit
                        </button>

                        <button type="button"
                            class="btn btn-danger px-4"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection