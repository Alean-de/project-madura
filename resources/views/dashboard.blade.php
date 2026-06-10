@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-1 py-2">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Dashboard</h3>
            <p class="text-muted small mb-0">
                Hai {{ auth()->user()->name }}! Selamat datang kembali di <span class="fw-bold" style="color: #FFB703;">InventoriKu</span>.
            </p>
        </div>
        <div>
            <span class="badge bg-white text-secondary border border-light-subtle px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center fw-medium">
                <i class="bi bi-calendar3 me-2 text-warning"></i>{{ date('d M Y') }}
            </span>
        </div>
    </div>

    {{-- Statistik Row --}}
    <div class="row g-3 mb-4">

        {{-- Card Produk --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-box fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">Produk</small>
                        <h4 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalProduk, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Unit Stock --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-info-subtle text-info rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-stack fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">Total Stok</small>
                        <h4 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalUnitStock, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Low Stock --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 {{ $lowStock > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">Low Stock</small>
                        <h4 class="fw-bold mb-0 mt-1 {{ $lowStock > 0 ? 'text-danger fw-bold' : 'text-dark' }}">{{ number_format($lowStock, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Gudang --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-secondary-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">Gudang</small>
                        <h4 class="fw-bold mb-0 text-dark mt-1">1</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card PO Pending --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 {{ $poPending > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }} rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">PO Pending</small>
                        <h4 class="fw-bold mb-0 text-dark mt-1">{{ number_format($poPending, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Supplier --}}
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover-animate">
                <div class="card-body d-flex align-items-center p-3 gap-3">
                    <div class="p-3 bg-dark-subtle text-dark rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold tracking-wider fs-7">Supplier</small>
                        <h4 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalSupplier, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts Row --}}
    <div class="row g-4">

        {{-- Line Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-1">Stock Movement</h5>
                        <p class="text-muted small mb-0">Arus logistik masuk dan keluar (6 Bulan Terakhir)</p>
                    </div>
                    <div style="position: relative; height: 320px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doughnut Chart --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Category Chart</h5>
                        <p class="text-muted small mb-4">Proporsi sebaran produk per kategori</p>
                    </div>
                    <div style="position: relative; height: 220px;" class="mx-auto w-100 mb-3">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div id="categoryLegendContainer"></div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Custom Styles --}}
<style>
    .card-hover-animate {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card-hover-animate:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.05) !important;
    }
    .fs-7 {
        font-size: 0.72rem;
    }
    .tracking-wider {
        letter-spacing: 0.06em;
    }
</style>

{{-- Chart JS Dependency --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // DEKLARASI VARIABEL GLOBAL UNTUK AJAX UPDATE
    window.stockChartInstance = null;
    window.categoryChartInstance = null;

    // 1. INITIALIZATION STOCK MOVEMENT CHART (LINE CHART)
    const stockCtx = document.getElementById('stockChart');
    if (stockCtx) {
        const ctx2d = stockCtx.getContext('2d');
        
        const greenGrad = ctx2d.createLinearGradient(0, 0, 0, 300);
        greenGrad.addColorStop(0, 'rgba(25, 135, 84, 0.15)');
        greenGrad.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

        const redGrad = ctx2d.createLinearGradient(0, 0, 0, 300);
        redGrad.addColorStop(0, 'rgba(220, 53, 69, 0.15)');
        redGrad.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

        window.stockChartInstance = new Chart(ctx2d, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!}, 
                datasets: [
                    {
                        label: 'Stock Masuk',
                        data: {!! json_encode($chartDataIn) !!}, 
                        borderColor: '#198754',
                        backgroundColor: greenGrad,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#198754',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Stock Keluar',
                        data: {!! json_encode($chartDataOut) !!}, 
                        borderColor: '#dc3545',
                        backgroundColor: redGrad,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3,
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
                            boxWidth: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: { size: 12, weight: '500' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#9ca3af', font: { size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // 2. INITIALIZATION CATEGORY CHART (DOUGHNUT CHART)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        window.categoryChartInstance = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($donutLabels) !!},
                datasets: [{
                    data: {!! json_encode($donutData) !!},
                    backgroundColor: [
                        '#4361ee', 
                        '#2ec4b6', 
                        '#e63946', 
                        '#ffb703', 
                        '#7209b7', 
                        '#6c757d'  
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            font: { size: 11, weight: '500' }
                        }
                    }
                }
            }
        });
    }

    // 3. REAL-TIME UPDATER FUNCTION VIA AJAX
    window.updateCategoryChart = function() {
        $.ajax({
            url: '/administrator/get-chart-data', 
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (window.categoryChartInstance) {
                    window.categoryChartInstance.data.labels = response.labels;
                    window.categoryChartInstance.data.datasets[0].data = response.data;
                    window.categoryChartInstance.update();
                }
            },
            error: function(xhr) {
                console.error('Gagal fetch data kategori terbaru:', xhr);
            }
        });
    };

    if (typeof window.updateCategoryChart === 'function') {
        window.updateCategoryChart();
    }
</script>
@endsection