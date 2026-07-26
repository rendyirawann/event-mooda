@extends('public.layout')
@section('title', 'Pembayaran — Event Mooda')
@section('styles')
.spinner { width:16px; height:16px; border:2px solid var(--border); border-top-color:var(--brand); border-radius:50%; animation:spin 1s linear infinite; display:inline-block; }
@keyframes spin { to { transform:rotate(360deg); } }
@endsection
@section('content')
<div class="wrap" style="max-width:560px;padding:36px 24px;text-align:center">
    <h1 style="margin-bottom:4px">Selesaikan Pembayaran</h1>
    <p class="muted" style="margin-bottom:20px">Invoice {{ $order->invoice_no }}</p>
    <div class="card" style="padding:28px">
        <div class="muted" style="font-size:13px">Total Bayar</div>
        <div style="font-family:'Plus Jakarta Sans';font-weight:800;font-size:30px;margin-bottom:4px">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
        <div class="muted" style="margin-bottom:22px">{{ $pay['payment_name'] ?? $order->payment_method }}</div>

        @if (! empty($pay['qr_url']))
            <img src="{{ $pay['qr_url'] }}" alt="QRIS" style="width:230px;margin:0 auto 12px;border-radius:12px;background:#fff;padding:10px">
            <p class="muted" style="font-size:13.5px">Scan QRIS dengan aplikasi e-wallet / m-banking.</p>
        @elseif (! empty($pay['pay_code']))
            <div class="muted" style="font-size:13px">Nomor Virtual Account</div>
            <div style="font-family:'Plus Jakarta Sans';font-weight:800;font-size:24px;letter-spacing:1px;margin:6px 0">{{ $pay['pay_code'] }}</div>
            <button class="btn btn-ghost btn-sm" onclick="navigator.clipboard.writeText('{{ $pay['pay_code'] }}');this.textContent='Tersalin ✓'">Salin Nomor</button>
        @endif

        <div id="status" style="margin-top:24px;display:flex;align-items:center;justify-content:center;gap:10px;color:var(--muted)">
            <span class="spinner"></span> Menunggu pembayaran...
        </div>
        @if (! empty($pay['checkout_url']))
            <a href="{{ $pay['checkout_url'] }}" target="_blank" rel="noopener" class="muted" style="font-size:13px;display:inline-block;margin-top:12px">Buka instruksi lengkap di Tripay ↗</a>
        @endif
    </div>
    <p class="muted" style="font-size:13px;margin-top:16px">Halaman ini otomatis lanjut begitu pembayaran terkonfirmasi.</p>
</div>
@endsection
@push('scripts')
<script>
    var url = "{{ route('checkout.status', $order) }}";
    function poll() {
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var s = document.getElementById('status');
                if (d.status === 'paid') { s.innerHTML = '✅ Pembayaran berhasil! Mengalihkan...'; window.location = d.redirect; }
                else if (d.status === 'failed') { s.innerHTML = '❌ Pembayaran gagal / kedaluwarsa.'; }
                else { setTimeout(poll, 5000); }
            })
            .catch(function () { setTimeout(poll, 8000); });
    }
    setTimeout(poll, 4000);
</script>
@endpush
