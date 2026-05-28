@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div>

    {{-- Header --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Dashboard
        </h3>

        <small class="text-muted">
            Hai, Ndo, Agus, Jaki Selamat Datang di InventoriKu
        </small>

    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-box fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">Produk</small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-stack fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">Total Unit Stock</small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-shop fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">Low Stock</small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-building fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">Gudang</small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-receipt-cutoff fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">PO Pending</small>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body d-flex align-items-center gap-3">

                    <i class="bi bi-truck fs-2"></i>

                    <div>
                        <h5 class="fw-bold mb-0">0</h5>
                        <small class="text-muted">Supplier</small>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Charts --}}
    <div class="row g-3">

        {{-- Line Chart --}}
        <div class="col-md-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <h4 class="fw-bold text-center mb-4">
                        Stock Movement
                    </h4>

                    <canvas id="stockChart" height="120"></canvas>

                </div>

            </div>

        </div>

        {{-- Doughnut Chart --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <h4 class="fw-bold text-center mb-4">
                        Category Chart
                    </h4>

                    <canvas id="categoryChart"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Chart JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // STOCK MOVEMENT
    const stockCtx = document.getElementById('stockChart');

    new Chart(stockCtx, {

        type: 'line',

        data: {

            labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8'],

            datasets: [

                {
                    label: 'Stock Masuk',
                    data: [40, 47, 61, 48, 67, 52, 47, 57],
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,128,0,0.1)',
                    tension: 0.4,
                    fill: true
                },

                {
                    label: 'Stock Keluar',
                    data: [10, 18, 33, 19, 39, 25, 18, 29],
                    borderColor: 'red',
                    backgroundColor: 'rgba(255,0,0,0.1)',
                    tension: 0.4,
                    fill: true
                }

            ]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    });

    // CATEGORY CHART
    const categoryCtx = document.getElementById('categoryChart');

    new Chart(categoryCtx, {

        type: 'doughnut',

        data: {

            labels: ['Makanan', 'Minuman', 'Bumbu Dapur'],

            datasets: [{

                data: [75, 15, 35],

                backgroundColor: [
                    'green',
                    'navy',
                    'red'
                ],

                borderWidth: 5,
                borderRadius: 10

            }]

        },

        options: {

            cutout: '70%',

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });

</script>

@endsection