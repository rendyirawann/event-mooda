@extends('public.layout')
@section('title', $event->title . ' — Event Mooda')
@section('styles')
.ev-hero { position:relative; min-height:320px; display:flex; align-items:flex-end; }
.ev-hero .ov { position:absolute; inset:0; background:linear-gradient(180deg,rgba(0,0,0,.15),rgba(0,0,0,.74)); }
.ev-hero .in { position:relative; z-index:1; padding:34px 0; color:#fff; }
.badge-cat { display:inline-block; background:rgba(255,255,255,.22); backdrop-filter:blur(6px); padding:6px 14px; border-radius:999px; font-weight:700; font-size:12.5px; margin-bottom:12px; }
.ev-h1 { font-size:clamp(26px,4.4vw,46px); font-weight:800; margin-bottom:10px; text-shadow:0 2px 16px rgba(0,0,0,.5); }
.ev-meta { display:flex; gap:20px; flex-wrap:wrap; font-weight:600; font-size:14.5px; }
.ev-grid { display:grid; grid-template-columns:1fr 380px; gap:36px; padding:40px 0; }
.ev-about h3 { font-size:20px; margin-bottom:12px; }
.ev-about p { color:var(--muted); white-space:pre-line; }
.tk-card { position:sticky; top:88px; padding:22px; align-self:start; }
.tk-row { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid var(--border); }
.tk-row:last-of-type { border-bottom:0; }
.tk-name { font-family:'Plus Jakarta Sans'; font-weight:700; }
.tk-price { color:var(--brand); font-weight:700; font-family:'Plus Jakarta Sans'; }
.qty { display:flex; align-items:center; gap:6px; flex-shrink:0; }
.qty button { width:30px; height:30px; border-radius:8px; border:1px solid var(--border); background:var(--card); color:var(--text); cursor:pointer; font-size:16px; }
.qty input { width:38px; text-align:center; border:0; background:transparent; color:var(--text); font-weight:700; font-family:'Plus Jakarta Sans'; }
.tk-total { display:flex; justify-content:space-between; font-family:'Plus Jakarta Sans'; font-weight:800; font-size:18px; margin:18px 0; }
.soldout { color:var(--muted); font-size:13px; font-weight:600; }
@media(max-width:840px){ .ev-grid{ grid-template-columns:1fr; } .tk-card{ position:static; } }
@endsection
@section('content')
@php $heroBg = $event->posterUrl() ? "url('".$event->posterUrl()."') center/cover no-repeat" : $event->gradient(); @endphp
<header class="ev-hero" style="background:{{ $heroBg }}">
    <div class="ov"></div>
    <div class="wrap in">
        <span class="badge-cat">{{ $event->category?->icon }} {{ $event->category?->name }}</span>
        <h1 class="ev-h1">{{ $event->title }}</h1>
        <div class="ev-meta">
            <span>📅 {{ $event->starts_at?->format('d M Y, H:i') }} WIB</span>
            <span>📍 {{ $event->venue_name }}{{ $event->city ? ', ' . $event->city->name : '' }}</span>
        </div>
    </div>
</header>

<div class="wrap">
    @if (session('error'))<div class="alert alert-err" style="margin-top:20px">{{ session('error') }}</div>@endif
    <div class="ev-grid">
        <div class="ev-about">
            @if ($event->tagline)<h3>{{ $event->tagline }}</h3>@endif
            <p>{{ $event->description }}</p>
        </div>

        <div class="card tk-card">
            <h3 style="margin-bottom:6px">Pilih Tiket</h3>
            <form method="POST" action="{{ route('checkout.order', $event) }}" id="buyForm">
                @csrf
                @forelse ($event->ticketTypes as $tt)
                    <div class="tk-row">
                        <div>
                            <div class="tk-name">{{ $tt->name }}</div>
                            <div class="tk-price">{{ $tt->price > 0 ? 'Rp ' . number_format($tt->price, 0, ',', '.') : 'GRATIS' }}</div>
                            @if ($tt->description)<div class="muted" style="font-size:12.5px">{{ $tt->description }}</div>@endif
                        </div>
                        @if ($tt->remaining() > 0)
                            <div class="qty" data-price="{{ $tt->price }}">
                                <button type="button" data-d="-1">−</button>
                                <input type="number" name="qty[{{ $tt->id }}]" value="0" min="0" max="{{ min($tt->max_per_order, $tt->remaining()) }}" readonly>
                                <button type="button" data-d="1">+</button>
                            </div>
                        @else
                            <span class="soldout">Habis</span>
                        @endif
                    </div>
                @empty
                    <p class="muted">Belum ada tiket tersedia.</p>
                @endforelse
                <div class="tk-total"><span>Total</span><span id="grandTotal">Rp 0</span></div>
                @auth
                    <button type="submit" class="btn btn-grad" style="width:100%;justify-content:center" id="buyBtn" disabled>Lanjut Bayar</button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-grad" style="width:100%;justify-content:center">Masuk untuk Beli Tiket</a>
                @endauth
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var rows = document.querySelectorAll('.qty');
    function recalc() {
        var total = 0, count = 0;
        rows.forEach(function (r) {
            var q = parseInt(r.querySelector('input').value) || 0;
            count += q; total += q * parseInt(r.dataset.price);
        });
        document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        var b = document.getElementById('buyBtn'); if (b) b.disabled = count <= 0;
    }
    rows.forEach(function (r) {
        r.querySelectorAll('button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var inp = r.querySelector('input');
                var v = (parseInt(inp.value) || 0) + parseInt(btn.dataset.d);
                inp.value = Math.max(0, Math.min(parseInt(inp.max), v));
                recalc();
            });
        });
    });
    recalc();
</script>
@endpush
