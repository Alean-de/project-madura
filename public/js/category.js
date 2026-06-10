$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --- CLIENT-SIDE REALTIME SEARCH ---
    $('#searchCategory').on('keyup', function () {
        let value = $(this).val().toLowerCase();
        $("#categoryTableBody tr").filter(function () {
            if(this.id !== 'emptyRow') {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            }
        });
    });

    // --- 1. AJAX CREATE ---
    $('#formAddCategory').on('submit', function (e) {
        e.preventDefault();
        let name = $(this).find('input[name="category_name"]').val();
        
        $.ajax({
            url: "/category/create",
            method: "POST",
            data: { category_name: name },
            success: function (res) {
                $('#kategoriModal').modal('hide');
                $('#formAddCategory')[0].reset();
                location.reload(); 
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Gagal menambahkan kategori.');
            }
        });
    });

    // --- 2. OPEN EDIT MODAL ---
    $(document).on('click', '.btn-edit-category', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let status = $(this).data('status');

        $('#editCategoryId').val(id);
        $('#editCategoryName').val(name);
        $('#editCategoryStatus').val(status);
        $('#editKategoriModal').modal('show');
    });

    // --- 3. AJAX UPDATE VIA SUBMIT ---
    $('#formEditCategory').on('submit', function (e) {
        e.preventDefault();
        let id = $('#editCategoryId').val(); // <--- Menggunakan 'id'
        let name = $('#editCategoryName').val();
        let status = $('#editCategoryStatus').val();

        $.ajax({
            url: `/category/${id}`,
            method: "PUT",
            data: { category_name: name, status: status },
            success: function (res) {
                $('#editKategoriModal').modal('hide');
                let row = $(`#row-${id}`);
                row.find('.row-category-name span').text(name);
                
                let statusBtn = row.find('.btn-toggle-status');
                statusBtn.data('status', status);
                if(parseInt(status) === 1) {
                    statusBtn.removeClass('bg-danger-subtle text-danger').addClass('bg-success-subtle text-success').find('span').text('Aktif');
                } else {
                    statusBtn.removeClass('bg-success-subtle text-success').addClass('bg-danger-subtle text-danger').find('span').text('Nonaktif');
                }
                
                if (typeof window.updateCategoryChart === 'function') window.updateCategoryChart();
            },
            error: function () {
                alert('Gagal memperbarui data.');
            }
        });
    });

    // --- 4. AJAX TOGGLE STATUS (INSTANT CLICK) ---
    $(document).on('click', '.btn-toggle-status', function () {
        let btn = $(this);
        let id = btn.data('id'); // <--- Menggunakan 'id'
        let currentStatus = btn.data('status');
        let newStatus = currentStatus ? 0 : 1;

        $.ajax({
            url: `/category/${id}/status`, // <--- Diperbaiki dari ${category_id} menjadi ${id}
            method: "PATCH",
            data: { status: newStatus },
            success: function () {
                btn.data('status', newStatus);
                if (newStatus === 1) {
                    btn.removeClass('bg-danger-subtle text-danger').addClass('bg-success-subtle text-success').find('span').text('Aktif');
                } else {
                    btn.removeClass('bg-success-subtle text-success').addClass('bg-danger-subtle text-danger').find('span').text('Nonaktif');
                }
            },
            error: function() {
                alert('Gagal mengubah status.');
            }
        });
    });
});