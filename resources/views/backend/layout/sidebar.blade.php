<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="275px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_toggle">

    {{-- Logo Event Mooda --}}
    <div class="d-flex align-items-center px-6 py-6" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-3 text-decoration-none">
            <span class="d-grid rounded flex-shrink-0" style="width:42px;height:42px;place-items:center;background:linear-gradient(135deg,#ff2d3f,#a10e1a);color:#fff;box-shadow:0 8px 18px -6px rgba(225,29,42,.6)">
                <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
            </span>
            <span style="font-family:'Playfair Display',Georgia,serif;font-weight:900;font-size:21px;color:var(--bs-gray-900)">Event<span style="color:#e11d2a">Mooda</span></span>
        </a>
    </div>
    <div class="separator mx-6"></div>

    {{-- Navigasi Event --}}
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid px-3 py-5">
        <div class="menu menu-column menu-rounded menu-active-bg menu-title-gray-800 menu-icon-gray-500 fw-semibold fs-6">
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="menu-icon"><i class="ki-outline ki-element-11 fs-2"></i></span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>

            @if (auth()->user()?->hasRole('organizer') || auth()->user()?->isSuperadmin())
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('organizer.events.*') || request()->routeIs('organizer.checkin.*') ? 'active' : '' }}" href="{{ route('organizer.events.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-calendar-8 fs-2"></i></span>
                        <span class="menu-title">Event Saya</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('organizer.payout.*') ? 'active' : '' }}" href="{{ route('organizer.payout.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-wallet fs-2"></i></span>
                        <span class="menu-title">Pencairan Dana</span>
                    </a>
                </div>
            @endif

            @if (auth()->user()?->hasRole('affiliate') || auth()->user()?->hasRole('reseller') || auth()->user()?->isSuperadmin())
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('referral.*') ? 'active' : '' }}" href="{{ route('referral.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-share fs-2"></i></span>
                        <span class="menu-title">Program Referral</span>
                    </a>
                </div>
            @endif

            @can('blog.manage')
                <div class="menu-item pt-5 pb-1">
                    <span class="menu-title text-uppercase fw-bold fs-8 px-3" style="letter-spacing:.14em;color:#e11d2a">Superadmin</span>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('event-categories.*') ? 'active' : '' }}" href="{{ route('event-categories.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span>
                        <span class="menu-title">Kategori Event</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('cities.*') ? 'active' : '' }}" href="{{ route('cities.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-geolocation fs-2"></i></span>
                        <span class="menu-title">Kota & Monumen</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('provinces.*') ? 'active' : '' }}" href="{{ route('provinces.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-map fs-2"></i></span>
                        <span class="menu-title">Provinsi</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('tripay-history.*') ? 'active' : '' }}" href="{{ route('tripay-history.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-credit-cart fs-2"></i></span>
                        <span class="menu-title">Riwayat Tripay</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('payouts.*') ? 'active' : '' }}" href="{{ route('payouts.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-dollar fs-2"></i></span>
                        <span class="menu-title">Kelola Pencairan</span>
                    </a>
                </div>
                @can('view_resources')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-profile-user fs-2"></i></span>
                            <span class="menu-title">User Management</span>
                        </a>
                    </div>
                @endcan
            @endcan
        </div>
    </div>

    {{-- Footer: user + logout --}}
    <div class="px-5 py-4 border-top border-gray-300">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2" style="min-width:0">
                <div class="symbol symbol-35px symbol-circle flex-shrink-0">
                    <img src="{{ Auth::user()->avatar ? asset('storage/user/avatar/' . Auth::user()->avatar) : asset('assets/media/logos/mooda-mark-192.png') }}" alt="user" />
                </div>
                <div style="line-height:1.15;min-width:0">
                    <div class="fw-bold fs-7 text-truncate">{{ Auth::user()->name }}</div>
                    <div class="text-muted fs-8">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0" id="logout-form">@csrf
                <button type="submit" class="btn btn-icon btn-sm btn-light-danger" title="Keluar"><i class="ki-outline ki-exit-right fs-4"></i></button>
            </form>
        </div>
        <a href="{{ route('account.index') }}" class="text-muted fs-8 d-block mt-3">Profil & Akun →</a>
    </div>
</div>
