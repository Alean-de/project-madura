@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Purchase Order (PO)</h3>
            <p class="text-muted small mb-0">Kelola pemesanan barang ke vendor supplier, pantau status pengiriman, dan tinjau rincian biaya.</p>
        </div>

        {{-- Button Tambah PO --}}
        <button class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 card-hover-animate" style="background-color: #17354D" data-bs-toggle="modal" data-bs-target="#modalTambahPO">
            <i class="bi bi-plus-lg fw-bold"></i> Buat PO Baru
        </button>
    </div>

    {{-- FILTER --}}
    <form action="{{ route('po.index') }}" method="GET" id="filterForm">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    
                    <form action="{{ route('po.index') }}" method="GET">
                        {{-- 1. Pencarian --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" 
                                    placeholder="Cari nomor PO atau nama supplier..." 
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- 2. Filter Supplier --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Filter Supplier</label>
                            <select name="supplier_id" class="form-select bg-light filter-submit">
                                <option value="">Semua Supplier</option>
                                @foreach ($suppliers ?? [] as $s)
                                    <option value="{{ $s->supplier_id }}" {{ request('supplier_id') == $s->supplier_id ? 'selected' : '' }}>
                                        {{ $s->supplier_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Status Dokumen --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Status Dokumen</label>
                            <select name="status" class="form-select bg-light"> 
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Proses)</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </form>

    {{-- TABLE PURCHASE ORDERS --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light text-uppercase fs-7 tracking-wider text-muted border-bottom">
                        <tr>
                            <th class="p-3 ps-4" style="width: 140px;">No. PO</th>
                            <th>Tanggal Transaksi</th>
                            <th>Vendor Supplier</th>
                            <th class="text-center">Kuantitas Muatan</th>
                            <th class="text-center" style="width: 180px;">Status Alur</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @if(empty($purchase_orders) || $purchase_orders->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt text-light-emphasis display-5 d-block mb-2"></i>
                                    Belum terdapat berkas riwayat rekaman Purchase Order.
                                </td>
                            </tr>
                        @else
                            @foreach ($purchase_orders as $po)
                                {{-- BARIS UTAMA (Klik untuk Expand Detail) --}}
                                <tr data-bs-toggle="collapse" data-bs-target="#detail-po-{{ $po->id }}" class="align-middle position-relative transition-all" style="cursor: pointer;">
                                    <td class="p-3 ps-4">
                                        <span class="badge bg-light text-primary border px-2 py-1.5 rounded-2 font-monospace fs-7 fw-bold">
                                            {{ $po->po_number ?? 'PO-' . str_pad($po->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="text-secondary small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $po->created_at ? \Carbon\Carbon::parse($po->created_at)->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ optional($po->supplier)->supplier_name ?? '-' }}</span>
                                        <small class="text-muted fs-7"><i class="bi bi-geo-alt me-1"></i>{{ optional($po->supplier)->city ?? 'Domisili tidak tercatat' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3 fw-semibold">
                                            <i class="bi bi-box-seam text-secondary me-1"></i> {{ $po->detailPo->count() }} Item Jenis
                                        </span>
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation()">
                                        @php
                                            $statusValue = strtolower($po->status ?? 'pending');
                                            $bgColor = $statusValue == 'selesai' ? 'bg-success-subtle' : ($statusValue == 'pending' ? 'bg-warning-subtle' : 'bg-danger-subtle');
                                            $textColor = $statusValue == 'selesai' ? 'text-success' : ($statusValue == 'pending' ? 'text-warning' : 'text-danger');
                                        @endphp
                                        <select class="form-select form-select-sm border-0 fw-bold rounded-pill px-3 py-1.5 text-center {{ $bgColor }} {{ $textColor }} status-select" 
                                                data-id="{{ $po->id }}" 
                                                style="cursor: pointer; -webkit-appearance: none; appearance: none;">
                                            <option value="pending" {{ $statusValue == 'pending' ? 'selected' : '' }}>● Pending</option>
                                            <option value="selesai" {{ $statusValue == 'selesai' ? 'selected' : '' }}>● Selesai</option>
                                            <option value="dibatalkan" {{ $statusValue == 'dibatalkan' ? 'selected' : '' }}>● Batal</option>
                                        </select>
                                    </td>
                                </tr>

                                {{-- BARIS DETAIL EXPANDABLE --}}
                                <tr class="collapse-row bg-light-subtle">
                                    <td colspan="5" class="p-0 border-0">
                                        <div class="collapse" id="detail-po-{{ $po->id }}">
                                            <div class="px-4 py-3 border-start border-primary border-4 m-3 bg-white shadow-sm rounded-3">
                                                <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                                                    <i class="bi bi-card-list fs-5"></i>
                                                    <h6 class="fw-bold mb-0">Rincian Nota Pembelian Komoditas:</h6>
                                                </div>
                                                
                                                <div class="table-responsive border rounded-3 overflow-hidden">
                                                    <table class="table table-sm table-hover mb-0 align-middle">
                                                        <thead class="table-light fs-7 text-uppercase tracking-wider text-muted border-bottom">
                                                            <tr>
                                                                <th class="text-center py-2" style="width: 50px;">No</th>
                                                                <th>Nama Spesifikasi Produk</th>
                                                                <th class="text-center" style="width: 100px;">Qty</th>
                                                                <th class="text-center" style="width: 120px;">Satuan</th>
                                                                <th class="text-end" style="width: 160px;">Harga Satuan</th>
                                                                <th class="text-end" style="width: 180px;">Subtotal Bersih</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="border-0">
                                                            @if($po->detailPo->isEmpty())
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted py-3 small">
                                                                        <i class="bi bi-exclamation-circle me-1"></i> Lembar perincian item produk tidak ditemukan.
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                @foreach($po->detailPo as $index => $detail)
                                                                    <tr class="border-bottom-0">
                                                                        <td class="text-center text-muted small py-2">{{ $index + 1 }}</td>
                                                                        <td>
                                                                            <span class="fw-bold text-dark">{{ optional($detail->product)->product_name ?? 'Produk Tidak Diketahui' }}</span>
                                                                        </td>
                                                                        <td class="text-center fw-bold text-primary">{{ $detail->quantity }}</td>
                                                                        <td class="text-center"><span class="badge bg-light text-secondary border px-2 py-1 rounded-2">{{ $detail->uom ?? 'PCS' }}</span></td>
                                                                        <td class="text-end fw-semibold text-dark">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                                                        <td class="text-end fw-extrabold text-dark">Rp {{ number_format($detail->quantity * $detail->unit_price, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr class="table-light border-top border-2 fw-bold">
                                                                    <td colspan="5" class="text-end p-2.5 text-uppercase fs-7 text-muted tracking-wide">Akmulasi Grand Total Pajak & Biaya:</td>
                                                                    <td class="text-end p-2.5 text-danger fw-black fs-5">Rp {{ number_format($po->total_price, 0, ',', '.') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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

{{-- MODAL TAMBAH PURCHASE ORDER --}}
<div class="modal fade" id="modalTambahPO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-down text-primary fs-4"></i> Registrasi Formulir PO Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('po.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Pilih Vendor Supplier</label>
                            <select name="supplier_id" class="form-select py-2 rounded-3" required>
                                <option value="" selected disabled>Tentukan target supplier utama</option>
                                {{-- @foreach ($suppliers as $s)
                                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_name }}</option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Tanggal Ekspedisi Order</label>
                            <input type="date" name="date" class="form-control py-2 rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Status Awal Dokumen</label>
                            <select name="status" class="form-select py-2 rounded-3" required>
                                <option value="pending" selected>Pending (Tahap Proses)</option>
                                <option value="selesai">Selesai (Barang Langsung Diterima)</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                    </div>

                    <div class="my-4 border-bottom"></div>

                    {{-- BAGIAN INPUT PRODUK DINAMIS --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-boxes text-secondary"></i> Alokasi Daftar Komoditas Barang
                        </h6>
                        <button type="button" id="addProduct" class="btn btn-sm btn-success px-3 py-1.5 rounded-3 border-0 shadow-sm d-flex align-items-center gap-1 card-hover-animate">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Muatan Barang
                        </button>
                    </div>

                    <div class="table-responsive border rounded-3 bg-light-subtle max-height-form-table">
                        <table class="table table-borderless mb-0 align-middle" id="productTable">
                            <thead class="table-light fs-7 text-uppercase tracking-wider text-muted border-bottom">
                                <tr>
                                    <th class="ps-3" style="width: 65%;">Spesifikasi Produk</th>
                                    <th style="width: 23%;">Kuantitas (Qty)</th>
                                    <th class="text-center" style="width: 12%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Baris Pertama Default --}}
                                <tr>
                                    <td class="ps-3">
                                        <select name="products[0][product_id]" class="form-select" required>
                                            <option value="" selected disabled>Pilih komoditas barang</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->product_id }}">{{ $product->name ?? $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="products[0][qty]" class="form-control" min="1" placeholder="0" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-light text-secondary border remove-row p-2 rounded-3" style="cursor:not-allowed;" disabled>×</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- TEMPLATE JAVASCRIPT --}}
                    <template id="productRowTemplate">
                        <tr class="border-top-light">
                            <td class="ps-3">
                                <select name="products[__index__][product_id]" class="form-select" required>
                                    <option value="" selected disabled>Pilih komoditas barang</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name ?? $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[__index__][qty]" class="form-control" min="1" placeholder="0" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger remove-row p-2 rounded-3">×</button>
                            </td>
                        </tr>
                    </template>
                </div>

                <div class="modal-footer border-0 pb-4 px-4 pt-3">
                    <button type="button" class="btn btn-light px-4 py-2 text-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 py-2 border-0 rounded-3 shadow-sm" style="background-color: #17354D">Kirim Validasi Dokumen</button>
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
    .fw-black {
        font-weight: 900;
    }
    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
    }
    .border-top-light {
        border-top: 1px solid #e9ecef;
    }
    .max-height-form-table {
        max-height: 280px;
        overflow-y: auto;
    }
</style>

@push('scripts')
    <script src="{{ asset('js/po.js') }}?v={{ time() }}"></script>
@endpush