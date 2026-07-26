@extends('backend.layout.app')
@section('title', 'Dashboard')
@push('stylesheets')
<style>
    .npd { --red:#e11d2a; --ink:var(--bs-gray-900); --line:var(--bs-gray-300); }
    [data-bs-theme="dark"] .npd { --line:#2a2a2a; }
    .npd { font-family:'Playfair Display', Georgia, serif; }
    .np-masthead { border-top:3px solid var(--ink); border-bottom:1px solid var(--ink); padding:14px 0 10px; margin-bottom:6px; }
    .np-masthead .kicker { font-family:'Inter',sans-serif; letter-spacing:.35em; text-transform:uppercase; font-size:11px; color:var(--red); font-weight:700; }
    .np-masthead h1 { font-weight:900; font-size:clamp(30px,5vw,52px); letter-spacing:-.01em; margin:2px 0; }
    .np-masthead .sub { font-family:'Inter',sans-serif; font-size:13px; color:var(--bs-gray-600); border-top:1px solid var(--line); padding-top:8px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px; }
    .np-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:0; border:1px solid var(--ink); margin:18px 0; }
    .np-stat { padding:18px 20px; border-right:1px solid var(--line); border-bottom:1px solid var(--line); }
    .np-stat:nth-child(4n){ border-right:0; }
    .np-stat .lbl { font-family:'Inter',sans-serif; text-transform:uppercase; letter-spacing:.12em; font-size:10.5px; font-weight:700; color:var(--bs-gray-600); }
    .np-stat .val { font-weight:900; font-size:30px; line-height:1.1; margin-top:6px; }
    .np-stat .val.red { color:var(--red); }
    .np-stat small { font-family:'Inter',sans-serif; color:var(--bs-gray-500); font-size:11px; }
    .np-cols { display:grid; grid-template-columns:1.5fr 1fr; gap:26px; }
    .np-sec h3 { font-weight:900; font-size:20px; border-bottom:2px solid var(--ink); padding-bottom:6px; margin-bottom:2px; display:flex; align-items:center; gap:8px; }
    .np-sec h3::before { content:''; width:10px; height:10px; background:var(--red); display:inline-block; }
    .np-row { font-family:'Inter',sans-serif; display:flex; justify-content:space-between; gap:12px; padding:11px 0; border-bottom:1px solid var(--line); }
    .np-row .t { font-weight:600; font-size:14px; }
    .np-row .m { font-size:12px; color:var(--bs-gray-500); }
    .np-row .amt { font-family:'Playfair Display',serif; font-weight:900; white-space:nowrap; }
    .np-rank { font-family:'Inter',sans-serif; display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--line); }
    .np-rank .n { font-family:'Playfair Display',serif; font-weight:900; font-size:22px; color:var(--red); width:26px; }
    .np-empty { font-family:'Inter',sans-serif; color:var(--bs-gray-500); padding:20px 0; font-size:13px; }
    @media(max-width:900px){ .np-grid{grid-template-columns:repeat(2,1fr)} .np-stat:nth-child(4n){border-right:1px solid var(--line)} .np-stat:nth-child(2n){border-right:0} .np-cols{grid-template-columns:1fr} }
</style>
@endpush
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid mt-4">
    <div id="kt_app_content_container" class="app-container container-xxl npd">

        <header class="np-masthead">
            <div class="kicker">{{ $super ? 'Edisi Platform' : 'Edisi Penyelenggara' }} — {{ now()->translatedFormat('l, d F Y') }}</div>
            <h1>Event<span style="color:var(--red)">Mooda</span> Daily</h1>
            <div class="sub">
                <span>Halo, <b>{{ $user->name }}</b> — ringkasan {{ $super ? 'seluruh platform' : 'event Anda' }} hari ini.</span>
                <span>Tiket terjual: <b>{{ number_format($ticketsSold) }}</b></span>
            </div>
        </header>

        <div class="np-grid">
            <div class="np-stat"><div class="lbl">Total Event</div><div class="val">{{ number_format($totalEvents) }}</div><small>{{ $publishedEvents }} published</small></div>
            <div class="np-stat"><div class="lbl">Tiket Terjual</div><div class="val red">{{ number_format($ticketsSold) }}</div><small>{{ number_format($checkedIn) }} sudah check-in</small></div>
            <div class="np-stat"><div class="lbl">Pendapatan (lunas)</div><div class="val">Rp {{ number_format($revenue, 0, ',', '.') }}</div><small>{{ $paidOrders }} pesanan lunas</small></div>
            <div class="np-stat"><div class="lbl">Total Pesanan</div><div class="val">{{ number_format($totalOrders) }}</div><small>termasuk pending</small></div>
        </div>

        <div class="np-cols">
            <div class="np-sec">
                <h3>Pesanan Terbaru</h3>
                @forelse ($recentOrders as $o)
                    <div class="np-row">
                        <div>
                            <div class="t">{{ $o->event?->title ?? '—' }}</div>
                            <div class="m">{{ $o->invoice_no }} · {{ $o->buyer_name }} · {{ $o->paid_at?->format('d M Y H:i') }}</div>
                        </div>
                        <div class="amt">{{ $o->total > 0 ? 'Rp ' . number_format($o->total, 0, ',', '.') : 'GRATIS' }}</div>
                    </div>
                @empty
                    <div class="np-empty">Belum ada pesanan lunas.</div>
                @endforelse
            </div>
            <div class="np-sec">
                <h3>Event Teratas</h3>
                @forelse ($topEvents as $i => $e)
                    <div class="np-rank">
                        <span class="n">{{ $i + 1 }}</span>
                        <div style="flex:1">
                            <div class="t" style="font-weight:700">{{ $e->title }}</div>
                            <div class="m">{{ $e->tickets_count }} tiket · {{ ucfirst($e->status) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="np-empty">Belum ada event.</div>
                @endforelse
                <a href="{{ route('organizer.events.index') }}" class="btn btn-sm btn-danger mt-4" style="font-family:'Inter',sans-serif;font-weight:700">Kelola Event →</a>
            </div>
        </div>

    </div>
</div>
@endsection
