<div class="text-white p-3 position-fixed shadow-sm d-flex flex-column" 
     style="
        width: 250px;
        height: 100vh;
        background-color: #17354D;
        overflow-y: auto;
        left: 0;
        top: 0;
        z-index: 1030;
     ">

    {{-- LOGO BRAND --}}
    <div class="py-3 px-2 mb-3 border-bottom border-secondary border-opacity-25">
        <h4 class="fw-black tracking-tight mb-0 text-white d-flex align-items-center gap-2">
            <i class="bi bi-box-seam-fill text-warning fs-4"></i>
            <span>Inventori<span style="color: #FFCC00">Ku</span></span>
        </h4>
    </div>

    {{-- NAVIGATION LINKS --}}
    <ul class="nav flex-column flex-grow-1 gap-1">

        {{-- Menu Dashboard / Home --}}
        <li class="nav-item">
            <a href="/dashboard" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->is('dashboard*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-grid-1x2-fill fs-5"></i>
                <span>Home</span>
            </a>
        </li>

        {{-- Menu Produk --}}
        <li class="nav-item">
            <a href="/product" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->is('product*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-box fs-5"></i>
                <span>Produk</span>
            </a>
        </li>

        {{-- Menu Kategori --}}
        <li class="nav-item">
            <a href="{{ route('category.index') }}" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->routeIs('category.*') || request()->is('kategori*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-tags fs-5"></i>
                <span>Kategori</span>
            </a>
        </li>

        {{-- Menu Supplier --}}
        <li class="nav-item">
            <a href="/supplier" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->is('supplier*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-truck fs-5"></i>
                <span>Supplier</span>
            </a>
        </li>

        {{-- Menu PO --}}
        <li class="nav-item">
            <a href="{{ route('po.index') }}" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->routeIs('po.*') || request()->is('purchase-order*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-file-earmark-text fs-5"></i>
                <span>Purchase Order</span>
            </a>
        </li>

        {{-- Menu Daftar Stok --}}
        <li class="nav-item">
            <a href="/daftar_stok" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->is('daftar_stok*') || request()->is('stok*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-clipboard-data fs-5"></i>
                <span>Daftar Stok</span>
            </a>
        </li>
        
        {{-- Menu Inventory Adjustment --}}
        <li class="nav-item">
            <a href="{{ route('adjustment.index') }}" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->routeIs('adjustment.*') || request()->is('inventoryadjustment*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-sliders fs-5"></i>
                <span>Adjustment Log</span>
            </a>
        </li>

        {{-- Menu Profile --}}
        <li class="nav-item">
            <a href="/profile" class="nav-link rounded-3 px-3 py-2.5 transition-all text-white-50 d-flex align-items-center gap-3 {{ request()->is('profile*') ? 'sidebar-active text-white fw-bold' : 'sidebar-link-hover' }}">
                <i class="bi bi-person-circle fs-5"></i>
                <span>Profile</span>
            </a>
        </li>

    </ul>

    {{-- FOOTER / USER ACCOUNT SECTION --}}
    <div class="mt-auto pt-3 border-top border-secondary border-opacity-25">
        
        <div class="d-flex align-items-center gap-2 mb-2 px-2 py-2 bg-white bg-opacity-5 rounded-3 border border-secondary border-opacity-10">
            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.85rem;">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <small class="text-black-50 fs-7 d-block mb-0 lh-1" style="font-size: 11px;">Logged in as</small>
                <span class="text-black fw-semibold text-truncate d-block small" style="max-width: 150px;">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 border-opacity-50 border-danger card-hover-animate" style="font-size: 0.9rem; font-weight: 500; border-radius: 8px;">
                <i class="bi bi-box-arrow-right fw-bold"></i>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>

</div>

{{-- Custom Style CSS untuk Efek Hover & Active State --}}
<style>
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .sidebar-link-hover:hover {
        background-color: rgba(255, 255, 255, 0.06);
        color: #ffffff !important;
    }
    .sidebar-active {
        background-color: rgba(255, 204, 0, 0.15) !important;
        color: #FFCC00 !important;
        border-left: 4px solid #FFCC00;
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
    }
    .fw-black {
        font-weight: 900;
    }
    .fs-7 {
        font-size: 0.72rem;
    }
</style>