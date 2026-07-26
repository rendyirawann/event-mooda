{{-- Layout publik Event Mooda (bertema gelap/terang) — dipakai halaman detail event, checkout, tiket. --}}
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Event Mooda')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            var t = localStorage.getItem('em-theme');
            if (!t) t = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <style>
        :root {
            --bg:#fff; --bg-soft:#f5f6fb; --card:#fff; --text:#0d0f1c; --muted:#5a6079; --border:#e7e9f4;
            --brand:#e11d2a; --grad:linear-gradient(120deg,#ff2d3f 0%,#e11d2a 55%,#a10e1a 100%);
            --shadow:0 18px 50px -22px rgba(120,8,15,.30); --nav-bg:rgba(255,255,255,.86);
        }
        html[data-theme="dark"] {
            --bg:#0a0a0a; --bg-soft:#121212; --card:#181818; --text:#f5f5f5; --muted:#a0a0a0; --border:#2a2a2a;
            --shadow:0 22px 60px -20px rgba(0,0,0,.85); --nav-bg:rgba(10,10,10,.8);
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); color:var(--text); line-height:1.6; -webkit-font-smoothing:antialiased; overflow-x:hidden; }
        h1,h2,h3,.display { font-family:'Sora',sans-serif; letter-spacing:-.02em; line-height:1.12; }
        a { color:inherit; text-decoration:none; }
        img { max-width:100%; display:block; }
        .wrap { width:100%; max-width:1100px; margin:0 auto; padding:0 24px; }
        .grad-text { background:var(--grad); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .btn { display:inline-flex; align-items:center; gap:8px; border:0; cursor:pointer; font-family:'Sora',sans-serif; font-weight:700; font-size:15px; padding:13px 24px; border-radius:14px; transition:transform .15s,box-shadow .2s; white-space:nowrap; }
        .btn-grad { background:var(--grad); color:#fff; box-shadow:0 10px 30px -10px rgba(225,29,42,.6); }
        .btn-grad:hover { transform:translateY(-2px); }
        .btn-ghost { background:transparent; color:var(--text); border:1.5px solid var(--border); }
        .btn-ghost:hover { border-color:var(--brand); color:var(--brand); }
        .btn-sm { padding:10px 18px; font-size:14px; border-radius:11px; }
        .btn:disabled { opacity:.55; cursor:not-allowed; transform:none; }
        .card { background:var(--card); border:1px solid var(--border); border-radius:18px; }
        .muted { color:var(--muted); }
        .nav { position:sticky; top:0; z-index:50; background:var(--nav-bg); backdrop-filter:blur(16px); border-bottom:1px solid var(--border); }
        .nav-in { display:flex; align-items:center; gap:16px; height:68px; }
        .brand { display:flex; align-items:center; gap:10px; font-family:'Sora'; font-weight:800; font-size:20px; }
        .brand .logo { width:38px; height:38px; border-radius:11px; background:var(--grad); display:grid; place-items:center; color:#fff; box-shadow:0 8px 20px -6px rgba(225,29,42,.65); }
        .nav-right { margin-left:auto; display:flex; align-items:center; gap:10px; }
        .icon-btn { width:40px; height:40px; border-radius:11px; border:1.5px solid var(--border); background:var(--card); color:var(--text); display:grid; place-items:center; cursor:pointer; }
        .icon-btn:hover { border-color:var(--brand); }
        .theme-dark-only { display:none; } html[data-theme="dark"] .theme-dark-only { display:block; }
        .theme-light-only { display:block; } html[data-theme="dark"] .theme-light-only { display:none; }
        .alert { padding:14px 18px; border-radius:12px; margin-bottom:18px; font-size:14.5px; font-weight:500; }
        .alert-ok { background:rgba(34,197,94,.12); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
        .alert-err { background:rgba(239,68,68,.12); color:#ef4444; border:1px solid rgba(239,68,68,.3); }
        footer { border-top:1px solid var(--border); background:var(--bg-soft); padding:30px 0; margin-top:60px; text-align:center; color:var(--muted); font-size:13.5px; }
        @yield('styles')
    </style>
</head>
<body>
    <nav class="nav">
        <div class="wrap nav-in">
            <a href="/" class="brand">
                <span class="logo"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg></span>
                Event<span class="grad-text">Mooda</span>
            </a>
            <div class="nav-right">
                <button class="icon-btn" id="themeToggle" aria-label="Ganti tema">
                    <svg class="theme-dark-only" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                    <svg class="theme-light-only" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
                </button>
                @auth
                    <a href="{{ route('my-tickets.index') }}" class="btn btn-ghost btn-sm">🎫 Tiket Saya</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-grad btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <footer>© {{ date('Y') }} Event Mooda — platform tiket event Indonesia.</footer>

    <script>
        document.getElementById('themeToggle').addEventListener('click', function () {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', cur);
            localStorage.setItem('em-theme', cur);
        });
    </script>
    @stack('scripts')
</body>
</html>
