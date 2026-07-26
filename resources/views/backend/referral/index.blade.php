@extends('backend.layout.app')
@section('title', 'Program Referral')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
            <div>
                <h2 class="fw-bold mb-1">Program Referral</h2>
                <span class="text-muted">Bagikan link, dapat komisi dari setiap tiket terjual lewat linkmu.</span>
            </div>
            <span class="badge badge-light-{{ $role === 'reseller' ? 'warning' : 'primary' }} fs-7 text-capitalize">{{ $role }} · komisi {{ $rate }}%</span>
        </div>

        {{-- LINK REFERRAL --}}
        <div class="card card-flush mb-5">
            <div class="card-body">
                <label class="form-label fw-semibold">Link Referral Kamu</label>
                <div class="d-flex gap-2 flex-wrap">
                    <input id="refLink" class="form-control form-control-solid" value="{{ $link }}" readonly style="max-width:520px">
                    <button class="btn btn-primary" onclick="navigator.clipboard.writeText(document.getElementById('refLink').value);this.textContent='Tersalin ✓'">Salin Link</button>
                </div>
                <div class="text-muted fs-8 mt-2">Kode: <b>{{ $user->referral_code }}</b> — komisi <b>{{ $rate }}%</b> dari nilai pesanan lunas.</div>
            </div>
        </div>

        {{-- STAT --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4"><div class="card card-flush"><div class="card-body text-center">
                <div class="text-muted fs-8">Komisi Pending</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:26px">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            </div></div></div>
            <div class="col-md-4"><div class="card card-flush"><div class="card-body text-center">
                <div class="text-muted fs-8">Komisi Dibayar</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:26px">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            </div></div></div>
            <div class="col-md-4"><div class="card card-flush"><div class="card-body text-center">
                <div class="text-muted fs-8">Transaksi Referral</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:26px">{{ $countRef }}</div>
            </div></div></div>
        </div>

        {{-- RIWAYAT KOMISI --}}
        <div class="card card-flush">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Riwayat Komisi</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gy-3">
                        <thead><tr class="fw-bold text-muted fs-7 text-uppercase">
                            <th>Waktu</th><th>Event</th><th class="text-end">Nilai Pesanan</th><th class="text-center">Rate</th><th class="text-end">Komisi</th><th>Status</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($commissions as $c)
                                <tr>
                                    <td class="text-muted fs-8">{{ $c->created_at->format('d M Y H:i') }}</td>
                                    <td class="fs-7">{{ $c->order?->event?->title ?? '—' }}</td>
                                    <td class="text-end fs-7">Rp {{ number_format($c->base_amount, 0, ',', '.') }}</td>
                                    <td class="text-center fs-7">{{ rtrim(rtrim(number_format($c->rate, 2), '0'), '.') }}%</td>
                                    <td class="text-end fw-bold fs-7">Rp {{ number_format($c->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge badge-light-{{ $c->status === 'paid' ? 'success' : 'warning' }}">{{ $c->status === 'paid' ? 'Dibayar' : 'Pending' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-8">Belum ada komisi. Bagikan link referralmu!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
