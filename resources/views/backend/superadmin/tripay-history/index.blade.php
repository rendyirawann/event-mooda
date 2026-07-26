@extends('backend.layout.app')
@section('title', 'Riwayat Pembayaran Tripay')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
            <div>
                <h2 class="fw-bold mb-1">Riwayat Pembayaran Tripay</h2>
                <span class="text-muted">Semua transaksi <b>POS / Mooda</b> & <b>Tiket Event</b> dalam satu tempat (sumber: Tripay {{ $production ? 'Produksi' : 'Sandbox' }}).</span>
            </div>
        </div>

        <div class="alert alert-primary fs-7">
            Ditandai otomatis dari prefix invoice: <span class="badge badge-light-success">Tiket Event</span> = <code>TIX-…</code>,
            <span class="badge badge-light-primary">POS / Mooda</span> = lainnya (<code>DSP-DEP-</code>, <code>MDA-INV-</code>, dll).
        </div>

        @if ($error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        {{-- FILTER --}}
        <div class="card card-flush mb-5">
            <div class="card-body py-4">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <label class="fw-semibold fs-7 text-muted">Status:</label>
                        <select name="status" class="form-select form-select-sm form-select-solid w-150px" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach (['PAID' => 'Lunas', 'UNPAID' => 'Belum Bayar', 'EXPIRED' => 'Kedaluwarsa', 'FAILED' => 'Gagal', 'REFUND' => 'Refund'] as $k => $v)
                                <option value="{{ $k }}" @selected($status === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="btn-group" role="group" id="typeFilter">
                        <button type="button" class="btn btn-sm btn-light-primary active" data-type="all">Semua</button>
                        <button type="button" class="btn btn-sm btn-light-primary" data-type="event">Tiket Event</button>
                        <button type="button" class="btn btn-sm btn-light-primary" data-type="pos">POS / Mooda</button>
                    </div>
                    <input type="text" id="searchBox" class="form-control form-control-sm form-control-solid w-250px ms-auto" placeholder="Cari invoice / pelanggan / ref...">
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="card card-flush">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gy-3" id="txTable">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th>Waktu</th>
                                <th>Invoice (Merchant Ref)</th>
                                <th>Tipe</th>
                                <th>Pelanggan</th>
                                <th>Metode</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $r)
                                @php
                                    $badge = ['PAID' => 'success', 'UNPAID' => 'warning', 'EXPIRED' => 'secondary', 'FAILED' => 'danger', 'REFUND' => 'info'][$r['status']] ?? 'secondary';
                                @endphp
                                <tr data-type="{{ $r['type'] }}" data-search="{{ strtolower($r['merchant_ref'] . ' ' . $r['customer'] . ' ' . $r['reference']) }}">
                                    <td class="text-muted fs-8" style="white-space:nowrap">{{ $r['created_at'] ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold fs-7">{{ $r['merchant_ref'] ?: '-' }}</div>
                                        <div class="text-muted fs-8">{{ $r['reference'] }}</div>
                                    </td>
                                    <td><span class="badge badge-light-{{ $r['type'] === 'event' ? 'success' : 'primary' }}">{{ $r['type_label'] }}</span></td>
                                    <td class="fs-7">{{ $r['customer'] }}</td>
                                    <td class="fs-7">{{ $r['method'] }}</td>
                                    <td class="text-end fw-bold fs-7" style="white-space:nowrap">Rp {{ number_format($r['amount'], 0, ',', '.') }}</td>
                                    <td><span class="badge badge-light-{{ $badge }}">{{ $r['status'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-8">{{ $error ? 'Gagal memuat.' : 'Belum ada transaksi.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div id="noResult" class="text-center text-muted py-6 d-none">Tidak ada baris cocok dengan filter.</div>
                </div>

                {{-- PAGINATION --}}
                @if (! empty($pagination))
                    @php
                        $cur = (int) ($pagination['current_page'] ?? 1);
                        $last = (int) ($pagination['last_page'] ?? 1);
                        $total = (int) ($pagination['total_records'] ?? 0);
                        $q = fn ($p) => route('tripay-history.index', array_filter(['page' => $p, 'status' => $status]));
                    @endphp
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                        <span class="text-muted fs-8">Total {{ number_format($total) }} transaksi · halaman {{ $cur }} dari {{ $last }}</span>
                        <div class="d-flex gap-2">
                            <a href="{{ $q(max(1, $cur - 1)) }}" class="btn btn-sm btn-light {{ $cur <= 1 ? 'disabled' : '' }}">← Sebelumnya</a>
                            <a href="{{ $q(min($last, $cur + 1)) }}" class="btn btn-sm btn-light {{ $cur >= $last ? 'disabled' : '' }}">Berikutnya →</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Filter tipe (client-side, dalam halaman aktif)
    var curType = 'all', curSearch = '';
    var rows = Array.prototype.slice.call(document.querySelectorAll('#txTable tbody tr[data-type]'));
    function apply() {
        var shown = 0;
        rows.forEach(function (tr) {
            var okType = curType === 'all' || tr.dataset.type === curType;
            var okSearch = !curSearch || (tr.dataset.search || '').indexOf(curSearch) !== -1;
            var show = okType && okSearch;
            tr.style.display = show ? '' : 'none';
            if (show) shown++;
        });
        document.getElementById('noResult').classList.toggle('d-none', shown > 0 || rows.length === 0);
    }
    document.querySelectorAll('#typeFilter button').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('#typeFilter button').forEach(function (x) { x.classList.remove('active'); });
            b.classList.add('active'); curType = b.dataset.type; apply();
        });
    });
    document.getElementById('searchBox').addEventListener('input', function () { curSearch = this.value.toLowerCase().trim(); apply(); });
</script>
@endpush
@endsection
