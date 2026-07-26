@extends('public.layout')
@section('title', 'E-Ticket — ' . $order->event->title)
@section('content')
<div class="wrap" style="max-width:620px;padding:36px 24px">
    @if (session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
    <a href="{{ route('my-tickets.index') }}" class="muted" style="font-size:14px">← Tiket Saya</a>
    <h1 style="margin:10px 0 4px">{{ $order->event->title }}</h1>
    <p class="muted" style="margin-bottom:4px">📅 {{ $order->event->starts_at?->format('d M Y, H:i') }} WIB · 📍 {{ $order->event->venue_name }}{{ $order->event->city ? ', ' . $order->event->city->name : '' }}</p>
    <p class="muted" style="margin-bottom:24px">Invoice {{ $order->invoice_no }} · Lunas {{ $order->paid_at?->format('d M Y H:i') }}</p>

    @foreach ($order->tickets as $t)
        <div class="card" style="display:flex;gap:18px;align-items:center;padding:18px;margin-bottom:14px">
            <div style="background:#fff;padding:8px;border-radius:12px;flex-shrink:0;line-height:0">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(118)->margin(0)->generate($t->code) !!}
            </div>
            <div>
                <div style="font-family:'Plus Jakarta Sans';font-weight:800;font-size:18px">{{ $t->ticketType?->name }}</div>
                <div class="muted" style="font-size:13.5px;margin:2px 0">Kode: <b style="color:var(--text)">{{ $t->code }}</b></div>
                <div class="muted" style="font-size:13.5px">Atas nama: {{ $t->holder_name ?: '—' }}</div>
                <span style="display:inline-block;margin-top:8px;padding:3px 11px;border-radius:999px;font-size:11.5px;font-weight:700;color:#fff;background:{{ $t->status === 'used' ? '#9ca3af' : '#22c55e' }}">
                    {{ $t->status === 'used' ? 'SUDAH DIPINDAI' : 'VALID' }}
                </span>
            </div>
        </div>
    @endforeach

    <p class="muted" style="font-size:13px;text-align:center;margin-top:12px">Tunjukkan QR ini saat check-in di lokasi acara.</p>
</div>
@endsection
