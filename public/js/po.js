document.addEventListener('DOMContentLoaded', function () {
    // === LOGIKA DINAMIS TAMBAH/HAPUS BARIS PRODUK (TAMBAHAN BARU) ===
    const productTableBody = document.querySelector('#productTable tbody');
    const addProductBtn = document.getElementById('addProduct');
    const rowTemplate = document.getElementById('productRowTemplate');
    let rowIndex = 1; // Mulai dari 1 karena indeks 0 sudah dipakai baris default awal

    if (addProductBtn && productTableBody && rowTemplate) {
        addProductBtn.addEventListener('click', function () {
            // Ambil struktur HTML dari dalam <template>
            let templateContent = rowTemplate.innerHTML;
            
            // Ganti __index__ di dalam template menjadi angka indeks yang unik dan dinamis
            let newRowHtml = templateContent.replace(/__index__/g, rowIndex);
            
            // Masukkan baris baru ke dalam tabel body
            productTableBody.insertAdjacentHTML('beforeend', newRowHtml);
            
            // Naikkan index untuk baris berikutnya
            rowIndex++;
        });
    }

    // Event delegation untuk menangani tombol hapus (remove-row) yang baru dibuat
    if (productTableBody) {
        productTableBody.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                // Pastikan baris tidak dihapus jika tombol dalam keadaan disabled (baris pertama)
                if (row && !e.target.hasAttribute('disabled')) {
                    row.remove();
                }
            }
        });
    }
    // ==============================================================


    // 1. Logika AJAX ubah status di dalam tabel (Tanpa Reload)
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const poId = this.getAttribute('data-id');
            const newStatus = this.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/purchaseorder/purchase-orders/${poId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.remove('bg-success-subtle', 'text-success', 'bg-warning-subtle', 'text-warning', 'bg-danger-subtle', 'text-danger');

                    if (newStatus === 'selesai') {
                        this.classList.add('bg-success-subtle', 'text-success');
                    } else if (newStatus === 'pending') {
                        this.classList.add('bg-warning-subtle', 'text-warning');
                    } else if (newStatus === 'dibatalkan') {
                        this.classList.add('bg-danger-subtle', 'text-danger');
                    }
                    
                    alert('Status berhasil diperbarui!');
                } else {
                    alert('Gagal memperbarui status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem.');
            });
        });
    });

    // 2. Auto-submit untuk filter dropdown atas (Status & Supplier)
    ['status', 'supplier_id'].forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) {
            el.addEventListener('change', () => {
                el.closest('form')?.submit();
            });
        }
    });

    $('#formPurchaseOrder').on('submit', function (e) {
        e.preventDefault(); // <-- Baris paling penting agar browser tidak pindah halaman
        
        let $submitBtn = $('#btnSubmitPo');
        $submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: '/purchaseorder/store',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '/purchaseorder'; // Alihkan halaman secara halus lewat JS jika sukses
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
            },
            complete: function () {
                $submitBtn.prop('disabled', false).text('Simpan PO');
            }
        });
    });

    // 3. Auto-submit untuk input pencarian teks (Debounce 0.5 detik)
    const searchInput = document.querySelector('[name="search"]');
    if (searchInput) {
        let typingTimer;

        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                searchInput.closest('form')?.submit();
            }, 500);
        });

        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                clearTimeout(typingTimer);
                searchInput.closest('form')?.submit();
            }
        });

        if (searchInput.value !== '') {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    }
});