<div class="text-white p-3 vh-100" 
style="width: 250px; background-color: #1B3C53">

    <h3>Inventori<span style="color: #FFCC00">Ku</span></h3>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="/dashboard" class="nav-link text-white">
                Home
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/produk" class="nav-link text-white">
                Produk
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/kategori" class="nav-link text-white">
                Kategori
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/supplier" class="nav-link text-white">
                Supplier
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/pre_order" class="nav-link text-white">
                PO
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/daftar_stok" class="nav-link text-white">
                Daftar Stok
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/profile" class="nav-link text-white">
                Profile
            </a>
        </li>

    </ul>

    <hr>

    <p>
        {{ Auth::user()->name }}
    </p>

    <form action="/logout" method="POST">
        @csrf

        <button class="btn btn-danger w-100">
            Logout
        </button>
    </form>

</div>