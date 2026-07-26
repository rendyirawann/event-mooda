@extends('backend.layout.app')
@section('title', 'Event Saya')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
            <div>
                <h2 class="fw-bold mb-1">Event Saya</h2>
                <span class="text-muted">Buat & kelola acara beserta jenis tiketnya.</span>
            </div>
            <a href="{{ route('organizer.events.create') }}" class="btn btn-primary"><i class="ki-outline ki-plus fs-3"></i> Buat Event</a>
        </div>

        <div class="card card-flush">
            <div class="card-body">
                @forelse ($events as $e)
                    <div class="border rounded p-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                @if ($e->posterUrl())
                                    <span class="rounded flex-shrink-0" style="width:84px;height:56px;background-image:url('{{ $e->posterUrl() }}');background-size:cover;background-position:center;display:block;"></span>
                                @else
                                    <span class="d-grid rounded flex-shrink-0" style="width:84px;height:56px;place-items:center;color:#fff;font-size:11px;text-align:center;background:{{ $e->gradient() }};">Tanpa<br>Poster</span>
                                @endif
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fw-bold fs-6">{{ $e->title }}</span>
                                        @if ($e->status === 'published')
                                            <span class="badge badge-light-success">Published</span>
                                        @elseif ($e->status === 'archived')
                                            <span class="badge badge-light-secondary">Arsip</span>
                                        @else
                                            <span class="badge badge-light-warning">Draft</span>
                                        @endif
                                    </div>
                                    <span class="text-muted fs-7">
                                        {{ $e->category?->name }} · {{ $e->city?->name }}
                                        @if ($e->starts_at) · {{ $e->starts_at->format('d M Y, H:i') }} @endif
                                        · {{ $e->ticket_types_count }} jenis tiket
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <a href="{{ route('organizer.events.edit', $e) }}" class="btn btn-sm btn-light-primary">Kelola</a>
                                <form method="POST" action="{{ route('organizer.events.toggle', $e) }}" class="m-0">@csrf
                                    <button type="submit" class="btn btn-sm btn-light-{{ $e->status === 'published' ? 'warning' : 'success' }}">
                                        {{ $e->status === 'published' ? 'Jadikan Draft' : 'Publish' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('organizer.events.destroy', $e) }}" class="m-0" onsubmit="return confirm('Hapus event ini beserta tiketnya?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="fs-2 mb-2">🎫</div>
                        <div class="text-muted mb-4">Belum ada event. Yuk buat event pertamamu!</div>
                        <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">Buat Event</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
