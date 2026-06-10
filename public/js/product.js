let products = [];
let searchTimeout;
let selectedProductId = null;

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadProductItems();

    $('#searchProduct').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            loadProductItems();
        }, 500);
    });

    $('#filterCategory, #filterSupplier, #filterStock').on('change', function () {
        loadProductItems();
    });

});

function loadProductItems(page = 1) {
    $('#productTableBody').html('<tr><td colspan="8" class="text-center">Memuat data...</td></tr>');

    $.ajax({
        url: '/product',
        method: 'GET',
        dataType: 'json',
        data: {
            page: page,
            search: $('#searchProduct').val(),
            category_id: $('#filterCategory').val(),
            supplier_id: $('#filterSupplier').val(),
            stock_status: $('#filterStock').val()
        },
        success: function (response) {
            products = response.data.data;
            
            let $tableBody = $('#productTableBody');            
            $tableBody.empty(); 

            if (!products || products.length === 0) {
                $tableBody.append('<tr><td colspan="8" class="text-center">Tidak ada data produk.</td></tr>');
                $('#paginationLinks').empty();
                return;
            }

            products.forEach(function (product, index) {
                let row = `
                    <tr>
                        <td class="text-center">
                            ${(response.data.current_page - 1) * response.data.per_page + index + 1}
                        </td>
                        <td class="text-center">${product.product_name}</td>
                        <td class="text-center">${product.unit}</td>
                        <td class="text-center">${product.categories?.category_name || '-'}</td>
                        <td class="text-center">${formatRupiah(product.purchase_price)}</td>
                        <td class="text-center">${formatRupiah(product.selling_price)}</td>
                        <td class="text-center">${product.minimum_stock}</td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-light border-0 text-primary p-2 rounded-3 btn-edit"
                                data-id="${product.id}">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-light border-0 text-danger p-2 rounded-3 btn-delete"
                                data-id="${product.id}">
                                <i class="bi bi-trash3 fs-5"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $tableBody.append(row);
            });
            // console.error('Gagal memuat data tabel produk:', xhr.responseJSON);

            renderPagination(response.data); // Render tombol halaman
        },
        error: function (xhr) {
            console.error('Gagal memuat data', xhr.responseJSON);   
            $('#productTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>');
        }
    });
}

$('#formNewProduct').on('submit', function (e) {
    e.preventDefault();
    let $form = $(this);
    let $submitBtn = $form.find('button[type="submit"]');
    
    $submitBtn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: '/product/create',
        method: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                bootstrap.Modal
                    .getInstance(
                        document.getElementById('inputModal')
                    )
                    .hide();

                $form[0].reset();

                loadProductItems();
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                alert(Object.values(errors)[0][0]);
            } else {
                alert('Gagal menyimpan produk. Periksa kembali rute URL Anda.');
            }
        },
        complete: function () {
            $submitBtn.prop('disabled', false).text('Simpan Produk');
        }
    });
});

$(document).on('click', '.btn-edit', function () {
    const productId = $(this).data('id');
    const product = products.find(p => p.id == productId);

    if (!product) {
        alert('Produk tidak ditemukan');
        return;
    }

    $('#edit_product_id').val(product.id);
    $('#edit_product_name').val(product.product_name);
    $('#edit_unit').val(product.unit);
    $('#edit_purchase_price').val(product.purchase_price);
    $('#edit_selling_price').val(product.selling_price);
    $('#edit_minimum_stock').val(product.minimum_stock);
    $('#edit_category_id').val(product.category_id);
    $('#edit_supplier_id').val(product.supplier_id);

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditProduct')).show();
});

$('#formEditProduct').on('submit', function (e) {
    e.preventDefault();

    const productId = $('#edit_product_id').val();
    let $submitBtn = $(this).find('button[type="submit"]');
    $submitBtn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: `/product/${productId}`,
        method: 'POST',
        data: {
            _method: 'PUT', // Spoofing method demi laravel route PUT
            product_name: $('#edit_product_name').val(),
            category_id: $('#edit_category_id').val(),
            supplier_id: $('#edit_supplier_id').val(),
            unit: $('#edit_unit').val(),
            purchase_price: $('#edit_purchase_price').val(),
            selling_price: $('#edit_selling_price').val(),
            minimum_stock: $('#edit_minimum_stock').val()
        },
        dataType: 'json',
        success: function (response) {
            alert(response.message || 'Produk berhasil diupdate!');
            bootstrap.Modal.getInstance(document.getElementById('modalEditProduct')).hide();
            loadProductItems();
        },
        error: function (xhr) {
            console.error(xhr.responseJSON);
            alert('Gagal mengupdate produk');
        },
        complete: function () {
            $submitBtn.prop('disabled', false).text('Simpan Produk');
        }
    });
});

$(document).on('click', '.btn-delete', function () {

    selectedProductId = $(this).data('id');

    bootstrap.Modal
        .getOrCreateInstance(
            document.getElementById('deleteProductModal')
        )
        .show();
});

$('#confirmDeleteProduct').on('click', function () {

    if (!selectedProductId) return;

    $.ajax({
        url: `/product/${selectedProductId}`,
        method: 'POST',
        data: {
            _method: 'DELETE'
        },
        dataType: 'json',

        success: function (response) {

            bootstrap.Modal
                .getInstance(
                    document.getElementById('deleteProductModal')
                )
                .hide();

            loadProductItems();

            alert(
                response.message ||
                'Produk berhasil dihapus!'
            );
        },

        error: function (xhr) {
            console.error(xhr.responseJSON);

            alert('Gagal menghapus produk.');
        }
    });

});

$(document).on('click', '.page-btn', function () {
    const page = $(this).data('page');
    loadProductItems(page);
});

$('#modalEditProduct').on('hidden.bs.modal', function () {
    $('#formEditProduct')[0].reset();
});

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}
function renderPagination(data) {
    let html = '';

    if (data.last_page <= 1) {
        $('#paginationLinks').html('');
        return;
    }

    for (let i = 1; i <= data.last_page; i++) {
        html += `
            <button
                class="btn btn-sm ${i === data.current_page ? 'btn-primary' : 'btn-outline-primary'} page-btn"
                data-page="${i}">
                ${i}
            </button>
        `;
    }

    $('#paginationLinks').html(html);
}