{{-- Event Mooda — Landing platform tiket event (full-width, tema gelap/terang) --}}
@php
    // Data nyata dari controller: $categories (EventCategory), $cities (City featured), $events (Event published).
    $categories = $categories ?? collect();
    $cities     = $cities ?? collect();
    $events     = $events ?? collect();
    $rupiah     = fn ($n) => $n <= 0 ? 'GRATIS' : 'Rp ' . number_format($n, 0, ',', '.');
    $waUrl      = 'https://wa.me/6281265558044';
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Mooda — Beli & Jual Tiket Event Favoritmu</title>
    <meta name="description" content="Event Mooda: platform tiket event Indonesia. Temukan konser, festival, seminar, dan workshop favoritmu — atau jual tiket eventmu dengan mudah.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        // Set tema sebelum render (hindari flash)
        (function () {
            var t = localStorage.getItem('em-theme');
            if (!t) t = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <style>
        :root {
            --bg: #ffffff; --bg-soft: #f5f6fb; --card: #ffffff; --card-2: #fbfbfe;
            --text: #0d0f1c; --muted: #5a6079; --border: #e7e9f4; --border-2: #eef0f8;
            --brand: #7c3aed; --brand-2: #ec4899; --accent: #f97316;
            --grad: linear-gradient(120deg, #7c3aed 0%, #ec4899 55%, #f97316 100%);
            --shadow: 0 18px 50px -22px rgba(24, 15, 55, .28);
            --nav-bg: rgba(255,255,255,.82);
        }
        html[data-theme="dark"] {
            --bg: #08090f; --bg-soft: #0c0e18; --card: #12141f; --card-2: #171a28;
            --text: #f3f4fb; --muted: #9aa0bb; --border: #23263a; --border-2: #1b1e2e;
            --shadow: 0 22px 60px -20px rgba(0,0,0,.7);
            --nav-bg: rgba(8,9,15,.75);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg); color: var(--text);
            line-height: 1.6; -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        h1,h2,h3,.display { font-family: 'Sora', sans-serif; letter-spacing: -.02em; line-height: 1.08; }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        .wrap { width: 100%; max-width: 1240px; margin: 0 auto; padding: 0 24px; }
        .grad-text { background: var(--grad); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; border: 0; cursor: pointer;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 15px;
            padding: 13px 24px; border-radius: 14px; transition: transform .15s, box-shadow .2s, background .2s;
            white-space: nowrap;
        }
        .btn-grad { background: var(--grad); color: #fff; box-shadow: 0 10px 30px -10px rgba(124,58,237,.6); }
        .btn-grad:hover { transform: translateY(-2px); box-shadow: 0 16px 36px -10px rgba(236,72,153,.6); }
        .btn-ghost { background: transparent; color: var(--text); border: 1.5px solid var(--border); }
        .btn-ghost:hover { border-color: var(--brand); color: var(--brand); }
        .btn-sm { padding: 10px 18px; font-size: 14px; border-radius: 11px; }

        /* NAV */
        .nav {
            position: sticky; top: 0; z-index: 50; width: 100%;
            background: var(--nav-bg); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-in { display: flex; align-items: center; gap: 20px; height: 72px; }
        .brand { display: flex; align-items: center; gap: 11px; font-family: 'Sora'; font-weight: 800; font-size: 21px; }
        .brand .logo {
            width: 40px; height: 40px; border-radius: 12px; background: var(--grad);
            display: grid; place-items: center; color: #fff; flex: 0 0 auto;
            box-shadow: 0 8px 20px -6px rgba(124,58,237,.65);
        }
        .nav-links { display: flex; align-items: center; gap: 30px; margin-left: 18px; }
        .nav-links a { font-weight: 600; font-size: 15px; color: var(--muted); transition: color .15s; }
        .nav-links a:hover { color: var(--text); }
        .nav-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .icon-btn {
            width: 42px; height: 42px; border-radius: 12px; border: 1.5px solid var(--border);
            background: var(--card); color: var(--text); display: grid; place-items: center; cursor: pointer;
            transition: border-color .15s, transform .15s;
        }
        .icon-btn:hover { border-color: var(--brand); transform: translateY(-1px); }
        .theme-dark-only { display: none; } html[data-theme="dark"] .theme-dark-only { display: block; }
        .theme-light-only { display: block; } html[data-theme="dark"] .theme-light-only { display: none; }
        .hamburger { display: none; }

        /* HERO */
        .hero { position: relative; padding: 84px 0 72px; overflow: hidden; }
        .hero::before {
            content: ''; position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(760px 420px at 82% -8%, rgba(236,72,153,.28), transparent 60%),
                radial-gradient(680px 460px at 6% 12%, rgba(124,58,237,.30), transparent 58%),
                radial-gradient(600px 400px at 60% 108%, rgba(249,115,22,.20), transparent 60%);
        }
        .hero-in { position: relative; z-index: 1; text-align: center; }
        .pill {
            display: inline-flex; align-items: center; gap: 9px; padding: 8px 16px; border-radius: 999px;
            background: var(--card); border: 1px solid var(--border); font-size: 13.5px; font-weight: 600;
            color: var(--muted); margin-bottom: 26px; box-shadow: var(--shadow);
        }
        .pill .dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 4px rgba(34,197,94,.18); }
        .hero h1 { font-size: clamp(38px, 6.4vw, 78px); font-weight: 800; margin-bottom: 22px; }
        .hero p.lead { font-size: clamp(16px, 2vw, 20px); color: var(--muted); max-width: 640px; margin: 0 auto 34px; }
        /* Search bar */
        .search {
            display: flex; align-items: center; gap: 8px; max-width: 720px; margin: 0 auto 20px;
            background: var(--card); border: 1px solid var(--border); border-radius: 18px; padding: 9px;
            box-shadow: var(--shadow); flex-wrap: wrap;
        }
        .search .field { display: flex; align-items: center; gap: 10px; flex: 1 1 200px; padding: 6px 14px; }
        .search .field + .field { border-left: 1px solid var(--border); }
        .search input {
            border: 0; background: transparent; color: var(--text); font-size: 15px; width: 100%;
            font-family: inherit; outline: none;
        }
        .search input::placeholder { color: var(--muted); }
        .search .btn { flex: 0 0 auto; }
        .hero-tags { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 8px; }
        .hero-tags span { font-size: 13px; color: var(--muted); }
        .hero-tags a { font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 999px; background: var(--card); border: 1px solid var(--border); color: var(--muted); transition: .15s; }
        .hero-tags a:hover { color: var(--brand); border-color: var(--brand); }

        /* SECTION */
        section { position: relative; }
        .sec { padding: 80px 0; }
        .sec-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 40px; flex-wrap: wrap; }
        .sec-head .eyebrow { font-family: 'Sora'; font-weight: 700; font-size: 13px; letter-spacing: .14em; text-transform: uppercase; color: var(--brand); margin-bottom: 10px; }
        .sec-head h2 { font-size: clamp(28px, 4vw, 44px); font-weight: 800; }
        .sec-head p { color: var(--muted); margin-top: 10px; max-width: 520px; }
        .link-more { font-weight: 700; color: var(--brand); font-family: 'Sora'; font-size: 15px; }

        /* CATEGORIES */
        .cats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .cat {
            background: var(--card); border: 1px solid var(--border); border-radius: 18px; padding: 22px 20px;
            display: flex; align-items: center; gap: 14px; transition: transform .18s, border-color .18s, box-shadow .18s;
        }
        .cat:hover { transform: translateY(-4px); border-color: transparent; box-shadow: var(--shadow); }
        .cat .ic { width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center; font-size: 24px; flex: 0 0 auto; }
        .cat b { font-family: 'Sora'; font-size: 16px; font-weight: 700; }
        .cat small { color: var(--muted); font-size: 12.5px; }

        /* CITIES */
        .cities { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .city { position: relative; aspect-ratio: 3/2.2; border-radius: 20px; overflow: hidden; display: flex; align-items: flex-end; padding: 18px; color: #fff; background-size: cover; background-position: center; border: 1px solid var(--border); transition: transform .18s, box-shadow .18s; }
        .city:hover { transform: translateY(-5px); box-shadow: var(--shadow); }
        .city-emoji { position: absolute; top: 14px; right: 16px; font-size: 40px; line-height: 1; filter: drop-shadow(0 4px 10px rgba(0,0,0,.35)); }
        .city-meta { position: relative; z-index: 1; }
        .city-meta b { font-family: 'Sora'; font-weight: 800; font-size: 20px; display: block; text-shadow: 0 2px 12px rgba(0,0,0,.55); }
        .city-meta small { font-weight: 600; opacity: .95; text-shadow: 0 1px 8px rgba(0,0,0,.55); }
        @media (max-width: 980px) { .cities { grid-template-columns: repeat(2, 1fr); } }

        /* EVENT CARDS */
        .grid-ev { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
        .ev {
            background: var(--card); border: 1px solid var(--border); border-radius: 20px; overflow: hidden;
            transition: transform .18s, box-shadow .18s, border-color .18s; display: flex; flex-direction: column;
        }
        .ev:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: transparent; }
        .ev-poster { position: relative; aspect-ratio: 3/2.05; display: grid; place-items: center; color: #fff; padding: 18px; text-align: center; }
        .ev-poster .pt { font-family: 'Sora'; font-weight: 800; font-size: 20px; text-shadow: 0 2px 14px rgba(0,0,0,.28); }
        .ev-poster::after { content: ''; position: absolute; inset: 0; background: radial-gradient(120% 90% at 50% 0%, rgba(255,255,255,.18), transparent 60%); }
        .ev-badge { position: absolute; top: 12px; left: 12px; z-index: 2; background: rgba(0,0,0,.45); backdrop-filter: blur(4px); color: #fff; font-size: 11.5px; font-weight: 700; padding: 5px 11px; border-radius: 999px; }
        .ev-hot { position: absolute; top: 12px; right: 12px; z-index: 2; background: #fff; color: #ef4444; font-size: 11px; font-weight: 800; padding: 5px 10px; border-radius: 999px; font-family: 'Sora'; }
        .ev-body { padding: 18px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
        .ev-date { font-family: 'Sora'; font-weight: 700; font-size: 12.5px; color: var(--brand); letter-spacing: .04em; }
        .ev-title { font-family: 'Sora'; font-weight: 700; font-size: 17px; line-height: 1.25; }
        .ev-meta { display: flex; align-items: center; gap: 7px; color: var(--muted); font-size: 13.5px; }
        .ev-foot { margin-top: auto; padding-top: 14px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .ev-price small { color: var(--muted); font-size: 11px; display: block; }
        .ev-price b { font-family: 'Sora'; font-size: 16px; }

        /* ORGANIZER / WHY */
        .why { background: var(--bg-soft); }
        .why-grid { display: grid; grid-template-columns: 1.05fr 1fr; gap: 56px; align-items: center; }
        .feat-list { display: grid; gap: 18px; }
        .feat { display: flex; gap: 16px; }
        .feat .fi { width: 50px; height: 50px; border-radius: 14px; background: var(--card); border: 1px solid var(--border); display: grid; place-items: center; font-size: 22px; flex: 0 0 auto; }
        .feat h4 { font-family: 'Sora'; font-size: 17px; font-weight: 700; margin-bottom: 3px; }
        .feat p { color: var(--muted); font-size: 14.5px; }
        .why-card {
            background: var(--grad); border-radius: 26px; padding: 44px 38px; color: #fff; position: relative; overflow: hidden;
            box-shadow: 0 30px 70px -30px rgba(124,58,237,.7);
        }
        .why-card::before { content: ''; position: absolute; width: 260px; height: 260px; right: -60px; top: -60px; border-radius: 50%; background: rgba(255,255,255,.14); }
        .why-card h3 { font-size: 30px; font-weight: 800; margin-bottom: 14px; position: relative; }
        .why-card p { opacity: .92; margin-bottom: 26px; position: relative; }
        .why-card .btn { background: #fff; color: var(--brand); position: relative; }
        .why-stats { display: flex; gap: 26px; margin-top: 30px; position: relative; flex-wrap: wrap; }
        .why-stats b { font-family: 'Sora'; font-size: 28px; font-weight: 800; display: block; }
        .why-stats small { opacity: .85; font-size: 13px; }

        /* STEPS */
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
        .step { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 28px 24px; position: relative; }
        .step .n { font-family: 'Sora'; font-weight: 800; font-size: 15px; width: 38px; height: 38px; border-radius: 11px; background: var(--grad); color: #fff; display: grid; place-items: center; margin-bottom: 18px; }
        .step h4 { font-family: 'Sora'; font-size: 17.5px; font-weight: 700; margin-bottom: 8px; }
        .step p { color: var(--muted); font-size: 14px; }

        /* CTA band */
        .cta { text-align: center; }
        .cta-box { background: var(--grad); border-radius: 30px; padding: 66px 32px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 30px 70px -30px rgba(236,72,153,.6); }
        .cta-box::before, .cta-box::after { content:''; position:absolute; border-radius:50%; background:rgba(255,255,255,.12); }
        .cta-box::before { width: 300px; height: 300px; left: -80px; bottom: -120px; }
        .cta-box::after { width: 220px; height: 220px; right: -60px; top: -90px; }
        .cta-box h2 { font-size: clamp(28px, 4.4vw, 48px); font-weight: 800; margin-bottom: 16px; position: relative; }
        .cta-box p { opacity: .92; max-width: 560px; margin: 0 auto 30px; position: relative; }
        .cta-box .btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; }
        .cta-box .btn-white { background: #fff; color: var(--brand); }
        .cta-box .btn-outline { background: rgba(255,255,255,.12); color: #fff; border: 1.5px solid rgba(255,255,255,.5); }

        /* FOOTER */
        footer { background: var(--bg-soft); border-top: 1px solid var(--border); padding: 66px 0 30px; }
        .foot-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 46px; }
        .foot-brand p { color: var(--muted); font-size: 14.5px; margin: 16px 0 20px; max-width: 300px; }
        .foot-col h5 { font-family: 'Sora'; font-size: 14px; font-weight: 700; margin-bottom: 16px; text-transform: uppercase; letter-spacing: .06em; }
        .foot-col a { display: block; color: var(--muted); font-size: 14.5px; margin-bottom: 11px; transition: color .15s; }
        .foot-col a:hover { color: var(--brand); }
        .socials { display: flex; gap: 10px; }
        .socials a { width: 40px; height: 40px; border-radius: 11px; border: 1px solid var(--border); background: var(--card); display: grid; place-items: center; color: var(--text); transition: .15s; }
        .socials a:hover { background: var(--grad); color: #fff; border-color: transparent; transform: translateY(-2px); }
        .foot-bottom { border-top: 1px solid var(--border); padding-top: 26px; display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; color: var(--muted); font-size: 13.5px; }

        /* Reveal on scroll */
        .reveal { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: none; }

        /* Mobile */
        @media (max-width: 980px) {
            .cats, .grid-ev, .steps { grid-template-columns: repeat(2, 1fr); }
            .why-grid { grid-template-columns: 1fr; gap: 34px; }
            .foot-grid { grid-template-columns: 1fr 1fr; gap: 30px; }
        }
        @media (max-width: 640px) {
            .nav-links { display: none; }
            .hamburger { display: grid; }
            .nav-cta-desktop { display: none; }
            .cats, .grid-ev, .steps, .foot-grid { grid-template-columns: 1fr; }
            .sec { padding: 56px 0; }
            .search .field + .field { border-left: 0; border-top: 1px solid var(--border); }
            .mobile-menu.open { display: flex; }
        }
        .mobile-menu { display: none; flex-direction: column; gap: 4px; padding: 12px 24px 20px; border-bottom: 1px solid var(--border); background: var(--bg); }
        .mobile-menu a { padding: 12px 8px; font-weight: 600; color: var(--text); border-radius: 10px; }
        .mobile-menu a:hover { background: var(--bg-soft); }
    </style>
</head>
<body>

    {{-- ============ NAV ============ --}}
    <nav class="nav">
        <div class="wrap nav-in">
            <a href="/" class="brand">
                <span class="logo">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                </span>
                Event<span class="grad-text">Mooda</span>
            </a>
            <div class="nav-links">
                <a href="#trending">Jelajahi Event</a>
                <a href="#kategori">Kategori</a>
                <a href="#kota">Kota</a>
                <a href="#organizer">Buat Event</a>
                <a href="#cara">Cara Kerja</a>
            </div>
            <div class="nav-right">
                <button class="icon-btn" id="themeToggle" aria-label="Ganti tema" title="Ganti tema">
                    <svg class="theme-dark-only" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                    <svg class="theme-light-only" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
                </button>
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm nav-cta-desktop">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-grad btn-sm">Daftar Gratis</a>
                <button class="icon-btn hamburger" id="hamburger" aria-label="Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="#trending">Jelajahi Event</a>
            <a href="#kategori">Kategori</a>
            <a href="#kota">Kota</a>
            <a href="#organizer">Buat Event</a>
            <a href="#cara">Cara Kerja</a>
            <a href="{{ route('login') }}">Masuk</a>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <header class="hero">
        <div class="wrap hero-in">
            <span class="pill reveal"><span class="dot"></span> Platform Tiket Event #1 untuk Kreator Indonesia</span>
            <h1 class="reveal">Temukan & Jual <span class="grad-text">Tiket Event</span><br>Favoritmu di Satu Tempat</h1>
            <p class="lead reveal">Dari konser, festival, seminar, sampai workshop — beli tiket dengan aman, atau buat & jual tiket eventmu sendiri hanya dalam hitungan menit.</p>

            <form class="search reveal" onsubmit="return false;">
                <div class="field">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3-3"/></svg>
                    <input type="text" placeholder="Cari event, artis, atau komunitas...">
                </div>
                <div class="field">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <input type="text" placeholder="Kota">
                </div>
                <button class="btn btn-grad" type="submit">Cari Tiket</button>
            </form>

            <div class="hero-tags reveal">
                <span>Populer:</span>
                <a href="#trending">Konser</a>
                <a href="#trending">Festival Musik</a>
                <a href="#trending">Seminar</a>
                <a href="#trending">Workshop</a>
            </div>
        </div>
    </header>

    {{-- ============ KATEGORI ============ --}}
    <section class="sec" id="kategori">
        <div class="wrap">
            <div class="sec-head reveal">
                <div>
                    <div class="eyebrow">Jelajahi</div>
                    <h2>Kategori Event</h2>
                    <p>Temukan event sesuai minatmu — dari panggung musik sampai ruang belajar.</p>
                </div>
                <a href="#trending" class="link-more">Lihat semua →</a>
            </div>
            <div class="cats">
                @foreach ($categories as $c)
                    <a href="#trending" class="cat reveal">
                        <span class="ic" style="background:linear-gradient(135deg,{{ $c->color }});">{{ $c->icon }}</span>
                        <span>
                            <b>{{ $c->name }}</b><br>
                            <small>Lihat event</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ KOTA ============ --}}
    @if ($cities->isNotEmpty())
    <section class="sec" id="kota">
        <div class="wrap">
            <div class="sec-head reveal">
                <div>
                    <div class="eyebrow">Berdasarkan Lokasi</div>
                    <h2>Jelajahi Event per Kota</h2>
                    <p>Temukan acara seru di kotamu — klik kota untuk lihat event yang ada di sana.</p>
                </div>
            </div>
            <div class="cities">
                @foreach ($cities as $city)
                    <a href="#trending" class="city reveal" style="@if ($city->landmarkUrl())background-image:linear-gradient(180deg,rgba(0,0,0,.12),rgba(0,0,0,.68)),url('{{ $city->landmarkUrl() }}');@else background:{{ $city->gradient() }};@endif">
                        @unless ($city->landmarkUrl())<span class="city-emoji">{{ $city->landmark_emoji }}</span>@endunless
                        <span class="city-meta">
                            <b>{{ $city->name }}</b>
                            <small>{{ $city->events_count ?? 0 }} event</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ TRENDING EVENTS ============ --}}
    <section class="sec" id="trending" style="background:var(--bg-soft);">
        <div class="wrap">
            <div class="sec-head reveal">
                <div>
                    <div class="eyebrow">🔥 Sedang Ramai</div>
                    <h2>Event Trending</h2>
                    <p>Tiket yang paling banyak diburu minggu ini.</p>
                </div>
                <a href="{{ route('register') }}" class="link-more">Lihat semua event →</a>
            </div>
            <div class="grid-ev">
                @forelse ($events as $e)
                    @php $price = $e->priceFrom(); @endphp
                    <article class="ev reveal">
                        <div class="ev-poster" style="@if ($e->posterUrl())background-image:url('{{ $e->posterUrl() }}');background-size:cover;background-position:center;@else background:{{ $e->gradient() }};@endif">
                            <span class="ev-badge">{{ $e->category?->name }}</span>
                            @if ($e->is_featured)<span class="ev-hot">HOT 🔥</span>@endif
                            @unless ($e->posterUrl())<span class="pt">{{ $e->title }}</span>@endunless
                        </div>
                        <div class="ev-body">
                            <span class="ev-date">📅 {{ strtoupper($e->starts_at?->format('d M Y')) }}</span>
                            <h3 class="ev-title">{{ $e->title }}</h3>
                            <span class="ev-meta">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $e->venue_name }}, {{ $e->city?->name }}
                            </span>
                            <div class="ev-foot">
                                <div class="ev-price">
                                    <small>Mulai dari</small>
                                    <b class="{{ $price <= 0 ? 'grad-text' : '' }}">{{ $rupiah($price) }}</b>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-grad btn-sm">Beli Tiket</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:48px 0;">Belum ada event dipublikasikan. Jadilah penyelenggara pertama!</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ ORGANIZER / KENAPA ============ --}}
    <section class="sec why" id="organizer">
        <div class="wrap why-grid">
            <div class="reveal">
                <div class="eyebrow" style="color:var(--brand);font-family:'Sora';font-weight:700;font-size:13px;letter-spacing:.14em;text-transform:uppercase;margin-bottom:10px;">Untuk Penyelenggara & Ambasador</div>
                <h2 style="font-size:clamp(28px,4vw,44px);font-weight:800;margin-bottom:16px;">Punya Event? <span class="grad-text">Jual Tiketnya di Sini.</span></h2>
                <p style="color:var(--muted);margin-bottom:30px;max-width:520px;">Buat halaman event profesional, atur berbagai jenis tiket, dan pantau penjualan real-time. Event Mooda urus pembayaran & e-ticket — kamu fokus bikin acaranya keren.</p>
                <div class="feat-list">
                    <div class="feat"><span class="fi">⚡</span><div><h4>Buat Event dalam Menit</h4><p>Form sederhana: poster, jadwal, lokasi, dan tiket. Langsung publish & bagikan.</p></div></div>
                    <div class="feat"><span class="fi">🎟️</span><div><h4>E-Ticket QR Otomatis</h4><p>Setiap pembeli dapat tiket QR yang bisa di-scan saat check-in di lokasi.</p></div></div>
                    <div class="feat"><span class="fi">💸</span><div><h4>Pencairan Dana Cepat</h4><p>Pantau pemasukan dan cairkan hasil penjualan tiket dengan aman & transparan.</p></div></div>
                    <div class="feat"><span class="fi">📊</span><div><h4>Dashboard Real-Time</h4><p>Lihat jumlah tiket terjual, pengunjung, dan pendapatan kapan saja.</p></div></div>
                </div>
            </div>
            <div class="why-card reveal">
                <h3>Mulai jual tiket hari ini</h3>
                <p>Gratis bikin akun. Tanpa biaya di muka — kamu hanya bayar saat tiket terjual.</p>
                <a href="{{ route('register') }}" class="btn">Buat Event Sekarang →</a>
                <div class="why-stats">
                    <div><b>12K+</b><small>Tiket terjual</small></div>
                    <div><b>320+</b><small>Event aktif</small></div>
                    <div><b>40+</b><small>Kota</small></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section class="sec" id="cara">
        <div class="wrap">
            <div class="sec-head reveal" style="justify-content:center;text-align:center;flex-direction:column;align-items:center;">
                <div class="eyebrow">Mudah & Cepat</div>
                <h2>Cara Kerja Event Mooda</h2>
                <p>Empat langkah dari ide acara sampai tiket terjual.</p>
            </div>
            <div class="steps">
                <div class="step reveal"><div class="n">1</div><h4>Buat Event</h4><p>Daftar, isi detail acara, unggah poster, dan tentukan jadwal serta lokasi.</p></div>
                <div class="step reveal"><div class="n">2</div><h4>Atur Tiket</h4><p>Buat kategori tiket (Reguler, VIP, Early Bird) beserta harga dan kuotanya.</p></div>
                <div class="step reveal"><div class="n">3</div><h4>Promosikan</h4><p>Bagikan halaman event ke media sosial. Pembeli bayar aman lewat berbagai metode.</p></div>
                <div class="step reveal"><div class="n">4</div><h4>Check-in & Cairkan</h4><p>Scan e-ticket QR saat acara, lalu cairkan hasil penjualanmu.</p></div>
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="sec cta">
        <div class="wrap">
            <div class="cta-box reveal">
                <h2>Siap Bikin Event-mu Sukses?</h2>
                <p>Bergabung dengan ratusan penyelenggara & ambasador yang menjual tiket lewat Event Mooda. Mulai gratis hari ini.</p>
                <div class="btns">
                    <a href="{{ route('register') }}" class="btn btn-white">Mulai Buat Event</a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-outline">Konsultasi via WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer>
        <div class="wrap">
            <div class="foot-grid">
                <div class="foot-brand">
                    <a href="/" class="brand" style="font-size:20px;">
                        <span class="logo" style="width:36px;height:36px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                        </span>
                        Event<span class="grad-text">Mooda</span>
                    </a>
                    <p>Platform tiket event Indonesia — tempat menemukan acara seru & menjual tiket eventmu dengan mudah, aman, dan transparan.</p>
                    <div class="socials">
                        <a href="https://www.instagram.com/moodateknologi.id" target="_blank" rel="noopener" aria-label="Instagram"><svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.86s0 3.6-.07 4.86c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.9.07s-3.6 0-4.86-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.6 2.2 15.2 2.2 12s0-3.6.07-4.86c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.21 8.8 2.2 12 2.2Zm0 1.8c-3.15 0-3.52.01-4.76.07-.9.04-1.38.19-1.7.32-.43.16-.74.36-1.06.68-.32.32-.52.63-.68 1.06-.13.32-.28.8-.32 1.7C3.21 8.48 3.2 8.85 3.2 12s.01 3.52.07 4.76c.04.9.19 1.38.32 1.7.16.43.36.74.68 1.06.32.32.63.52 1.06.68.32.13.8.28 1.7.32 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.9-.04 1.38-.19 1.7-.32.43-.16.74-.36 1.06-.68.32-.32.52-.63.68-1.06.13-.32.28-.8.32-1.7.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.9-.19-1.38-.32-1.7a2.85 2.85 0 0 0-.68-1.06 2.85 2.85 0 0 0-1.06-.68c-.32-.13-.8-.28-1.7-.32C15.52 4.01 15.15 4 12 4Zm0 3.06A4.94 4.94 0 1 1 12 17a4.94 4.94 0 0 1 0-9.88Zm0 1.8a3.14 3.14 0 1 0 0 6.28 3.14 3.14 0 0 0 0-6.28Zm5.14-.66a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z"/></svg></a>
                        <a href="https://www.tiktok.com/@moodateknologi.id" target="_blank" rel="noopener" aria-label="TikTok"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82a4.28 4.28 0 0 1-1.05-2.82h-3.1v12.4a2.53 2.53 0 0 1-2.53 2.36 2.53 2.53 0 0 1-.66-4.97v-3.17a5.64 5.64 0 0 0-5.53 5.64A5.64 5.64 0 0 0 9.36 20a5.64 5.64 0 0 0 5.64-5.64V8.1a7.34 7.34 0 0 0 4.3 1.38V6.4a4.28 4.28 0 0 1-2.7-.58Z"/></svg></a>
                        <a href="https://www.facebook.com/mooda.id" target="_blank" rel="noopener" aria-label="Facebook"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg></a>
                    </div>
                </div>
                <div class="foot-col">
                    <h5>Jelajahi</h5>
                    <a href="#trending">Event Trending</a>
                    <a href="#kategori">Kategori</a>
                    <a href="#trending">Konser & Musik</a>
                    <a href="#trending">Seminar & Workshop</a>
                </div>
                <div class="foot-col">
                    <h5>Untuk Organizer</h5>
                    <a href="{{ route('register') }}">Buat Event</a>
                    <a href="#organizer">Jual Tiket</a>
                    <a href="#cara">Cara Kerja</a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener">Konsultasi</a>
                </div>
                <div class="foot-col">
                    <h5>Perusahaan</h5>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Bantuan</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </div>
            <div class="foot-bottom">
                <span>© {{ date('Y') }} Event Mooda. Seluruh hak cipta dilindungi.</span>
                <span>Dibuat dengan ❤️ untuk kreator & penyelenggara event Indonesia.</span>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle
        document.getElementById('themeToggle').addEventListener('click', function () {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', cur);
            localStorage.setItem('em-theme', cur);
        });
        // Mobile menu
        var mm = document.getElementById('mobileMenu');
        document.getElementById('hamburger').addEventListener('click', function () { mm.classList.toggle('open'); });
        mm.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { mm.classList.remove('open'); }); });
        // Reveal on scroll
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
        }, { threshold: .12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    </script>
</body>
</html>
