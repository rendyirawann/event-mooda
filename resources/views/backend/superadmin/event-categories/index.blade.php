@extends('backend.layout.app')
@section('title', 'Kategori Event')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="alert alert-primary fs-7">
            Master <b>kategori event</b> — dipakai saat organizer membuat event & sebagai filter di landing.
            <b>Ikon</b> boleh emoji (mis. 🎵). <b>Warna</b> = dua kode hex dipisah koma (gradient), mis. <code>#7c3aed,#ec4899</code>.
        </div>

        {{-- TAMBAH --}}
        <div class="card card-flush mb-6">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Tambah Kategori</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('event-categories.store') }}">
                    @csrf
                    <div class="row g-4 align-items-end">
                        <div class="col-md-4"><label class="form-label required fw-semibold">Nama</label><input name="name" class="form-control form-control-solid" placeholder="Musik & Konser" required></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Ikon (emoji)</label><input name="icon" class="form-control form-control-solid" placeholder="🎵" maxlength="8"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Warna (hex,hex)</label><input name="color" class="form-control form-control-solid" placeholder="#7c3aed,#ec4899"></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control form-control-solid" min="0" placeholder="auto"></div>
                        <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Tambah</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DAFTAR --}}
        <div class="card card-flush">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Daftar Kategori ({{ $categories->count() }})</h3></div>
            <div class="card-body">
                @forelse ($categories as $c)
                    <div class="border rounded p-4 mb-3 {{ $c->is_active ? '' : 'bg-light-secondary' }}">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-grid rounded flex-shrink-0" style="width:44px;height:44px;place-items:center;font-size:22px;color:#fff;background:{{ $c->gradient() }};">{{ $c->icon }}</span>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge badge-light-secondary">#{{ $c->sort_order }}</span>
                                        <span class="fw-bold fs-6">{{ $c->name }}</span>
                                        <span class="badge badge-light-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        <span class="badge badge-light-info">{{ $c->events_count }} event</span>
                                    </div>
                                    <span class="text-muted fs-8">{{ $c->slug }}</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" data-bs-target="#ec-{{ $c->id }}">Edit</button>
                                <form method="POST" action="{{ route('event-categories.toggle', $c) }}" class="m-0">@csrf
                                    <button type="submit" class="btn btn-sm btn-light-{{ $c->is_active ? 'warning' : 'success' }}">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                <form method="POST" action="{{ route('event-categories.destroy', $c) }}" class="m-0" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="collapse mt-4" id="ec-{{ $c->id }}">
                            <form method="POST" action="{{ route('event-categories.update', $c) }}" class="border-top pt-4">
                                @csrf @method('PUT')
                                <div class="row g-4 align-items-end">
                                    <div class="col-md-4"><label class="form-label required fw-semibold">Nama</label><input name="name" value="{{ $c->name }}" class="form-control form-control-solid" required></div>
                                    <div class="col-md-2"><label class="form-label fw-semibold">Ikon</label><input name="icon" value="{{ $c->icon }}" class="form-control form-control-solid" maxlength="8"></div>
                                    <div class="col-md-3"><label class="form-label fw-semibold">Warna</label><input name="color" value="{{ $c->color }}" class="form-control form-control-solid"></div>
                                    <div class="col-md-2"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" value="{{ $c->sort_order }}" class="form-control form-control-solid" min="0"></div>
                                    <div class="col-md-1"><button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-8">Belum ada kategori. Tambahkan lewat form di atas.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
