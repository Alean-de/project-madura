let stockItems = [];
let searchStockTimeout;

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load data pertama kali saat halaman dibuka
    loadStockItems();

    // Event Handler Pencarian Teks (Debounce 0.5 detik)
    $('#searchStock').on('input', function () {
        clearTimeout(searchStockTimeout);
        searchStockTimeout = setTimeout(function () {
            loadStockItems();
        }, 500);
    });

    // Event Handler Dropdown Filter Atas
    $('#filterStockKondisi, #filterStockCategory').on('change', function () {
        loadStockItems();
    });
});

// =========================================================================
// INTERFACE READ DATA VIA AJAX STOCK
// =========================================================================
function loadStockItems(page = 1) {
    $('#stockTableBody').html('<tr><td colspan="5" class="text-center py-4 text-muted">Memuat komoditas stok...</td></tr>');

    // Pastikan URL mengarah ke rute index StockController Anda (contoh rute: '/stock')
    $.ajax({
        url: '/daftar-stok/', 
        method: 'GET',
        dataType: 'json',
        data: {
            page: page,
            search: $('#searchStock').val(),
            stock_status: $('#filterStockKondisi').val(),
            category_id: $('#filterStockCategory').val()
        },
        success: function (response) {
            stockItems = response.data.data;
            let $tableBody = $('#stockTableBody');            
            $tableBody.empty(); 

            // UPDATE ANGKA CARD: Sekarang nilainya akan selalu mengunci total global
            if (response.totalUnitStock !== undefined) {
                let formattedTotal = new Intl.NumberFormat('id-ID').format(response.totalUnitStock);
                $('#statTotalStock').text(formattedTotal);
            }
            
            if (response.lowStockCount !== undefined) {
                let formattedLow = new Intl.NumberFormat('id-ID').format(response.lowStockCount);
                let $lowStockCard = $('#statLowStock');
                
                $lowStockCard.text(formattedLow);
                
                // Set warna permanen sesuai kondisi riil gudang global saat ini
                if (response.lowStockCount > 0) {
                    $lowStockCard.addClass('text-danger fw-extrabold').removeClass('text-dark');
                } else {
                    $lowStockCard.addClass('text-dark').removeClass('text-danger fw-extrabold');
                }
            }

            if (!stockItems || stockItems.length === 0) {
                $tableBody.append('<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ditemukan data komoditas stok produk.</td></tr>');
                $('#paginationLinks').empty();
                return;
            }

            stockItems.forEach(function (p) {
                // 1. Logika Pembuatan Kode SKU Otomatis bawaan Blade Anda sebelumnya
                let skuCode = p.sku ? p.sku : 'PRD-' + String(p.id).padStart(3, '0');
                
                // 2. Format Teks Angka Satuan Ukuran Gudang
                let currentStock = new Intl.NumberFormat('id-ID').format(p.initial_stock);
                let minStock = new Intl.NumberFormat('id-ID').format(p.minimum_stock);
                let categoryName = p.categories ? p.categories.category_name : 'Tanpa Kategori';

                // 3. Logika Penentuan Atribut Badge Status Kondisi Stok Fisik Aktual
                let badgeHTML = '';
                if (p.initial_stock <= 0) {
                    badgeHTML = '<span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-semibold small w-100">Habis</span>';
                } else if (p.initial_stock < p.minimum_stock) {
                    badgeHTML = '<span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-semibold small w-100">Rendah</span>';
                } else if (p.initial_stock == p.minimum_stock) {
                    badgeHTML = '<span class="badge bg-warning-subtle text-warning px-3 py-1.5 rounded-pill fw-semibold small w-100">Tipis</span>';
                } else {
                    badgeHTML = '<span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-semibold small w-100">Cukup</span>';
                }

                let row = `
                    <tr>
                        <td class="p-3 ps-4">
                            <span class="badge bg-light text-secondary border px-2 py-1.5 rounded-2 font-monospace fs-7">
                                ${skuCode}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark d-block">${p.product_name}</span>
                            <small class="text-muted fs-7"><i class="bi bi-tags me-1"></i>${categoryName}</small>
                        </td>
                        <td class="fw-bold text-dark">${currentStock} <span class="text-muted fw-normal small">${p.unit}</span></td>
                        <td class="text-secondary fw-semibold">${minStock} <span class="text-muted fw-normal small">${p.unit}</span></td>
                        <td class="text-center">${badgeHTML}</td>
                    </tr>
                `;
                $tableBody.append(row);
            });

            renderStockPagination(response.data);
        },
        error: function (xhr) {
            console.error('Gagal memuat data stok:', xhr.responseText); 
            $('#stockTableBody').html('<tr><td colspan="5" class="text-center text-danger py-4">Gagal memuat data dari server.</td></tr>');
        }
    });
}

// =========================================================================
// NAVIGASI TOMBOL HALAMAN PAGINASI STOK
// =========================================================================
$(document).on('click', '.stock-page-btn', function () {
    const page = $(this).data('page');
    loadStockItems(page);
});

function renderStockPagination(data) {
    let html = '';
    if (data.last_page <= 1) {
        $('#paginationLinks').html('');
        return;
    }

    for (let i = 1; i <= data.last_page; i++) {
        let activeClass = i === data.current_page ? 'btn-primary fw-bold' : 'btn-light text-secondary border';
        html += `<button class="btn btn-sm px-3 ${activeClass} stock-page-btn" data-page="${i}">${i}</button>`;
    }
    $('#paginationLinks').html(html);
}