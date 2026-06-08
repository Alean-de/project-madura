let adjustments = [];
let searchTimeout;
// Ambil base URL dari meta tag agar aman di file JS eksternal
const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');

$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Jalankan pertama kali saat halaman siap
    loadAdjustmentItems();

    // Event Handler untuk Pencarian Teks (Debounce 0.5 detik)
    $('#searchProduct').on('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            loadAdjustmentItems();
        }, 500);
    });

    // Event Handler untuk Filter Dropdown Status Atas
    $('#filterStatus').on('change', function () {
        loadAdjustmentItems();
    });

    // Reset Form otomatis saat Modal Tambah ditutup
    $('#adjustmentModal').on('hidden.bs.modal', function () {
        $('#formAdjustment')[0].reset();
        $('#adj_product_id').val('').trigger('change');
    });
});

// =========================================================================
// 1. READ (LOAD DATA TABEL & PAGINASI VIA AJAX)
// =========================================================================
function loadAdjustmentItems(page = 1) {
    $('#adjustmentTableBody').html('<tr><td colspan="4" class="text-center py-4 text-muted">Memuat data log mutasi...</td></tr>');

    $.ajax({
        url: `${baseUrl}/inventoryadjustment`, 
        method: 'GET',
        dataType: 'json',
        data: {
            page: page,
            search: $('#searchProduct').val(),
            status: $('#filterStatus').val()
        },
        success: function (response) {
            adjustments = response.data.data;
            let $tableBody = $('#adjustmentTableBody');
            $tableBody.empty();

            if (!adjustments || adjustments.length === 0) {
                $tableBody.append('<tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-folder-x display-5 d-block mb-2 text-light-emphasis"></i>Tidak ada data record log adjustment.</td></tr>');
                $('#paginationLinks').empty();
                return;
            }

            adjustments.forEach(function (item) {
                let badgeClass = 'bg-secondary-subtle text-secondary';
                let statusText = item.status.replace('_', ' ').toUpperCase();

                if (item.status === 'barang_masuk') badgeClass = 'bg-success-subtle text-success';
                else if (item.status === 'barang_keluar') badgeClass = 'bg-danger-subtle text-danger';
                else if (item.status === 'pending') badgeClass = 'bg-warning-subtle text-warning';
                else if (item.status === 'rusak' || item.status === 'exp') badgeClass = 'bg-dark-subtle text-dark';

                let productName = item.product ? (item.product.product_name || item.product.name || '-') : '-';
                let productUnit = item.product && item.product.unit ? `<small class="text-muted fs-7 d-block">Unit: ${item.product.unit}</small>` : '';

                let formattedDate = '-';
                if (item.exp_date) {
                    const dateObj = new Date(item.exp_date);
                    if (!isNaN(dateObj)) {
                        formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    }
                }

                // Sekarang hanya menampilkan 4 kolom (Tanpa kolom Aksi Edit/Delete)
                let row = `
                    <tr>
                        <td class="p-3 ps-4">
                            <span class="fw-bold text-dark d-block">${productName}</span>
                            ${productUnit}
                        </td>
                        <td class="text-secondary small"><i class="bi bi-calendar-event me-1"></i>${formattedDate}</td>
                        <td class="fw-bold text-dark">${item.qty}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold small ${badgeClass}">● ${statusText}</span>
                        </td>
                    </tr>
                `;
                $tableBody.append(row);
            });

            // Jalankan fungsi pembuat tombol halaman paginasi
            renderPagination(response.data);
        },
        error: function (xhr) {
            console.error("Gagal memuat data tabel:", xhr.responseText);
            $('#adjustmentTableBody').html('<tr><td colspan="4" class="text-center text-danger py-4"><i class="bi bi-exclamation-octagon me-1"></i> Gagal memuat data dari server.</td></tr>');
        }
    });
}

// =========================================================================
// 2. CREATE SUBMIT FORM (Hanya untuk Tambah Data Baru)
// =========================================================================
$('#formAdjustment').on('submit', function (e) {
    e.preventDefault();
    let $form = $(this);
    let $submitBtn = $('#btnSubmit');
    $submitBtn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('adjustmentModal')).hide();
                loadAdjustmentItems(); // Reload tabel ke halaman 1 agar data terbaru muncul di paling atas
            }
        },
        error: function (xhr) {
            console.error("Error Detail:", xhr.responseText);
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                let errorMessages = '';
                $.each(xhr.responseJSON.errors, function (key, messages) {
                    errorMessages += messages.join('\n') + '\n';
                });
                alert('Gagal Validasi:\n' + errorMessages);
            } else {
                alert('Gagal memproses data.');
            }
        },
        complete: function () {
            $submitBtn.prop('disabled', false).text('Simpan');
        }
    });
});

// =========================================================================
// 3. LOGIKA TOMBOL PAGINASI KLIK
// =========================================================================
$(document).on('click', '.page-btn', function () {
    const page = $(this).data('page');
    loadAdjustmentItems(page);
});

function renderPagination(data) {
    let html = '';
    // Jika total data sedikit dan hanya menghasilkan 1 halaman, sembunyikan navigasi paginasi
    if (data.last_page <= 1) {
        $('#paginationLinks').html('');
        return;
    }

    // Tombol Halaman Angka Dinamis bawaan data Pagination Laravel
    for (let i = 1; i <= data.last_page; i++) {
        let activeClass = i === data.current_page ? 'btn-primary fw-bold' : 'btn-light text-secondary border';
        html += `<button class="btn btn-sm px-3 ${activeClass} page-btn" data-page="${i}">${i}</button>`;
    }
    $('#paginationLinks').html(html);
}