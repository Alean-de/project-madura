@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
<div class="d-flex">
    {{-- Main Content --}}
    <div class="flex-grow-1 p-4" style="background-color: #f5f5f5; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Purchase Order</h3>
                <small class="text-muted">Kelola pembelian ke supplier</small>
            </div>

            {{-- Button Tambah PO --}}
            <button class="btn text-white px-4" style="background-color: #183B56" data-bs-toggle="modal" data-bs-target="#modalTambahPO">
                <i class="bi bi-plus-lg"></i> Buat PO
            </button>
        </div>

        {{-- FILTER --}}
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Cari supplier...">
            </div>

            <div class="col-md-4">
                <select class="form-select">
                    <option value="">Semua supplier</option>
                    @foreach ($supplier ?? [] as $s)
                        <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select class="form-select">
                    <option value="">Semua status</option>
                    <option value="pending">Pending</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Batal</option>
                </select>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead style="background-color: #F2EDED">
                        <tr>
                            <th class="p-3">No. PO</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Total Item</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($purchase_orders) || $purchase_orders->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada data purchase order.
                                </td>
                            </tr>
                        @else
                            @foreach ($purchase_orders as $po)
                            <tr>
                                <td class="p-3 fw-semibold text-muted">
                                    {{ $po->po_number ?? 'PO-' . str_pad($po->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($po->date)->translatedFormat('d F Y') }}</td>
                                <td class="fw-semibold">{{ optional($po->supplier)->supplier_name ?? '-' }}</td>
                                <td>{{ $po->total_items ?? 0 }} item</td>
                                <td>
                                    @if(($po->status ?? '') == 'selesai')
                                        <span class="badge bg-success px-3 py-2">Selesai</span>
                                    @elseif(($po->status ?? '') == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Batal</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH PO --}}
<div class="modal fade" id="modalTambahPO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Tambah Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('po.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Supplier</option>
                                @foreach ($supplier ?? [] as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Order</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah / Spesifikasi Item</label>
                            <input type="text" name="item" class="form-control" placeholder="Contoh: 15 item / Nama Item" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Awal</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" selected>Pending</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #183B56">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection