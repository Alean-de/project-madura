let products = [];

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadProductItems();

    $('#searchProduct').on('keyup', function () {
        loadProductItems();
    });

    $('#filterCategory').on('change', function () {
        loadProductItems();
    });

    $('#filterSupplier').on('change', function () {
        loadProductItems();
    });

    $('#filterStock').on('change', function () {
        loadProductItems();
    });

});

function loadProductItems(page = 1) {

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

            if (products.length === 0) {
                $tableBody.append('<tr><td colspan="6" class="text-center">Tidak ada data produk.</td></tr>');
                $('#paginationLinks').empty();
                return;
            }

            products.forEach(function (product) {
                let row = `
                    <tr>
                        <td>${product.product_name}</td>
                        <td>${product.unit}</td>
                        <td>${product.categories?.category_name || '-'}</td>
                        <td>${product.suppliers?.supplier_name || '-'}</td>
                        <td>${formatRupiah(product.purchase_price)}</td>
                        <td>${formatRupiah(product.selling_price)}</td>
                        <td>${product.minimum_stock}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${product.product_id}">Edit</button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${product.product_id}">Hapus</button>
                        </td>
                    </tr>
                `;
                $tableBody.append(row);
            });

           renderPagination(response.data);

        },

        error: function (xhr) {
            console.error('Gagal memuat data tabel produk:', xhr.responseJSON);
        }
    });

}

$('#formNewProduct').on('submit', function (e) {

    e.preventDefault();

    let $submitBtn = $(this).find('button[type="submit"]');
    $submitBtn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: '/product/create',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',

        success: function (response) {

            if (response.success) {

                alert(response.message);

                $('#formNewProduct')[0].reset();

                loadProductItems();
            }
        },

        error: function (xhr) {

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                alert(Object.values(errors)[0][0]);
            } else {
                alert('Gagal menyimpan produk.');
            }

        },

        complete: function () {
            $submitBtn.prop('disabled', false).text('Simpan Produk');
        }

    });

});

$(document).on('click', '.btn-edit', function () {

    const productId = $(this).data('id');

    const product = products.find(
        p => p.product_id == productId
    );

    if (!product) {
        alert('Produk tidak ditemukan');
        return;
    }

    $('#edit_product_id').val(product.product_id);
    $('#edit_product_name').val(product.product_name);
    $('#edit_unit').val(product.unit);
    $('#edit_purchase_price').val(product.purchase_price);
    $('#edit_selling_price').val(product.selling_price);
    $('#edit_minimum_stock').val(product.minimum_stock);
    $('#edit_category_id').val(product.category_id);
    $('#edit_supplier_id').val(product.supplier_id);

    bootstrap.Modal
        .getOrCreateInstance(document.getElementById('modalEditProduct'))
        .show();

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
            _method: 'PUT',
            product_name: $('#edit_product_name').val(),
            category_id: $('#edit_category_id').val(),
            supplier_id: $('#edit_supplier_id').val(),
            unit: $('#edit_unit').val(),
            purchase_price: $('#edit_purchase_price').val(),
            selling_price: $('#edit_selling_price').val(),
            minimum_stock: $('#edit_minimum_stock').val()
        },

        success: function (response) {

            alert(response.message);

            bootstrap.Modal.getInstance(document.getElementById('modalEditProduct')).hide();

            loadProductItems();

        },

        error: function (xhr) {
            console.log(xhr.responseJSON);
            alert('Gagal mengupdate produk');
        },

        complete: function () {
            $submitBtn.prop('disabled', false).text('Simpan Produk');
        }

    });

});

$(document).on('click', '.btn-delete', function () {

    const productId = $(this).data('id');

    if (!confirm('Yakin hapus produk ini?')) {
        return;
    }

    $.ajax({
        url: `/product/${productId}`,
        method: 'POST',

        data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {
            alert(response.message);
            loadProductItems();
        }

    });

});

$(document).on('click', '.page-btn', function () {

    let page = $(this).data('page');

    loadProductItems(page);

});

$(document).on('click', '.page-btn', function () {

    const page = $(this).data('page');

    loadProductItems(page);

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
                class="btn btn-sm ${
                    i === data.current_page
                        ? 'btn-primary'
                        : 'btn-outline-primary'
                } page-btn"
                data-page="${i}">
                ${i}
            </button>
        `;
    }

    $('#paginationLinks').html(html);
}