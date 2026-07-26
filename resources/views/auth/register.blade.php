@extends('auth.app')
@section('title', 'Daftar')

@section('content')
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-start p-8 p-lg-12">

        <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-8 p-lg-10 shadow-lg">

            <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-100 w-md-450px">

                <div class="d-flex flex-center flex-column flex-column-fluid mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <span class="d-grid rounded" style="width:46px;height:46px;place-items:center;background:linear-gradient(135deg,#ff2d3f,#a10e1a);color:#fff;box-shadow:0 8px 20px -6px rgba(225,29,42,.6);">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                        </span>
                        <span class="fs-1 fw-bolder text-gray-900">Event<span style="color:#e11d2a">Mooda</span></span>
                    </div>
                </div>

                <div class="d-flex flex-center flex-column flex-column-fluid py-6">

                    <form class="form w-100" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="text-center mb-6">
                            <h1 class="text-gray-900 fw-bolder mb-2">Daftar ke Event Mooda</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Buat akun gratis untuk beli tiket, atau jual tiket eventmu.</div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-4 mb-6">
                                <i class="ki-outline ki-information-5 fs-2 text-danger me-3"></i>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">Periksa kembali isian Anda:</span>
                                    <ul class="mb-0 mt-1 ps-4 fs-7">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Daftar sebagai --}}
                        <div class="fv-row mb-4">
                            <label class="form-label fw-semibold required">Daftar sebagai</label>
                            <div class="d-flex gap-2">
                                @php $curRole = old('role', 'buyer'); @endphp
                                <input type="radio" class="btn-check" name="role" value="buyer" id="role-buyer" @checked($curRole === 'buyer')>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-danger flex-fill py-3 fw-bold" for="role-buyer">🎟️ Pembeli Tiket</label>
                                <input type="radio" class="btn-check" name="role" value="organizer" id="role-organizer" @checked($curRole === 'organizer')>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-danger flex-fill py-3 fw-bold" for="role-organizer">🎪 Penyelenggara</label>
                            </div>
                            <div class="form-text">Pembeli: beli tiket event. Penyelenggara: buat & jual tiket eventmu.</div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="fv-row mb-4">
                            <label class="form-label fw-semibold required">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control bg-transparent" placeholder="Nama lengkap Anda" />
                            @error('name')<div class="text-danger fs-7 mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- No WhatsApp --}}
                        <div class="fv-row mb-4">
                            <label class="form-label fw-semibold">No. WhatsApp / Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control bg-transparent" placeholder="08xxxxxxxxxx" />
                            @error('phone')<div class="text-danger fs-7 mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="fv-row mb-4">
                            <label class="form-label fw-semibold required">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control bg-transparent" placeholder="email@bisnis.com" />
                            @error('email')<div class="text-danger fs-7 mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Password --}}
                        <div class="fv-row mb-4" data-kt-password-meter="true">
                            <label class="form-label fw-semibold required">Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" autocomplete="new-password"
                                    class="form-control bg-transparent" id="regPassword" placeholder="Minimal 8 karakter" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="regTogglePassword">
                                    <i class="ki-outline ki-eye-slash fs-2" id="regToggleIcon"></i>
                                </span>
                            </div>
                            @error('password')<div class="text-danger fs-7 mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="fv-row mb-6">
                            <label class="form-label fw-semibold required">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                class="form-control bg-transparent" placeholder="Ulangi password" />
                        </div>

                        <div class="d-grid mb-6">
                            <button type="submit" class="btn btn-danger" id="regSubmitBtn">
                                <span class="reg-idle">Daftar Gratis</span>
                                <span class="reg-loading d-none"><span class="spinner-border spinner-border-sm align-middle me-2"></span>Memproses…</span>
                            </button>
                        </div>

                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="link-danger">Masuk</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const regToggle = document.querySelector('#regTogglePassword');
            const regPass = document.querySelector('#regPassword');
            const regIcon = document.querySelector('#regToggleIcon');
            if (regToggle && regPass) {
                regToggle.addEventListener('click', function () {
                    const type = regPass.getAttribute('type') === 'password' ? 'text' : 'password';
                    regPass.setAttribute('type', type);
                    regIcon.classList.toggle('ki-eye-slash', type === 'password');
                    regIcon.classList.toggle('ki-eye', type === 'text');
                });
            }

            // Loader tombol daftar (anti klik 2x). Hanya jalan bila validasi HTML5 lolos.
            var regBtn = document.getElementById('regSubmitBtn');
            if (regBtn && regBtn.closest('form')) {
                regBtn.closest('form').addEventListener('submit', function () {
                    regBtn.disabled = true;
                    regBtn.querySelector('.reg-idle').classList.add('d-none');
                    regBtn.querySelector('.reg-loading').classList.remove('d-none');
                });
            }
        </script>
    @endpush
@endsection
