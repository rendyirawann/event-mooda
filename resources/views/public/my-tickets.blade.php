@extends('public.layout')
@section('title', 'Tiket Saya — Event Mooda')
@section('content')
<div class="wrap" style="max-width:760px;padding:36px 24px">
    <h1 style="margin-bottom:20px">🎫 Tiket Saya</h1>
    @if (session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-err">{{ session('error') }}</div>@endif

    @forelse ($orders as $o)
        <a href="{{ route('my-tickets.show', $o) }}" class="card" style="display:flex;justify-content:space-between;align-items:center;padding:18px 20px;margin-bottom:12px">
            <div>
                <div style="font-family:'Plus Jakarta Sans';font-weight:700">{{ $o->event->title }}</div>
                <div class="muted" style="font-size:13.5px">{{ $o->invoice_no }} · {{ $o->paid_at?->format('d M Y') }} · {{ $o->tickets()->count() }} tiket</div>
            </div>
            <span class="btn btn-ghost btn-sm">Lihat →</span>
        </a>
    @empty
        <div class="card muted" style="padding:44px;text-align:center">
            Belum ada tiket. <a href="/" style="color:var(--brand);font-weight:600">Jelajahi event →</a>
        </div>
    @endforelse
</div>
@endsection
