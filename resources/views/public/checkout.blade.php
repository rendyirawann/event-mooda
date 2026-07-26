@extends('public.layout')
@section('title', 'Checkout — Event Mooda')
@section('content')
<div class="wrap" style="max-width:760px;padding:36px 24px">
    @if (session('error'))<div class="alert alert-err">{{ session('error') }}</div>@endif
    <h1 style="margin-bottom:4px">Checkout</h1>
    <p class="muted" style="margin-bottom:24px">Invoice {{ $order->invoice_no }}</p>

    <div class="card" style="padding:22px;margin-bottom:22px">
        <div style="font-weight:700;font-family:'Plus Jakarta Sans';margin-bottom:2px">{{ $order->event->title }}</div>
        <div class="muted" style="font-size:14px;margin-bottom:12px">{{ $order->event->starts_at?->format('d M Y, H:i') }} · {{ $order->event->city?->name }}</div>
        @foreach ($order->items as $it)
            <div style="display:flex;justify-content:space-between;padding:9px 0;border-top:1px solid var(--border)">
                <span>{{ $it['name'] }} × {{ $it['qty'] }}</span>
                <span>{{ $it['price'] > 0 ? 'Rp ' . number_format($it['price'] * $it['qty'], 0, ',', '.') : 'GRATIS' }}</span>
            </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;font-family:'Plus Jakarta Sans';font-weight:800;font-size:18px;padding-top:14px;border-top:2px solid var(--border);margin-top:6px">
            <span>Total</span><span>{{ $order->total > 0 ? 'Rp ' . number_format($order->total, 0, ',', '.') : 'GRATIS' }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.pay', $order) }}">
        @csrf
        @if ($order->total > 0)
            <h3 style="margin-bottom:6px">Metode Pembayaran</h3>
            @if (empty($channels))
                <div class="alert alert-err">Metode pembayaran belum tersedia saat ini. Coba beberapa saat lagi.</div>
            @else
                @foreach (collect($channels)->groupBy('group') as $group => $list)
                    <div class="muted" style="font-weight:700;margin:16px 0 8px">{{ $group }}</div>
                    <div style="display:grid;gap:10px">
                        @foreach ($list as $ch)
                            <label class="card" style="display:flex;align-items:center;gap:12px;padding:12px 16px;cursor:pointer">
                                <input type="radio" name="method" value="{{ $ch['code'] }}" required>
                                @if ($ch['icon_url'])<img src="{{ $ch['icon_url'] }}" style="height:24px;width:auto">@endif
                                <span style="font-weight:600">{{ $ch['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
                <button type="submit" class="btn btn-grad" style="width:100%;justify-content:center;margin-top:24px">Bayar Sekarang</button>
            @endif
        @else
            <button type="submit" class="btn btn-grad" style="width:100%;justify-content:center">Klaim Tiket Gratis</button>
        @endif
    </form>
</div>
@endsection
