@extends('backend.layout.app')
@section('title', 'Pencairan Dana')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <h2 class="fw-bold mb-5">Pencairan Dana</h2>

        <div class="row g-4 mb-6">
            <div class="col-md-4"><div class="card card-flush"><div class="card-body text-center">
                <div class="text-muted fs-8">Total Pendapatan (lunas)</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:24px">Rp {{ number_format($gross, 0, ',', '.') }}</div>
            </div></div></div>
            <div class="col-md-4"><div class="card card-flush"><div class="card-body text-center">
                <div class="text-muted fs-8">Diminta / Dicairkan</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:24px">Rp {{ number_format($withdrawn, 0, ',', '.') }}</div>
            </div></div></div>
            <div class="col-md-4"><div class="card" style="background:linear-gradient(120deg,#7c3aed,#ec4899);color:#fff"><div class="card-body text-center">
                <div style="opacity:.9;font-size:12px">Saldo Tersedia</div>
                <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:24px">Rp {{ number_format($available, 0, ',', '.') }}</div>
            </div></div></div>
        </div>

        <div class="card card-flush mb-6">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Ajukan Pencairan</h3></div>
            <div class="card-body">
                @if ($available < 10000)
                    <div class="text-muted">Saldo belum cukup untuk pencairan (min Rp10.000).</div>
                @else
                    <form method="POST" action="{{ route('organizer.payout.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-3"><label class="form-label required fw-semibold">Jumlah (Rp)</label><input type="number" name="amount" class="form-control form-control-solid" min="10000" max="{{ $available }}" required></div>
                            <div class="col-md-3"><label class="form-label required fw-semibold">Metode</label><input name="method" class="form-control form-control-solid" placeholder="BCA / DANA / OVO" required></div>
                            <div class="col-md-3"><label class="form-label required fw-semibold">No. Rekening / Akun</label><input name="account" class="form-control form-control-solid" required></div>
                            <div class="col-md-3"><label class="form-label required fw-semibold">Atas Nama</label><input name="account_name" class="form-control form-control-solid" required></div>
                            <div class="col-md-9"><label class="form-label fw-semibold">Catatan</label><input name="note" class="form-control form-control-solid" placeholder="opsional"></div>
                            <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100">Ajukan Pencairan</button></div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Riwayat Pencairan</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gy-3">
                        <thead><tr class="fw-bold text-muted fs-7 text-uppercase">
                            <th>Waktu</th><th class="text-end">Jumlah</th><th>Tujuan</th><th>Status</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($payouts as $p)
                                <tr>
                                    <td class="text-muted fs-8">{{ $p->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end fw-bold fs-7">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td class="fs-7">{{ $p->method }} · {{ $p->account }} <span class="text-muted">({{ $p->account_name }})</span></td>
                                    <td><span class="badge badge-light-{{ ['requested' => 'warning', 'paid' => 'success', 'rejected' => 'danger'][$p->status] ?? 'secondary' }}">{{ ['requested' => 'Diproses', 'paid' => 'Dibayar', 'rejected' => 'Ditolak'][$p->status] ?? $p->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-8">Belum ada pencairan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
