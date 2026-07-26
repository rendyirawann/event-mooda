@extends('backend.layout.app')
@section('title', 'Check-in — ' . $event->title)
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm btn-light mb-4">← Kembali ke Event</a>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
            <div>
                <h2 class="fw-bold mb-1">Check-in: {{ $event->title }}</h2>
                <span class="text-muted">Pindai QR e-ticket atau masukkan kodenya untuk verifikasi masuk.</span>
            </div>
            <div class="card" style="padding:14px 22px;text-align:center">
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:26px">
                    <span id="checkedCount">{{ $checked }}</span><span class="text-muted">/{{ $total }}</span>
                </div>
                <div class="text-muted fs-8">tiket ter-check-in</div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card card-flush">
                    <div class="card-body">
                        <div id="reader" style="width:100%;border-radius:12px;overflow:hidden"></div>
                        <div id="camNote" class="text-muted fs-8 mb-3"></div>
                        <label class="form-label fw-semibold">Kode tiket (manual / scanner USB):</label>
                        <form id="manualForm" class="d-flex gap-2">
                            <input id="codeInput" class="form-control form-control-solid" placeholder="TKT-XXXXXXXXXX" autocomplete="off" autofocus>
                            <button type="submit" class="btn btn-primary">Cek</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="result" class="card card-flush">
                    <div class="card-body text-center text-muted py-10">
                        Hasil verifikasi tiket akan tampil di sini.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    var CHECK_URL = "{{ route('organizer.checkin.check', $event) }}";
    var CSRF = "{{ csrf_token() }}";
    var busy = false;

    function showResult(d) {
        var color = d.status === 'valid' ? '#22c55e' : (d.status === 'used' ? '#f59e0b' : '#ef4444');
        var icon = d.status === 'valid' ? '✓' : (d.status === 'used' ? '⚠' : '✕');
        var t = d.ticket ? '<div style="margin-top:10px;font-size:15px"><b>' + (d.ticket.holder || '-') + '</b><br>' + (d.ticket.type || '') + '<br><small style="opacity:.85">' + (d.ticket.code || '') + '</small></div>' : '';
        document.getElementById('result').innerHTML =
            '<div class="card-body text-center py-8" style="background:' + color + ';color:#fff;border-radius:12px">' +
            '<div style="font-size:52px;line-height:1">' + icon + '</div>' +
            '<div style="font-weight:800;font-size:19px;margin-top:6px">' + d.message + '</div>' + t + '</div>';
        if (typeof d.checked !== 'undefined') {
            document.getElementById('checkedCount').textContent = d.checked;
        }
    }

    function submitCode(code) {
        if (busy || !code) return;
        busy = true;
        fetch(CHECK_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'code=' + encodeURIComponent(code)
        }).then(function (r) { return r.json(); })
          .then(function (d) { showResult(d); })
          .catch(function () { showResult({ status: 'invalid', message: 'Gagal terhubung ke server.' }); })
          .finally(function () { setTimeout(function () { busy = false; }, 1300); });
    }

    document.getElementById('manualForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var i = document.getElementById('codeInput');
        submitCode(i.value.trim());
        i.select();
    });

    // Kamera: butuh secure context (HTTPS). Jika tidak, andalkan input manual.
    if (window.isSecureContext && window.Html5Qrcode) {
        var h = new Html5Qrcode('reader');
        h.start({ facingMode: 'environment' }, { fps: 10, qrbox: 230 }, function (txt) { submitCode(txt); })
            .catch(function () { document.getElementById('camNote').textContent = 'Kamera tidak bisa diakses — gunakan input manual.'; });
    } else {
        document.getElementById('reader').style.display = 'none';
        document.getElementById('camNote').textContent = 'ℹ️ Kamera aktif setelah situs pakai HTTPS. Sementara pakai input kode manual atau scanner barcode USB.';
    }
</script>
@endpush
@endsection
