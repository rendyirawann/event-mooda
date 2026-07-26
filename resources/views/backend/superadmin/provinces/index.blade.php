@extends('backend.layout.app')
@section('title', 'Provinsi')
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid mt-5">
    <div id="kt_app_content_container" class="app-container container-xxl">

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="alert alert-primary fs-7">Master <b>provinsi</b>. Setiap <b>kota</b> ditautkan ke sebuah provinsi.</div>

        {{-- TAMBAH --}}
        <div class="card card-flush mb-6">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Tambah Provinsi</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('provinces.store') }}">
                    @csrf
                    <div class="row g-4 align-items-end">
                        <div class="col-md-10"><label class="form-label required fw-semibold">Nama Provinsi</label><input name="name" class="form-control form-control-solid" placeholder="Jawa Barat" required></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Tambah</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DAFTAR --}}
        <div class="card card-flush">
            <div class="card-header pt-5"><h3 class="card-title fw-bold">Daftar Provinsi ({{ $provinces->count() }})</h3></div>
            <div class="card-body">
                @forelse ($provinces as $p)
                    <div class="border rounded p-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-bold fs-6">{{ $p->name }}</span>
                                <span class="badge badge-light-info">{{ $p->cities_count }} kota</span>
                                <span class="text-muted fs-8">{{ $p->slug }}</span>
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" data-bs-target="#pv-{{ $p->id }}">Edit</button>
                                <form method="POST" action="{{ route('provinces.destroy', $p) }}" class="m-0" onsubmit="return confirm('Hapus provinsi ini?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="collapse mt-4" id="pv-{{ $p->id }}">
                            <form method="POST" action="{{ route('provinces.update', $p) }}" class="border-top pt-4">
                                @csrf @method('PUT')
                                <div class="row g-4 align-items-end">
                                    <div class="col-md-10"><label class="form-label required fw-semibold">Nama</label><input name="name" value="{{ $p->name }}" class="form-control form-control-solid" required></div>
                                    <div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-8">Belum ada provinsi. Tambahkan lewat form di atas.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
