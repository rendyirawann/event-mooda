@extends('backend.layout.app')
@section('title', 'Kota')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="alert alert-primary fs-7">
            Master <b>kota</b> + <b>foto monumen</b>. Kota dengan status <b>Tampil di Landing</b> muncul di seksi "Jelajahi Event per Kota".
            Unggah <b>foto monumen</b> (maks 1.5MB) agar tampil lebih menarik — jika kosong, dipakai <b>emoji</b> + warna gradient.
        </div>

        {{-- TAMBAH --}}
        <div class="card card-flush mb-6">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Tambah Kota</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('cities.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label required fw-semibold">Provinsi</label>
                            <select name="province_id" class="form-select form-select-solid" required>
                                <option value="">— pilih —</option>
                                @foreach ($provinces as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-3"><label class="form-label required fw-semibold">Nama Kota</label><input name="name" class="form-control form-control-solid" placeholder="Bandung" required></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Emoji Monumen</label><input name="landmark_emoji" class="form-control form-control-solid" placeholder="🏛️" maxlength="8"></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Warna</label><input name="color" class="form-control form-control-solid" placeholder="#6366f1,#ec4899"></div>
                        <div class="col-md-2"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control form-control-solid" min="0" placeholder="auto"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Foto Monumen (opsional)</label><input type="file" name="landmark_image" accept="image/*" class="form-control form-control-solid"></div>
                        <div class="col-md-3 d-flex align-items-center pt-6">
                            <label class="form-check form-switch form-check-custom">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                                <span class="form-check-label fw-semibold ms-2">Tampil di Landing</span>
                            </label>
                        </div>
                        <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100">Tambah Kota</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DAFTAR --}}
        <div class="card card-flush">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Daftar Kota ({{ $cities->count() }})</h3></div>
            <div class="card-body">
                @forelse ($cities as $city)
                    <div class="border rounded p-4 mb-3 {{ $city->is_active ? '' : 'bg-light-secondary' }}">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                @if ($city->landmarkUrl())
                                    <span class="rounded flex-shrink-0" style="width:72px;height:48px;background-image:url('{{ $city->landmarkUrl() }}');background-size:cover;background-position:center;display:block;"></span>
                                @else
                                    <span class="d-grid rounded flex-shrink-0" style="width:72px;height:48px;place-items:center;font-size:24px;color:#fff;background:{{ $city->gradient() }};">{{ $city->landmark_emoji }}</span>
                                @endif
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge badge-light-secondary">#{{ $city->sort_order }}</span>
                                        <span class="fw-bold fs-6">{{ $city->name }}</span>
                                        <span class="badge badge-light-primary">{{ $city->province?->name }}</span>
                                        @if ($city->is_featured)<span class="badge badge-light-success">Di Landing</span>@endif
                                        <span class="badge badge-light-info">{{ $city->events_count }} event</span>
                                    </div>
                                    <span class="text-muted fs-8">{{ $city->slug }}{{ $city->landmark_image ? ' · ada foto' : ' · emoji' }}</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" data-bs-target="#ct-{{ $city->id }}">Edit</button>
                                <form method="POST" action="{{ route('cities.toggle', $city) }}" class="m-0">@csrf
                                    <button type="submit" class="btn btn-sm btn-light-{{ $city->is_featured ? 'warning' : 'success' }}">{{ $city->is_featured ? 'Sembunyikan' : 'Tampilkan' }}</button>
                                </form>
                                <form method="POST" action="{{ route('cities.destroy', $city) }}" class="m-0" onsubmit="return confirm('Hapus kota ini?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="collapse mt-4" id="ct-{{ $city->id }}">
                            <form method="POST" action="{{ route('cities.update', $city) }}" enctype="multipart/form-data" class="border-top pt-4">
                                @csrf @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label class="form-label required fw-semibold">Provinsi</label>
                                        <select name="province_id" class="form-select form-select-solid" required>
                                            @foreach ($provinces as $p)<option value="{{ $p->id }}" @selected($p->id === $city->province_id)>{{ $p->name }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"><label class="form-label required fw-semibold">Nama</label><input name="name" value="{{ $city->name }}" class="form-control form-control-solid" required></div>
                                    <div class="col-md-2"><label class="form-label fw-semibold">Emoji</label><input name="landmark_emoji" value="{{ $city->landmark_emoji }}" class="form-control form-control-solid" maxlength="8"></div>
                                    <div class="col-md-2"><label class="form-label fw-semibold">Warna</label><input name="color" value="{{ $city->color }}" class="form-control form-control-solid"></div>
                                    <div class="col-md-2"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" value="{{ $city->sort_order }}" class="form-control form-control-solid" min="0"></div>
                                    <div class="col-md-6"><label class="form-label fw-semibold">Ganti Foto Monumen</label><input type="file" name="landmark_image" accept="image/*" class="form-control form-control-solid"></div>
                                    <div class="col-md-3 d-flex align-items-center pt-6">
                                        <label class="form-check form-switch form-check-custom">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked($city->is_featured)>
                                            <span class="form-check-label fw-semibold ms-2">Tampil di Landing</span>
                                        </label>
                                    </div>
                                    @if ($city->landmark_image)
                                        <div class="col-md-3 d-flex align-items-center pt-6">
                                            <label class="form-check form-check-custom">
                                                <input class="form-check-input" type="checkbox" name="remove_image" value="1">
                                                <span class="form-check-label fw-semibold ms-2 text-danger">Hapus foto</span>
                                            </label>
                                        </div>
                                    @endif
                                    <div class="col-12"><button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-8">Belum ada kota. Tambahkan lewat form di atas.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
