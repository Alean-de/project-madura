<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <title>@yield('title', 'InventoriKu')</title>
    
    {{-- Bootstrap CSS & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles') 

    {{-- STYLE GLOBAL & RESPONSIF SIDEBAR-FOOTER --}}
    <style>
        /* Kerangka dasar Flexbox Sticky Footer */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Default Layar Besar (PC / Laptop) */
        main, .main-content {
            flex: 1 0 auto;
            margin-left: 250px !important;
            padding-bottom: 1rem;
            transition: margin 0.2s ease-in-out;
        }

        .footer {
            flex-shrink: 0;
            margin-left: 250px !important;
            transition: margin 0.2s ease-in-out;
        }

        /* Otomatis Menyesuaikan saat dibuka di Layar HP (Mobile) */
        @media (max-width: 767.98px) {
            main, .main-content, .footer {
                margin-left: 0 !important; /* Konten & Footer penuh di HP */
                padding-top: 15px;
            }
        }

        .fs-7 {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

    {{-- INJECT SIDEBAR COMPONENT --}}
    @include('partials.sidebar')

    {{-- KONTEN UTAMA HALAMAN --}}
    <main class="main-content p-4">
        @yield('content')
    </main>

    {{-- FOOTER COPYRIGHT --}}
    <footer class="footer mt-auto py-3 bg-transparent">
        <div class="container-fluid px-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-muted small border-top pt-3">
                <div>
                    <span class="fw-semibold text-dark">Inventori<span style="color: #FFB703;">Ku</span></span> 
                    &copy; {{ date('Y') }}. All Rights Reserved.
                </div>
                <div class="d-flex gap-3 text-secondary fs-7">
                    <span>Developed by Group 5</span>
                    <span class="text-muted">|</span>
                    <span class="fw-medium">v1.0.0</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- JAVASCRIPT GLOBAL DEPENDENCIES --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Global JavaScript Token Setup untuk AJAX Laravel --}}
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @stack('scripts')

</body>
</html>