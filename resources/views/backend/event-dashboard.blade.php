@extends('backend.layout.app')
@section('title', 'Dashboard')
@push('stylesheets')
<style>
    .dsh{ --primary:#D90429; --section:#FFF8F6; }
    .dsh, .dsh h1,.dsh h2,.dsh h3,.dsh h4{ font-family:'Plus Jakarta Sans','Inter',sans-serif; }
    .dsh .hero{ background:linear-gradient(120deg,#D90429,#8f0318); border-radius:24px; padding:28px 32px; color:#fff; display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap; position:relative; overflow:hidden; box-shadow:0 22px 50px -20px rgba(217,4,41,.5); }
    .dsh .hero::before{ content:''; position:absolute; width:280px;height:280px; right:-70px; top:-120px; border-radius:50%; background:rgba(255,255,255,.12); }
    .dsh .hero h2{ font-weight:800; font-size:26px; position:relative; }
    .dsh .hero p{ opacity:.9; position:relative; margin-top:4px; }
    .dsh .stat-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin:22px 0; }
    .dsh .stat{ background:#fff; border:1px solid #eee; border-radius:20px; padding:22px; transition:.25s; }
    .dsh .stat:hover{ transform:translateY(-6px); box-shadow:0 18px 40px -18px rgba(17,17,17,.16); }
    .dsh .stat .ic{ width:46px;height:46px;border-radius:14px;background:var(--section);display:grid;place-items:center;font-size:22px;margin-bottom:14px; }
    .dsh .stat .lbl{ font-size:12.5px;color:#888;font-weight:600 }
    .dsh .stat .val{ font-weight:800;font-size:28px;margin-top:4px }
    .dsh .stat .val.red{ color:var(--primary) }
    .dsh .stat small{ color:#999;font-size:12px }
    .dsh .cols{ display:grid; grid-template-columns:1.5fr 1fr; gap:20px; }
    .dsh .panel{ background:#fff;border:1px solid #eee;border-radius:22px;padding:24px; }
    .dsh .panel h3{ font-weight:800;font-size:18px;margin-bottom:16px;display:flex;align-items:center;gap:8px }
    .dsh .panel h3::before{ content:'';width:9px;height:9px;border-radius:50%;background:var(--primary) }
    .dsh .row{ display:flex;justify-content:space-between;gap:12px;padding:13px 0;border-bottom:1px solid #f2f2f2 }
    .dsh .row:last-child{border-bottom:0}
    .dsh .row .t{ font-weight:700;font-size:14.5px }
    .dsh .row .m{ font-size:12px;color:#999 }
    .dsh .row .amt{ font-weight:800;white-space:nowrap }
    .dsh .rank{ display:flex;align-items:center;gap:14px;padding:11px 0;border-bottom:1px solid #f2f2f2 }
    .dsh .rank:last-of-type{border-bottom:0}
    .dsh .rank .n{ width:34px;height:34px;border-radius:11px;background:var(--section);color:var(--primary);display:grid;place-items:center;font-weight:800 }
    .dsh .empty{ color:#aaa;padding:24px 0;text-align:center;font-size:13.5px }
    .dsh .btn-red{ display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;font-weight:700;font-size:13.5px;padding:11px 20px;border-radius:999px;margin-top:16px }
    @media(max-width:900px){ .dsh .stat-grid{grid-template-columns:repeat(2,1fr)} .dsh .cols{grid-template-columns:1fr} }
</style>
@endpush
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid mt-4">
    <div id="kt_app_content_container" class="app-container container-xxl dsh">

        <div class="hero">
            <div>
                <h2>Halo, {{ $user->name }} 👋</h2>
                <p>Ringkasan {{ $super ? 'seluruh platform EventMooda' : 'event Anda' }} · {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('organizer.events.create') }}" class="btn-red">+ Buat Event Baru</a>
        </div>

        <div class="stat-grid">
            <div class="stat"><div class="ic">🎫</div><div class="lbl">Total Event</div><div class="val">{{ number_format($totalEvents) }}</div><small>{{ $publishedEvents }} published</small></div>
            <div class="stat"><div class="ic">🎟️</div><div class="lbl">Tiket Terjual</div><div class="val red">{{ number_format($ticketsSold) }}</div><small>{{ number_format($checkedIn) }} sudah check-in</small></div>
            <div class="stat"><div class="ic">💰</div><div class="lbl">Pendapatan (lunas)</div><div class="val">Rp {{ number_format($revenue, 0, ',', '.') }}</div><small>{{ $paidOrders }} pesanan lunas</small></div>
            <div class="stat"><div class="ic">🧾</div><div class="lbl">Total Pesanan</div><div class="val">{{ number_format($totalOrders) }}</div><small>termasuk pending</small></div>
        </div>

        <div class="cols">
            <div class="panel">
                <h3>Pesanan Terbaru</h3>
                @forelse ($recentOrders as $o)
                    <div class="row">
                        <div><div class="t">{{ $o->event?->title ?? '—' }}</div><div class="m">{{ $o->invoice_no }} · {{ $o->buyer_name }} · {{ $o->paid_at?->format('d M Y H:i') }}</div></div>
                        <div class="amt">{{ $o->total > 0 ? 'Rp ' . number_format($o->total, 0, ',', '.') : 'Gratis' }}</div>
                    </div>
                @empty
                    <div class="empty">Belum ada pesanan lunas.</div>
                @endforelse
            </div>
            <div class="panel">
                <h3>Event Teratas</h3>
                @forelse ($topEvents as $i => $e)
                    <div class="rank"><span class="n">{{ $i + 1 }}</span><div style="flex:1"><div class="t">{{ $e->title }}</div><div class="m">{{ $e->tickets_count }} tiket · {{ ucfirst($e->status) }}</div></div></div>
                @empty
                    <div class="empty">Belum ada event.</div>
                @endforelse
                <a href="{{ route('organizer.events.index') }}" class="btn-red">Kelola Event →</a>
            </div>
        </div>

    </div>
</div>
@endsection
