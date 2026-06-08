@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark tracking-tight mb-1">Dashboard</h3>
            <p class="text-muted small mb-0">
                Hai {{ explode(' ', auth()->user()->name)[0] }}! Selamat datang kembali di <span class="fw-semibold text-primary">InventoriKu</span>.
            </p>
        </div>
        <div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 small">
                <i class="bi bi-calendar3 me-2 text-primary"></i>{{ date('d M Y') }}
            </span>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        {{-- ================= ROW 1 (3 Card Pertama) ================= --}}
        {{-- Card Produk --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-box fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Produk</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalProduk, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Unit Stock --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-info-subtle text-info rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-stack fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Total Stok</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalUnitStock, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Low Stock --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 {{ $lowStock > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Low Stock</small>
                        <h4 class="fw-bold mb-0 {{ $lowStock > 0 ? 'text-danger fw-extrabold' : 'text-dark' }}">{{ number_format($lowStock, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= ROW 2 (3 Card Kedua) ================= --}}
        {{-- Card Gudang --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-secondary-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Gudang</small>
                        <h4 class="fw-bold mb-0 text-dark">1</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card PO Pending --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 {{ $poPending > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }} rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-receipt-cutoff fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">PO Pending</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($poPending, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Supplier --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-dark-subtle text-dark rounded-4 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider fs-7">Supplier</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalSupplier, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <div class="row g-4">

        {{-- Line Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Stock Movement</h5>
                            <p class="text-muted small mb-0">Arus logistik masuk dan keluar (6 Bulan Terakhir)</p>
                        </div>
                    </div>
                    <div style="position: relative; height: 320px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doughnut Chart --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Category Chart</h5>
                        <p class="text-muted small mb-3">Proporsi sebaran produk per kategori</p>
                    </div>
                    <div style="position: relative; height: 240px;" class="mb-3">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div id="categoryLegendContainer"></div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Tambahan CSS Custom Styles --}}
<style>
    .card-hover-animate {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover-animate:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .fs-7 {
        font-size: 0.72rem;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
    .fw-extrabold {
        font-weight: 800;
    }
</style>

{{-- Chart JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // STOCK MOVEMENT CHART
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    
    // Membuat warna gradasi transparan modern di bawah garis grafik
    const greenGrad = stockCtx.createLinearGradient(0, 0, 0, 300);
    greenGrad.addColorStop(0, 'rgba(25, 135, 84, 0.24)');
    greenGrad.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

    const redGrad = stockCtx.createLinearGradient(0, 0, 0, 300);
    redGrad.addColorStop(0, 'rgba(220, 53, 69, 0.24)');
    redGrad.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

    new Chart(stockCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!}, 
            datasets: [
                {
                    label: 'Stock Masuk',
                    data: {!! json_encode($chartDataIn) !!}, 
                    borderColor: '#198754',
                    backgroundColor: greenGrad,
                    borderWidth: 3,
                    pointBackgroundColor: '#198754',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'Stock Keluar',
                    data: {!! json_encode($chartDataOut) !!}, 
                    borderColor: '#dc3545',
                    backgroundColor: redGrad,
                    borderWidth: 3,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { size: 12, weight: '500' }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#6c757d', font: { size: 11 } }
                },
                y: {
                    grid: { color: '#f1f3f5' },
                    ticks: { color: '#6c757d', font: { size: 11 } },
                    beginAtZero: true
                }
            }
        }
    });

    // CATEGORY CHART (DOUGHNUT)
    const categoryCtx = document.getElementById('categoryChart');

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($donutLabels) !!},
            datasets: [{
                data: {!! json_encode($donutData) !!},
                backgroundColor: [
                    '#4361ee', // Royal Blue
                    '#2ec4b6', // Teal
                    '#e63946', // Coral Red
                    '#ffb703', // Amber Yellow
                    '#7209b7', // Purple
                    '#6c757d'  // Muted Gray
                ],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '78%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 15,
                        font: { size: 12, weight: '500' }
                    }
                }
            }
        }
    });
</script>
@include('partials.footer')
@endsection