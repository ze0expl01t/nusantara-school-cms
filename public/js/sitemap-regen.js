/**
 * Sitemap Regenerate — Global Function
 * Dipakai oleh tombol di /admin/settings/list/sitemap
 */

window.regenSitemap = function (btn, url) {
    if (!confirm('Regenerate sitemap sekarang?')) return;

    var label = btn.querySelector('span');
    var originalText = label ? label.textContent : 'Regenerate Sekarang';

    // Loading state
    btn.disabled = true;
    btn.style.opacity = '0.6';
    btn.style.cursor = 'wait';
    if (label) label.textContent = 'Processing...';

    // Ambil CSRF token dari form Twill
    var tokenInput = document.querySelector('input[name="_token"]');
    var token = tokenInput ? tokenInput.value : null;

    if (!token) {
        alert('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
        resetBtn();
        return;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
        .then(function (r) {
            return r.json().then(function (d) {
                return { ok: r.ok, status: r.status, data: d };
            }).catch(function () {
                return { ok: r.ok, status: r.status, data: {} };
            });
        })
        .then(function (res) {
            if (res.ok && res.data.success) {
                alert('✓ ' + (res.data.message || 'Sitemap berhasil di-regenerate!'));
                location.reload();
            } else {
                alert('✗ Gagal: ' + (res.data.message || ('HTTP ' + res.status)));
                resetBtn();
            }
        })
        .catch(function (err) {
            alert('✗ Error: ' + err.message);
            resetBtn();
        });

    function resetBtn() {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        if (label) label.textContent = originalText;
    }
};
