let suppliers = [];
let selectedSupplierId = null;
let searchTimeout;

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    loadSuppliers();

    $('#searchSupplier').on('keyup', function () {

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            loadSuppliers();
        }, 500);

    });

});

function loadSuppliers(page = 1) {

    $.ajax({
        url: '/supplier',
        method: 'GET',
        dataType: 'json',
        data: {
            page,
            search: $('#searchSupplier').val()
        },

        success: function (response) {

            suppliers = response.data.data;

            let html = '';

            suppliers.forEach(function (supplier) {

                html += `
                <tr>

                    <td>SUP-${supplier.id}</td>

                    <td>${supplier.supplier_name}</td>

                    <td>${supplier.contacts}</td>

                    <td>${supplier.city}</td>

                    <td class="text-center">
 
                        <select
                            class="form-select form-select-sm border-0 fw-bold rounded-pill px-3 py-1 supplier-status
                                ${supplier.status
                                    ? 'bg-success-subtle text-success'
                                    : 'bg-danger-subtle text-danger'}"
                            data-id="${supplier.id}"
                            style="cursor:pointer; -webkit-appearance:none; appearance:none;">

                            <option value="1" ${supplier.status ? 'selected' : ''}>
                                Aktif
                            </option>

                            <option value="0" ${!supplier.status ? 'selected' : ''}>
                                Nonaktif
                            </option>

                        </select>

                    </td>

                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-sm btn-light border-0 text-primary p-2 rounded-3 btn-edit"
                            data-id="${supplier.id}">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-light border-0 text-danger p-2 rounded-3 btn-delete"
                            data-id="${supplier.id}">
                            <i class="bi bi-trash3 fs-5"></i>
                        </button>

                    </td>

                </tr>
                `;
            });

            $('#supplierTableBody').html(html);

            renderPagination(response.data);
        }
    });
}

$('#formTambahSupplier').on('submit', function (e) {
    e.preventDefault(); // Mencegah browser reload halaman halaman

    $.ajax({
        url: '/supplier/create', // Mengambil rute /supplier/create otomatis dari form
        method: 'POST',
        data: $(this).serialize(), // Otomatis membungkus semua input data (name, contacts, city, _token)
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // 1. Sembunyikan modal tambah jika berhasil
                bootstrap.Modal.getInstance(document.getElementById('supplierModal')).hide();
                
                // 2. Kosongkan input form agar bersih saat dibuka kembali nanti
                $('#formTambahSupplier')[0].reset();
                
                // 3. Reload isi tabel data supplier secara realtime
                loadSuppliers();
                
                // 4. Beri notifikasi sukses ke user
                alert(response.message);
            }
        },
        error: function (xhr) {
            // Menangkap error validasi jika ada
            alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
        }
    });
});

$(document).on('click', '.btn-edit', function () {

    const id = $(this).data('id');

    const supplier = suppliers.find(
        s => s.id == id
    );

    $('#edit_supplier_id').val(supplier.id);
    $('#edit_supplier_name').val(supplier.supplier_name);
    $('#edit_contacts').val(supplier.contacts);
    $('#edit_city').val(supplier.city);

    bootstrap.Modal
        .getOrCreateInstance(
            document.getElementById('editSupplierModal')
        )
        .show();

});

$('#formEditSupplier').on('submit', function (e) {

    e.preventDefault();

    const id = $('#edit_supplier_id').val();

    $.ajax({

        url: `/supplier/${id}`,

        method: 'POST',

        data: {
            _method: 'PUT',
            supplier_name: $('#edit_supplier_name').val(),
            contacts: $('#edit_contacts').val(),
            city: $('#edit_city').val()
        },

        success: function (response) {

            bootstrap.Modal
                .getInstance(
                    document.getElementById('editSupplierModal')
                )
                .hide();

            loadSuppliers();

            alert(response.message);
        }
    });

});

$(document).on('click', '.btn-delete', function () {

    selectedSupplierId = $(this).data('id');

    bootstrap.Modal
        .getOrCreateInstance(
            document.getElementById('deleteSupplierModal')
        )
        .show();

});

$('#confirmDeleteSupplier').on('click', function () {

    $.ajax({

        url: `/supplier/${selectedSupplierId}`,

        method: 'POST',

        data: {
            _method: 'DELETE'
        },

        success: function (response) {

            bootstrap.Modal
                .getInstance(
                    document.getElementById('deleteSupplierModal')
                )
                .hide();

            loadSuppliers();

            alert(response.message);
        }
    });

});

$(document).on('change', '.supplier-status', function () {

    const id = $(this).data('id');

    $.ajax({

        url: `/supplier/${id}/status`,

        method: 'POST',

        data: {
            _method: 'PATCH',
            status: $(this).val()
        }

    });

});