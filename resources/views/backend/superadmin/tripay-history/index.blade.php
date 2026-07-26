@extends('backend.layout.app')
@section('title', 'Riwayat Pembayaran Tiket')
@push('stylesheets')
<style>
    .thx{ --primary:#D90429; --section:#FFF8F6; }
    .thx, .thx h2,.thx h3{ font-family:'Plus Jakarta Sans','Inter',sans-serif; }
    .thx .stat-grid{ display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px }
    .thx .stat{ background:#fff;border:1px solid #eee;border-radius:18px;padding:18px 20px }
    .thx .stat .lbl{ font-size:12px;color:#888;font-weight:600 }
    .thx .stat .val{ font-weight:800;font-size:26px;margin-top:3px }
    .thx .stat .val.red{ color:var(--primary) }
    .thx .filters{ background:#fff;border:1px solid #eee;border-radius:16px;padding:14px 18px;margin-bottom:16px;display:flex;gap:12px;align-items:center;flex-wrap:wrap }
    .thx .tbl-card{ background:#fff;border:1px solid #eee;border-radius:20px;overflow:hidden }
    .thx table{ width:100%;border-collapse:collapse }
    .thx th{ text-align:left;font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;color:#999;font-weight:700;padding:14px 20px;border-bottom:1px solid #f0f0f0;background:var(--section) }
    .thx td{ padding:14px 20px;border-bottom:1px solid #f4f4f4;font-size:13.5px }
    .thx tr:last-child td{ border-bottom:0 }
    .thx .inv{ font-weight:700 }
    .thx .sub{ font-size:11.5px;color:#aaa }
    .thx .badge{ font-size:11px;font-weight:700;padding:4px 11px;border-radius:999px }
    @media(max-width:900px){ .thx .stat-grid{grid-template-columns:repeat(2,1fr)} .thx .tbl-card{overflow-x:auto} }
</style>
@endpush
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid mt-4">
    <div id="kt_app_content_container" class="app-container container-xxl thx">

        <h2 class="fw-bold mb-1">Riwayat Pembayaran Tiket Event</h2>
        <p class="text-muted mb-4">Khusus transaksi tiket event (invoice <code>TIX-</code>) via Tripay. Riwayat Tripay keseluruhan (POS + event) ada di aplikasi monitor terpisah.</p>

        <div class="stat-grid">
            <div class="stat"><div class="lbl">Total Transaksi</div><div class="val">{{ number_format($counts['total']) }}</div></div>
            <div class="stat"><div class="lbl">Lunas</div><div class="val red">{{ number_format($counts['paid']) }}</div></div>
            <div class="stat"><div class="lbl">Pending</div><div class="val">{{ number_format($counts['pending']) }}</div></div>
            <div class="stat"><div class="lbl">Pendapatan (lunas)</div><div class="val">Rp {{ number_format($counts['revenue'], 0, ',', '.') }}</div></div>
        </div>

        <form method="GET" class="filters">
            <span class="fw-semibold text-muted fs-8">Status:</span>
            <select name="status" class="form-select form-select-sm w-150px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach (['paid' => 'Lunas', 'pending' => 'Pending', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Batal'] as $k => $v)
                    <option value="{{ $k }}" @selected($status === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </form>

        <div class="tbl-card">
            <table>
                <thead><tr><th>Waktu</th><th>Invoice</th><th>Event</th><th>Pembeli</th><th>Metode</th><th class="text-end">Jumlah</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($orders as $o)
                        @php $badge = ['paid'=>'success','pending'=>'warning','expired'=>'secondary','cancelled'=>'danger'][$o->status] ?? 'secondary'; @endphp
                        <tr>
                            <td class="text-muted fs-8" style="white-space:nowrap">{{ $o->created_at?->format('d M Y H:i') }}</td>
                            <td><div class="inv">{{ $o->invoice_no }}</div><div class="sub">{{ $o->payment_ref }}</div></td>
                            <td class="fs-7">{{ $o->event?->title ?? '—' }}</td>
                            <td class="fs-7">{{ $o->buyer_name }}</td>
                            <td class="fs-7">{{ data_get($o->payment_payload, 'payment_name') ?? $o->payment_method ?? '—' }}</td>
                            <td class="text-end fw-bold" style="white-space:nowrap">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-light-{{ $badge }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-10">Belum ada transaksi tiket event.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}</div>

    </div>
</div>
@endsection
