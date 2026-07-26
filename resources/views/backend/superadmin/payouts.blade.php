@extends('backend.layout.app')
@section('title', 'Kelola Pencairan')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <h2 class="fw-bold mb-1">Kelola Pencairan</h2>
        <p class="text-muted mb-5">Permintaan pencairan dana dari penyelenggara.</p>

        <div class="card card-flush">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gy-3">
                        <thead><tr class="fw-bold text-muted fs-7 text-uppercase">
                            <th>Waktu</th><th>Organizer</th><th class="text-end">Jumlah</th><th>Tujuan</th><th>Status</th><th class="text-end">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($payouts as $p)
                                <tr>
                                    <td class="text-muted fs-8">{{ $p->created_at->format('d M Y H:i') }}</td>
                                    <td class="fs-7">{{ $p->organizer?->name }}<br><span class="text-muted fs-8">{{ $p->organizer?->email }}</span></td>
                                    <td class="text-end fw-bold fs-7">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td class="fs-7">{{ $p->method }} · {{ $p->account }}<br><span class="text-muted fs-8">{{ $p->account_name }}{{ $p->note ? ' — ' . $p->note : '' }}</span></td>
                                    <td><span class="badge badge-light-{{ ['requested' => 'warning', 'paid' => 'success', 'rejected' => 'danger'][$p->status] ?? 'secondary' }}">{{ ['requested' => 'Diproses', 'paid' => 'Dibayar', 'rejected' => 'Ditolak'][$p->status] ?? $p->status }}</span></td>
                                    <td class="text-end">
                                        @if ($p->status === 'requested')
                                            <div class="d-flex gap-2 justify-content-end">
                                                <form method="POST" action="{{ route('payouts.update', $p) }}" class="m-0">@csrf @method('PUT')<input type="hidden" name="status" value="paid"><button class="btn btn-sm btn-light-success" onclick="return confirm('Tandai sudah dibayar?')">Tandai Dibayar</button></form>
                                                <form method="POST" action="{{ route('payouts.update', $p) }}" class="m-0">@csrf @method('PUT')<input type="hidden" name="status" value="rejected"><button class="btn btn-sm btn-light-danger" onclick="return confirm('Tolak pencairan ini?')">Tolak</button></form>
                                            </div>
                                        @else
                                            <span class="text-muted fs-8">{{ $p->processed_at?->format('d M H:i') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-8">Belum ada permintaan pencairan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
