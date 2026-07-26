{{-- Preloader tombol global: saat form dikirim, tombol submit di-disable + spinner → anti klik ganda.
     Opt-out: tambahkan atribut data-noloader pada <form> (mis. form AJAX yang punya loader sendiri). --}}
<style>
    .btn-spin{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.45);border-top-color:#fff;border-radius:50%;animation:btnspin .6s linear infinite;vertical-align:-2px;margin-right:7px}
    .btn-light .btn-spin,.btn-ghost .btn-spin,.btn-ink .btn-spin,.btn-secondary .btn-spin,.btn-light-primary .btn-spin,.btn-light-danger .btn-spin,.btn-light-success .btn-spin,.btn-light-warning .btn-spin,.btn-outline .btn-spin{border-color:rgba(0,0,0,.2);border-top-color:currentColor}
    @keyframes btnspin{to{transform:rotate(360deg)}}
</style>
<script>
    (function () {
        document.addEventListener('submit', function (e) {
            var f = e.target;
            if (!(f instanceof HTMLFormElement) || f.hasAttribute('data-noloader')) return;
            if (typeof f.checkValidity === 'function' && !f.checkValidity()) return; // biarkan validasi HTML5 dulu
            var btn = f.querySelector('button[type=submit], input[type=submit], button:not([type])');
            if (!btn || btn.dataset.loading) { if (btn && btn.dataset.loading) e.preventDefault(); return; }
            btn.dataset.loading = '1';
            btn.dataset.html = btn.innerHTML;
            btn.style.minWidth = btn.offsetWidth + 'px';
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.innerHTML = '<span class="btn-spin"></span>' + (btn.getAttribute('data-loading-text') || 'Memproses…');
            // Safety: pulihkan tombol bila halaman tidak berpindah (mis. error validasi server tanpa reload).
            setTimeout(function () {
                if (btn.dataset.loading) { btn.disabled = false; btn.classList.remove('disabled'); btn.innerHTML = btn.dataset.html; btn.style.minWidth = ''; delete btn.dataset.loading; }
            }, 20000);
        }, true);
    })();
</script>
