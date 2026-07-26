@extends('backend.layout.app')
@php $isEdit = $event->exists; @endphp
@section('title', $isEdit ? 'Kelola Event' : 'Buat Event')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold mb-0">{{ $isEdit ? 'Kelola Event' : 'Buat Event' }}</h2>
            <a href="{{ route('organizer.events.index') }}" class="btn btn-sm btn-light">← Kembali</a>
        </div>

        {{-- ===== DETAIL EVENT ===== --}}
        <div class="card card-flush mb-6">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Detail Event</h3>
                @if ($isEdit)
                    <div class="card-toolbar">
                        @if ($event->status === 'published')<span class="badge badge-light-success fs-7">Published</span>
                        @else<span class="badge badge-light-warning fs-7">Draft</span>@endif
                    </div>
                @endif
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $isEdit ? route('organizer.events.update', $event) : route('organizer.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($isEdit) @method('PUT') @endif
                    <div class="row g-4">
                        <div class="col-md-8"><label class="form-label required fw-semibold">Judul Event</label>
                            <input name="title" value="{{ old('title', $event->title) }}" class="form-control form-control-solid" placeholder="Konser Akbar 2026" required></div>
                        <div class="col-md-4"><label class="form-label required fw-semibold">Kategori</label>
                            <select name="event_category_id" class="form-select form-select-solid" required>
                                <option value="">— pilih —</option>
                                @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('event_category_id', $event->event_category_id) == $c->id)>{{ $c->icon }} {{ $c->name }}</option>@endforeach
                            </select></div>

                        <div class="col-md-4"><label class="form-label required fw-semibold">Kota</label>
                            <select name="city_id" class="form-select form-select-solid" required>
                                <option value="">— pilih —</option>
                                @foreach ($cities->groupBy(fn ($c) => $c->province?->name ?? 'Lainnya') as $prov => $group)
                                    <optgroup label="{{ $prov }}">
                                        @foreach ($group as $c)<option value="{{ $c->id }}" @selected(old('city_id', $event->city_id) == $c->id)>{{ $c->name }}</option>@endforeach
                                    </optgroup>
                                @endforeach
                            </select></div>
                        <div class="col-md-8"><label class="form-label fw-semibold">Tagline (singkat)</label>
                            <input name="tagline" value="{{ old('tagline', $event->tagline) }}" class="form-control form-control-solid" placeholder="Satu malam tak terlupakan" maxlength="200"></div>

                        <div class="col-md-6"><label class="form-label fw-semibold">Nama Venue</label>
                            <input name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" class="form-control form-control-solid" placeholder="GBK Senayan"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Alamat Venue</label>
                            <input name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" class="form-control form-control-solid" placeholder="Jl. ..."></div>

                        <div class="col-md-6"><label class="form-label fw-semibold">Mulai</label>
                            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control form-control-solid"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Selesai</label>
                            <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}" class="form-control form-control-solid"></div>

                        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" rows="4" class="form-control form-control-solid" placeholder="Ceritakan tentang acaramu...">{{ old('description', $event->description) }}</textarea></div>

                        <div class="col-md-8"><label class="form-label fw-semibold">Poster (maks 2MB)</label>
                            <input type="file" name="poster_image" accept="image/*" class="form-control form-control-solid"></div>
                        @if ($isEdit && $event->posterUrl())
                            <div class="col-md-4">
                                <img src="{{ $event->posterUrl() }}" class="rounded" style="height:90px;object-fit:cover;">
                                <label class="form-check form-check-custom mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_poster" value="1">
                                    <span class="form-check-label fw-semibold ms-2 text-danger">Hapus poster</span>
                                </label>
                            </div>
                        @endif

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Event' }}</button>
                            @if (! $isEdit)<span class="text-muted align-self-center fs-7">Setelah dibuat, tambahkan jenis tiket lalu publish.</span>@endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($isEdit)
            {{-- ===== PUBLISH ===== --}}
            <div class="card card-flush mb-6">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <div class="fw-bold">{{ $event->status === 'published' ? 'Event tampil di landing' : 'Event masih draft' }}</div>
                        <span class="text-muted fs-7">Publish memerlukan minimal 1 jenis tiket aktif.</span>
                    </div>
                    <form method="POST" action="{{ route('organizer.events.toggle', $event) }}" class="m-0">@csrf
                        <button type="submit" class="btn btn-{{ $event->status === 'published' ? 'light-warning' : 'success' }}">
                            {{ $event->status === 'published' ? 'Jadikan Draft' : 'Publish Sekarang' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- ===== JENIS TIKET ===== --}}
            <div class="card card-flush">
                <div class="card-header pt-5"><h3 class="card-title fw-bold">Jenis Tiket ({{ $event->ticketTypes->count() }})</h3></div>
                <div class="card-body">
                    {{-- tambah --}}
                    <form method="POST" action="{{ route('organizer.tickets.store', $event) }}" class="border rounded p-4 mb-4 bg-light-primary">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3"><label class="form-label required fw-semibold fs-7">Nama Tiket</label><input name="name" class="form-control form-control-solid" placeholder="Reguler / VIP" required></div>
                            <div class="col-md-2"><label class="form-label required fw-semibold fs-7">Harga (Rp)</label><input type="number" name="price" class="form-control form-control-solid" min="0" placeholder="0 = gratis" required></div>
                            <div class="col-md-2"><label class="form-label required fw-semibold fs-7">Kuota</label><input type="number" name="quota" class="form-control form-control-solid" min="0" required></div>
                            <div class="col-md-2"><label class="form-label fw-semibold fs-7">Maks/Pesanan</label><input type="number" name="max_per_order" class="form-control form-control-solid" min="1" value="10"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold fs-7">Keterangan</label><input name="description" class="form-control form-control-solid" placeholder="opsional"></div>
                            <div class="col-12"><button type="submit" class="btn btn-sm btn-primary">+ Tambah Jenis Tiket</button></div>
                        </div>
                    </form>

                    {{-- daftar --}}
                    @forelse ($event->ticketTypes as $tt)
                        <div class="border rounded p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fw-bold fs-6">{{ $tt->name }}</span>
                                        <span class="badge badge-light-primary">{{ $tt->price > 0 ? 'Rp ' . number_format($tt->price, 0, ',', '.') : 'GRATIS' }}</span>
                                        <span class="badge badge-light-{{ $tt->is_active ? 'success' : 'secondary' }}">{{ $tt->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </div>
                                    <span class="text-muted fs-7">Terjual {{ $tt->sold }} / {{ $tt->quota }} · sisa {{ $tt->remaining() }} · maks {{ $tt->max_per_order }}/pesanan @if ($tt->description) · {{ $tt->description }} @endif</span>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" data-bs-target="#tt-{{ $tt->id }}">Edit</button>
                                    <form method="POST" action="{{ route('organizer.tickets.destroy', [$event, $tt]) }}" class="m-0" onsubmit="return confirm('Hapus jenis tiket ini?')">@csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            <div class="collapse mt-4" id="tt-{{ $tt->id }}">
                                <form method="POST" action="{{ route('organizer.tickets.update', [$event, $tt]) }}" class="border-top pt-4">
                                    @csrf @method('PUT')
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3"><label class="form-label fw-semibold fs-7">Nama</label><input name="name" value="{{ $tt->name }}" class="form-control form-control-solid" required></div>
                                        <div class="col-md-2"><label class="form-label fw-semibold fs-7">Harga</label><input type="number" name="price" value="{{ $tt->price }}" class="form-control form-control-solid" min="0" required></div>
                                        <div class="col-md-2"><label class="form-label fw-semibold fs-7">Kuota</label><input type="number" name="quota" value="{{ $tt->quota }}" class="form-control form-control-solid" min="0" required></div>
                                        <div class="col-md-2"><label class="form-label fw-semibold fs-7">Maks/Pesanan</label><input type="number" name="max_per_order" value="{{ $tt->max_per_order }}" class="form-control form-control-solid" min="1"></div>
                                        <div class="col-md-3"><label class="form-label fw-semibold fs-7">Keterangan</label><input name="description" value="{{ $tt->description }}" class="form-control form-control-solid"></div>
                                        <div class="col-md-4">
                                            <label class="form-check form-switch form-check-custom">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($tt->is_active)>
                                                <span class="form-check-label fw-semibold ms-2 fs-7">Aktif (bisa dibeli)</span>
                                            </label>
                                        </div>
                                        <div class="col-md-8 text-end"><button type="submit" class="btn btn-sm btn-primary">Simpan</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-6">Belum ada jenis tiket. Tambahkan lewat form di atas.</div>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
