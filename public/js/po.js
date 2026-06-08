document.addEventListener('DOMContentLoaded', function () {

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
                e.preventDefault(); // Mencegah double submit bawaan browser
                clearTimeout(typingTimer);
                searchInput.closest('form')?.submit();
            }
        });

        // Tweak UX: Kembalikan fokus kursor ke input search setelah reload halaman
        if (searchInput.value !== '') {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    }
});