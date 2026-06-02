@extends('layouts.app')

@section('title', 'Daftar Stok')

@section('content')

<div>

    {{-- Header --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Stok
        </h3>

        <small class="text-muted">
            Monitor stok produk
        </small>

    </div>

    {{-- Card Statistik --}}
    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center justify-content-center gap-3">

                    <i class="bi bi-box-seam fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">
                            Total Unit Stock
                        </small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center justify-content-center gap-3">

                    <i class="bi bi-building fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">
                            Low Stock
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-5">
                    <input type="text"
                        class="form-control"
                        placeholder="Cari produk...">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>Semua Stok</option>
                        <option>Cukup</option>
                        <option>Tipis</option>
                        <option>Rendah</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <select class="form-select">
                        <option>Semua gudang</option>
                        <option>Gudang A</option>
                        <option>Gudang B</option>
                    </select>
                </div>

            </div>

        </div>

    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <table class="table align-middle mb-0">

                <thead style="background-color: #F4ECEC;">

                    <tr>

                        <th class="p-3">SKU</th>
                        <th>Produk</th>
                        <th>Stok</th>
                        <th>Min</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td class="p-3">PRD-001</td>
                        <td class="fw-semibold">Yakult</td>
                        <td>3 Pack</td>
                        <td>3 Pack</td>

                        <td>
                            <span class="badge bg-success">
                                Cukup
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </td>

                    </tr>

                    <tr>

                        <td class="p-3">PRD-004</td>
                        <td class="fw-semibold">Chuba</td>
                        <td>3pcs</td>
                        <td>10pcs</td>

                        <td>
                            <span class="badge bg-danger">
                                Rendah
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </td>

                    </tr>

                    <tr>

                        <td class="p-3">PRD-007</td>
                        <td class="fw-semibold">So Nice</td>
                        <td>12pcs</td>
                        <td>10pcs</td>

                        <td>
                            <span class="badge bg-success">
                                Cukup
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </td>

                    </tr>

                    <tr>

                        <td class="p-3">PRD-003</td>
                        <td class="fw-semibold">Ultra Milk</td>
                        <td>25pcs</td>
                        <td>15pcs</td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                Tipis
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </td>

                    </tr>

                    <tr>

                        <td class="p-3">PRD-002</td>
                        <td class="fw-semibold">Sasa</td>
                        <td>25pcs</td>
                        <td>5pcs</td>

                        <td>
                            <span class="badge bg-success">
                                Cukup
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection