{{-- EventMooda — Premium modern event ticketing landing (SaaS + event discovery). Data dari controller. --}}
@php
    $categories = $categories ?? collect();
    $cities     = $cities ?? collect();
    $events     = $events ?? collect();
    $rupiah     = fn ($n) => $n <= 0 ? 'Gratis' : 'Rp ' . number_format($n, 0, ',', '.');
    $waUrl      = 'https://wa.me/6281265558044';
    $catIcons   = ['musik & konser'=>'🎵','festival'=>'🎪','olahraga'=>'🏆','seminar'=>'🎤','workshop'=>'🛠️','teater & seni'=>'🎭','komunitas'=>'👥','pameran'=>'🖼️'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventMooda — Platform Tiket Event #1 Indonesia</title>
    <meta name="description" content="EventMooda — temukan & beli tiket konser, festival, seminar, workshop, olahraga di seluruh Indonesia. Atau jual tiket eventmu dengan mudah.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --primary:#D90429; --secondary:#B71C1C; --bg:#fff; --section:#FFF8F6; --ink:#111; --muted:#6b6b6b;
            --border:#EEE; --radius:22px; --radius-sm:16px;
            --shadow:0 10px 40px -12px rgba(17,17,17,.12); --shadow-lg:0 24px 60px -18px rgba(217,4,41,.22);
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink);line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        h1,h2,h3,h4,.dis{font-family:'Plus Jakarta Sans','Inter',sans-serif;letter-spacing:-.02em;line-height:1.08}
        a{color:inherit;text-decoration:none}
        img{max-width:100%;display:block}
        .wrap{width:100%;max-width:100%;margin:0 auto;padding:0 clamp(18px,3.5vw,60px)}
        .grad-text{background:linear-gradient(100deg,#D90429,#ff4d5e);-webkit-background-clip:text;background-clip:text;color:transparent}
        .btn{display:inline-flex;align-items:center;gap:8px;font-family:'Inter';font-weight:600;font-size:15px;padding:13px 26px;border-radius:999px;border:1.5px solid transparent;cursor:pointer;transition:transform .25s,box-shadow .25s,background .2s;white-space:nowrap}
        .btn-primary{background:var(--primary);color:#fff;box-shadow:0 12px 28px -10px rgba(217,4,41,.6)}
        .btn-primary:hover{transform:translateY(-3px);box-shadow:0 18px 36px -10px rgba(217,4,41,.7)}
        .btn-ghost{background:#fff;color:var(--ink);border-color:var(--border)}
        .btn-ghost:hover{border-color:var(--primary);color:var(--primary);transform:translateY(-3px)}
        .btn-sm{padding:10px 20px;font-size:14px}
        .eyebrow{display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:13px;color:var(--primary);background:#fff;border:1px solid var(--border);padding:7px 15px;border-radius:999px;box-shadow:var(--shadow)}
        .sec{padding:72px 0}
        .sec.soft{background:var(--section)}
        .sec-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:34px;flex-wrap:wrap}
        .sec-head h2{font-size:clamp(26px,3.4vw,40px);font-weight:800}
        .sec-head p{color:var(--muted);margin-top:8px}
        .sec-head .more{font-weight:600;color:var(--primary);font-size:15px;white-space:nowrap}

        /* NAVBAR */
        .nav{position:sticky;top:0;z-index:60;background:rgba(255,255,255,.7);backdrop-filter:blur(0px);border-bottom:1px solid transparent;transition:.3s}
        .nav.scrolled{background:rgba(255,255,255,.82);backdrop-filter:blur(18px);border-bottom-color:var(--border);box-shadow:0 6px 24px -14px rgba(17,17,17,.15)}
        .nav-in{display:flex;align-items:center;gap:22px;height:74px}
        .brand{display:flex;align-items:center;gap:10px;font-family:'Plus Jakarta Sans';font-weight:800;font-size:22px}
        .brand .m{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#ff4d5e,#B71C1C);display:grid;place-items:center;color:#fff;box-shadow:0 8px 18px -6px rgba(217,4,41,.6)}
        .nav-links{display:flex;gap:26px;margin-left:12px}
        .nav-links a{font-weight:500;font-size:15px;color:#333;transition:.15s}
        .nav-links a:hover{color:var(--primary)}
        .nav-search{margin-left:auto;display:flex;align-items:center;gap:9px;background:var(--section);border:1px solid var(--border);border-radius:999px;padding:9px 16px;width:270px;color:var(--muted)}
        .nav-search input{border:0;background:transparent;outline:none;font-family:inherit;font-size:14px;width:100%;color:var(--ink)}
        .nav-right{display:flex;align-items:center;gap:12px}
        .hamburger{display:none;background:none;border:0;cursor:pointer}

        /* HERO */
        .hero{position:relative;padding:52px 0 30px;overflow:hidden}
        .hero::before{content:'';position:absolute;width:620px;height:620px;right:-160px;top:-200px;border-radius:50%;background:radial-gradient(circle,rgba(217,4,41,.14),transparent 62%);z-index:0}
        .hero::after{content:'';position:absolute;width:480px;height:480px;left:-180px;bottom:-160px;border-radius:50%;background:radial-gradient(circle,rgba(217,4,41,.08),transparent 60%);z-index:0}
        .hero-grid{position:relative;z-index:1;display:grid;grid-template-columns:1.05fr 1.1fr;gap:48px;align-items:center}
        .hero h1{font-size:clamp(38px,5.2vw,66px);font-weight:800;margin:22px 0 18px}
        .hero .lead{font-size:18px;color:var(--muted);max-width:500px;margin-bottom:28px}
        .hero-cta{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:38px}
        .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;max-width:560px}
        .stat b{font-family:'Plus Jakarta Sans';font-weight:800;font-size:26px;display:block}
        .stat small{color:var(--muted);font-size:12.5px;font-weight:500}
        /* featured hero card */
        .feat{position:relative;border-radius:30px;overflow:hidden;box-shadow:var(--shadow-lg);aspect-ratio:4/3.4;color:#fff;display:flex;flex-direction:column;justify-content:flex-end;padding:26px;background-size:cover;background-position:center;animation:floaty 6s ease-in-out infinite}
        @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
        .feat::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.05) 30%,rgba(0,0,0,.82))}
        .feat>*{position:relative;z-index:1}
        .feat .tag{align-self:flex-start;background:rgba(255,255,255,.16);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.25);padding:6px 13px;border-radius:999px;font-size:12px;font-weight:600;position:absolute;top:20px;left:20px}
        .feat h3{font-size:30px;font-weight:800;margin-bottom:12px}
        .feat .meta{display:flex;gap:16px;flex-wrap:wrap;font-size:13.5px;font-weight:500;opacity:.95;margin-bottom:14px}
        .feat .foot{display:flex;justify-content:space-between;align-items:center;gap:10px}
        .feat .going{font-size:12.5px;background:rgba(255,255,255,.15);padding:6px 12px;border-radius:999px}
        .feat .left{background:var(--primary);padding:7px 14px;border-radius:999px;font-size:12.5px;font-weight:600}

        /* CARD (event) */
        .hscroll{display:grid;grid-auto-flow:column;grid-auto-columns:minmax(255px,1fr);gap:20px;overflow-x:auto;padding:6px 2px 18px;scroll-snap-type:x mandatory;scrollbar-width:none}
        .hscroll::-webkit-scrollbar{display:none}
        .grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
        .ecard{background:#fff;border:1px solid var(--border);border-radius:20px;overflow:hidden;transition:transform .3s,box-shadow .3s;scroll-snap-align:start;display:flex;flex-direction:column}
        .ecard:hover{transform:translateY(-8px);box-shadow:var(--shadow-lg);border-color:transparent}
        .ecard .poster{position:relative;aspect-ratio:4/5;overflow:hidden;background-size:cover;background-position:center;display:grid;place-items:center;color:#fff;text-align:center;padding:16px}
        .ecard .poster .pt{font-family:'Plus Jakarta Sans';font-weight:800;font-size:19px;text-shadow:0 2px 12px rgba(0,0,0,.4)}
        .ecard .poster::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.28))}
        .ecard .poster img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .5s}
        .ecard:hover .poster img{transform:scale(1.07)}
        .ecard .badge{position:absolute;top:12px;left:12px;z-index:2;background:rgba(255,255,255,.92);color:var(--ink);font-size:11px;font-weight:700;padding:5px 11px;border-radius:999px}
        .ecard .live{position:absolute;top:12px;left:12px;z-index:2;background:var(--primary);color:#fff;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;display:flex;align-items:center;gap:5px}
        .ecard .live::before{content:'';width:6px;height:6px;border-radius:50%;background:#fff;animation:blink 1.2s infinite}
        @keyframes blink{50%{opacity:.3}}
        .ecard .fav{position:absolute;top:11px;right:11px;z-index:2;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.92);display:grid;place-items:center;cursor:pointer;color:var(--ink);transition:.2s}
        .ecard .fav:hover{background:var(--primary);color:#fff}
        .ebody{padding:16px;display:flex;flex-direction:column;gap:6px;flex:1}
        .ecat{font-size:11.5px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.04em}
        .etitle{font-family:'Plus Jakarta Sans';font-weight:700;font-size:17px;line-height:1.25}
        .emeta{font-size:12.5px;color:var(--muted);display:flex;gap:6px;align-items:center}
        .efoot{margin-top:auto;padding-top:12px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:10px}
        .eprice{font-family:'Plus Jakarta Sans';font-weight:800;font-size:16px}
        .erate{font-size:12px;color:var(--muted);display:flex;align-items:center;gap:4px}

        /* CATEGORIES */
        .cats{display:grid;grid-template-columns:repeat(8,1fr);gap:14px}
        .cat{background:#fff;border:1px solid var(--border);border-radius:18px;padding:20px 12px;text-align:center;transition:.28s;cursor:pointer}
        .cat:hover{transform:scale(1.05);background:linear-gradient(135deg,#D90429,#B71C1C);border-color:transparent;box-shadow:var(--shadow-lg)}
        .cat:hover .ci,.cat:hover b{color:#fff}
        .cat .ci{width:52px;height:52px;margin:0 auto 10px;border-radius:15px;background:var(--section);display:grid;place-items:center;font-size:24px;transition:.28s}
        .cat:hover .ci{background:rgba(255,255,255,.18)}
        .cat b{font-family:'Plus Jakarta Sans';font-size:13.5px;font-weight:700}

        /* CITIES */
        .cities{display:grid;grid-template-columns:repeat(6,1fr);gap:16px}
        .city{position:relative;aspect-ratio:3/3.6;border-radius:20px;overflow:hidden;display:flex;align-items:flex-end;padding:16px;color:#fff;background-size:cover;background-position:center;transition:.3s}
        .city:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg)}
        .city::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.05),rgba(0,0,0,.72))}
        .city .em{position:absolute;top:14px;right:14px;font-size:30px;z-index:1;filter:drop-shadow(0 3px 8px rgba(0,0,0,.4))}
        .city span{position:relative;z-index:1}
        .city b{font-family:'Plus Jakarta Sans';font-weight:800;font-size:18px;display:block;text-shadow:0 2px 10px rgba(0,0,0,.5)}
        .city small{font-size:12px;opacity:.92}

        /* ORGANIZERS / PARTNERS */
        .logos{display:grid;grid-template-columns:repeat(8,1fr);gap:14px}
        .logo-c{background:#fff;border:1px solid var(--border);border-radius:16px;padding:18px 10px;display:grid;place-items:center;gap:8px;transition:.25s;text-align:center}
        .logo-c:hover{transform:translateY(-5px);box-shadow:var(--shadow);border-color:var(--primary)}
        .logo-c .lc{width:44px;height:44px;border-radius:12px;background:var(--section);display:grid;place-items:center;font-size:20px}
        .logo-c small{font-size:11px;font-weight:600;color:var(--muted)}

        /* SELL CTA */
        .sell{background:linear-gradient(120deg,#D90429,#8f0318);border-radius:30px;padding:48px;color:#fff;position:relative;overflow:hidden;display:grid;grid-template-columns:1.1fr 1fr;gap:36px;align-items:center}
        .sell::before{content:'';position:absolute;width:300px;height:300px;right:-60px;top:-120px;border-radius:50%;background:rgba(255,255,255,.1)}
        .sell>*{position:relative;z-index:1}
        .sell h2{font-size:clamp(28px,3.6vw,42px);font-weight:800;margin-bottom:14px}
        .sell p{opacity:.94;margin-bottom:24px;max-width:440px}
        .sell-feats{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:26px}
        .sell-feat{display:flex;gap:10px;align-items:center;font-size:13.5px;font-weight:500;background:rgba(255,255,255,.1);padding:10px 14px;border-radius:12px}
        .mock{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:18px;backdrop-filter:blur(6px)}
        .mock .bar{height:10px;border-radius:6px;background:rgba(255,255,255,.25);margin-bottom:10px}
        .mock .row{display:flex;gap:10px;margin-bottom:12px}
        .mock .box{flex:1;height:64px;border-radius:12px;background:rgba(255,255,255,.16)}
        .mock .chart{height:120px;border-radius:12px;background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.2));display:flex;align-items:flex-end;gap:8px;padding:14px}
        .mock .chart i{flex:1;background:rgba(255,255,255,.6);border-radius:5px 5px 0 0}

        /* FEATURES BENTO */
        .bento{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
        .bx{background:#fff;border:1px solid var(--border);border-radius:20px;padding:22px;transition:.25s}
        .bx:hover{transform:translateY(-6px);box-shadow:var(--shadow);border-color:transparent}
        .bx.wide{grid-column:span 2}
        .bx .bi{width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,#D90429,#B71C1C);display:grid;place-items:center;font-size:20px;color:#fff;margin-bottom:14px}
        .bx h4{font-family:'Plus Jakarta Sans';font-size:17px;font-weight:700;margin-bottom:5px}
        .bx p{font-size:13.5px;color:var(--muted)}

        /* TIMELINE */
        .steps{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;position:relative}
        .step{background:#fff;border:1px solid var(--border);border-radius:20px;padding:24px 18px;text-align:center;transition:.25s}
        .step:hover{transform:translateY(-6px);box-shadow:var(--shadow)}
        .step .n{width:46px;height:46px;margin:0 auto 12px;border-radius:14px;background:var(--section);color:var(--primary);display:grid;place-items:center;font-family:'Plus Jakarta Sans';font-weight:800;font-size:19px}
        .step h4{font-family:'Plus Jakarta Sans';font-size:16px;font-weight:700;margin-bottom:5px}
        .step p{font-size:12.5px;color:var(--muted)}

        /* TESTIMONIALS */
        .tests{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
        .tcard{background:#fff;border:1px solid var(--border);border-radius:20px;padding:26px;transition:.25s}
        .tcard:hover{box-shadow:var(--shadow);transform:translateY(-5px)}
        .tcard .stars{color:#f5a623;margin-bottom:12px}
        .tcard p{font-size:14.5px;color:#333;margin-bottom:18px}
        .tcard .who{display:flex;align-items:center;gap:12px}
        .tcard .av{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#D90429,#B71C1C);display:grid;place-items:center;color:#fff;font-weight:700;font-family:'Plus Jakarta Sans'}
        .tcard .who b{font-family:'Plus Jakarta Sans';font-size:14px;display:block}
        .tcard .who small{font-size:12px;color:var(--muted)}

        /* FAQ */
        .faq{max-width:820px;margin:0 auto}
        .fitem{background:#fff;border:1px solid var(--border);border-radius:16px;margin-bottom:12px;overflow:hidden}
        .fq{display:flex;justify-content:space-between;align-items:center;gap:14px;padding:18px 22px;cursor:pointer;font-family:'Plus Jakarta Sans';font-weight:700;font-size:16px}
        .fq .ar{transition:.3s;color:var(--primary);font-size:20px}
        .fa{max-height:0;overflow:hidden;transition:max-height .3s ease;color:var(--muted);font-size:14.5px}
        .fitem.open .fa{max-height:200px}
        .fitem.open .ar{transform:rotate(45deg)}
        .fa div{padding:0 22px 18px}

        /* NEWSLETTER */
        .news{background:linear-gradient(120deg,#111,#2a1013);border-radius:30px;padding:52px;text-align:center;color:#fff;position:relative;overflow:hidden}
        .news::before{content:'';position:absolute;width:360px;height:360px;left:50%;top:-180px;transform:translateX(-50%);border-radius:50%;background:radial-gradient(circle,rgba(217,4,41,.4),transparent 60%)}
        .news>*{position:relative;z-index:1}
        .news h2{font-size:clamp(26px,3.4vw,38px);font-weight:800;margin-bottom:10px}
        .news p{opacity:.8;margin-bottom:26px}
        .news form{display:flex;gap:10px;max-width:480px;margin:0 auto;flex-wrap:wrap}
        .news input{flex:1 1 240px;border:0;border-radius:999px;padding:14px 20px;font-family:inherit;font-size:15px;outline:none}

        /* FOOTER */
        footer{background:#0e0e0e;color:#bbb;padding:60px 0 28px}
        .foot-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr 1fr;gap:34px;margin-bottom:44px}
        .foot-brand .brand{color:#fff}
        .foot-brand p{font-size:14px;margin:16px 0 18px;max-width:280px}
        .foot-col h5{color:#fff;font-family:'Plus Jakarta Sans';font-size:14px;font-weight:700;margin-bottom:16px}
        .foot-col a{display:block;font-size:14px;margin-bottom:10px;transition:.15s}
        .foot-col a:hover{color:var(--primary)}
        .socs{display:flex;gap:10px}
        .socs a{width:40px;height:40px;border-radius:11px;background:#1a1a1a;display:grid;place-items:center;color:#bbb;transition:.2s}
        .socs a:hover{background:var(--primary);color:#fff}
        .foot-bot{border-top:1px solid #1e1e1e;padding-top:24px;text-align:center;font-size:13px;color:#777}

        .reveal{opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease}
        .reveal.in{opacity:1;transform:none}

        @media(max-width:1080px){.cats{grid-template-columns:repeat(4,1fr)}.cities{grid-template-columns:repeat(3,1fr)}.logos{grid-template-columns:repeat(4,1fr)}.grid4{grid-template-columns:repeat(2,1fr)}.steps{grid-template-columns:repeat(2,1fr)}.tests{grid-template-columns:1fr}.bento{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:820px){.hero-grid,.sell,.foot-grid{grid-template-columns:1fr}.nav-links,.nav-search{display:none}.hamburger{display:block}.stats{grid-template-columns:repeat(2,1fr)}.foot-grid{grid-template-columns:1fr 1fr}}
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="nav" id="nav">
        <div class="wrap nav-in">
            <a href="/" class="brand"><span class="m"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg></span>Event<span class="grad-text">Mooda</span></a>
            <div class="nav-links">
                <a href="#trending">Explore</a><a href="#kategori">Categories</a><a href="#kota">Cities</a><a href="#organizer">For Organizer</a><a href="#features">Business</a><a href="#faq">About</a>
            </div>
            <div class="nav-search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3-3"/></svg>
                <input placeholder="Cari event, kota, atau kategori">
            </div>
            <div class="nav-right">
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <header class="hero">
        <div class="wrap hero-grid">
            <div>
                <span class="eyebrow reveal">🇮🇩 Platform Event Terbesar di Indonesia</span>
                <h1 class="reveal">Discover Amazing<br><span class="grad-text">Events Near You.</span></h1>
                <p class="lead reveal">Temukan konser, festival, seminar, olahraga, gaming, workshop, hingga pameran di seluruh Indonesia — pesan tiket dalam hitungan detik.</p>
                <div class="hero-cta reveal">
                    <a href="#trending" class="btn btn-primary">Explore Events →</a>
                    <a href="{{ route('register') }}" class="btn btn-ghost">Become Organizer</a>
                </div>
                <div class="stats reveal">
                    <div class="stat"><b>350K+</b><small>Tiket Terjual</small></div>
                    <div class="stat"><b>12K+</b><small>Event Aktif</small></div>
                    <div class="stat"><b>2.5K+</b><small>Organizer</small></div>
                    <div class="stat"><b>40+</b><small>Kota</small></div>
                </div>
            </div>
            <div class="reveal">
                @php $hf = $events->firstWhere('is_featured', true) ?? $events->first(); @endphp
                @if ($hf)
                    <div class="feat" style="@if ($hf->posterUrl())background-image:url('{{ $hf->posterUrl() }}')@else background-image:{{ $hf->gradient() }}@endif">
                        <span class="tag">★ Featured Event</span>
                        <h3>{{ $hf->title }}</h3>
                        <div class="meta"><span>📍 {{ $hf->city?->name }}</span><span>📅 {{ $hf->starts_at?->format('d M Y') }}</span></div>
                        <div class="foot"><span class="going">🔥 10K+ akan hadir</span><span class="left">Sisa Tiket 28%</span></div>
                    </div>
                @endif
            </div>
        </div>
    </header>

    {{-- TRENDING --}}
    <section class="sec" id="trending">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>🔥 Trending Sekarang</h2><p>Event paling banyak diburu minggu ini.</p></div><a href="{{ route('register') }}" class="more">Lihat Semua →</a></div>
            <div class="hscroll">
                @forelse ($events as $e)
                    @php $price = $e->priceFrom(); @endphp
                    <a href="{{ route('event.show', $e->slug) }}" class="ecard reveal">
                        <div class="poster" style="@if (!$e->posterUrl())background:{{ $e->gradient() }}@endif">
                            @if ($e->posterUrl())<img src="{{ $e->posterUrl() }}" alt="" loading="lazy">@endif
                            <span class="badge">{{ $e->category?->name }}</span>
                            <span class="fav">♡</span>
                            @unless ($e->posterUrl())<span class="pt">{{ $e->title }}</span>@endunless
                        </div>
                        <div class="ebody">
                            <span class="ecat">{{ $e->category?->name }}</span>
                            <span class="etitle">{{ $e->title }}</span>
                            <span class="emeta">📅 {{ $e->starts_at?->format('d M Y') }} · {{ $e->city?->name }}</span>
                            <div class="efoot"><span class="eprice">{{ $rupiah($price) }}</span><span class="erate">★ 4.8</span></div>
                        </div>
                    </a>
                @empty
                    <p style="color:var(--muted)">Belum ada event.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CATEGORIES --}}
    <section class="sec soft" id="kategori">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Jelajahi Kategori</h2><p>Temukan event sesuai minatmu.</p></div><a href="#trending" class="more">Lihat Semua →</a></div>
            <div class="cats">
                @foreach ($categories as $c)
                    <a href="#trending" class="cat reveal"><span class="ci">{{ $c->icon }}</span><b>{{ $c->name }}</b></a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURED --}}
    <section class="sec">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Event Unggulan</h2><p>Pilihan terbaik untukmu.</p></div><a href="{{ route('register') }}" class="more">Lihat Semua →</a></div>
            <div class="grid4">
                @foreach ($events->take(4) as $e)
                    @php $price = $e->priceFrom(); @endphp
                    <a href="{{ route('event.show', $e->slug) }}" class="ecard reveal">
                        <div class="poster" style="@if (!$e->posterUrl())background:{{ $e->gradient() }}@endif;aspect-ratio:4/3.6">
                            @if ($e->posterUrl())<img src="{{ $e->posterUrl() }}" alt="" loading="lazy">@endif
                            <span class="live">LIVE</span>
                            <span class="fav">♡</span>
                            @unless ($e->posterUrl())<span class="pt">{{ $e->title }}</span>@endunless
                        </div>
                        <div class="ebody">
                            <span class="ecat">{{ $e->category?->name }}</span>
                            <span class="etitle">{{ $e->title }}</span>
                            <span class="emeta">📅 {{ $e->starts_at?->format('d M Y') }} · {{ $e->city?->name }}</span>
                            <div class="efoot"><span class="eprice">{{ $rupiah($price) }}</span><span class="btn btn-primary btn-sm" style="padding:8px 16px">Beli Tiket</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CITIES --}}
    @if ($cities->isNotEmpty())
    <section class="sec soft" id="kota">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Event di Kotamu</h2><p>Jelajahi acara seru berdasarkan lokasi.</p></div></div>
            <div class="cities">
                @foreach ($cities as $city)
                    <a href="#trending" class="city reveal" style="@if ($city->landmarkUrl())background-image:url('{{ $city->landmarkUrl() }}')@else background:{{ $city->gradient() }}@endif">
                        @unless ($city->landmarkUrl())<span class="em">{{ $city->landmark_emoji }}</span>@endunless
                        <span><b>{{ $city->name }}</b><small>{{ $city->events_count ?? 0 }} event</small></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ORGANIZERS --}}
    <section class="sec">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Organizer Populer</h2><p>Dipercaya penyelenggara event ternama.</p></div></div>
            <div class="logos">
                @foreach (['Dyandra','Ismaya Live','Java Festival','Garuda','Prambanan Jazz','Color Asia','Hype Fest','Rajawali'] as $org)
                    <div class="logo-c reveal"><span class="lc">🎫</span><small>{{ $org }}</small></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SELL CTA --}}
    <section class="sec soft" id="organizer">
        <div class="wrap">
            <div class="sell reveal">
                <div>
                    <h2>Jual Tiketmu — Lebih Mudah &amp; Aman.</h2>
                    <p>Kelola event, check-in QR, pembayaran, analytics, pencairan dana, kode promo, affiliate, hingga seat map — semua dalam satu dashboard.</p>
                    <div class="sell-feats">
                        <span class="sell-feat">📷 QR Check-in</span><span class="sell-feat">💳 Pembayaran</span>
                        <span class="sell-feat">📊 Analytics</span><span class="sell-feat">💸 Pencairan Cepat</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn" style="background:#fff;color:var(--primary)">Mulai Jual Tiket</a>
                </div>
                <div class="mock">
                    <div class="bar" style="width:60%"></div>
                    <div class="row"><div class="box"></div><div class="box"></div><div class="box"></div></div>
                    <div class="chart"><i style="height:40%"></i><i style="height:70%"></i><i style="height:55%"></i><i style="height:90%"></i><i style="height:65%"></i><i style="height:80%"></i></div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES BENTO --}}
    <section class="sec" id="features">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Kenapa <span class="grad-text">EventMooda</span>?</h2><p>Semua alat yang kamu butuh untuk sukses.</p></div></div>
            <div class="bento">
                <div class="bx wide reveal"><div class="bi">📷</div><h4>QR Check-in Anti-Fraud</h4><p>Validasi tiket cepat &amp; akurat di lokasi, cegah tiket ganda dengan scanner.</p></div>
                <div class="bx reveal"><div class="bi">📊</div><h4>Analytics Dashboard</h4><p>Data lengkap untuk keputusan lebih baik.</p></div>
                <div class="bx reveal"><div class="bi">🏷️</div><h4>Promo &amp; Diskon</h4><p>Buat kode promo menarik.</p></div>
                <div class="bx reveal"><div class="bi">🤝</div><h4>Affiliate</h4><p>Jangkau lebih luas lewat komunitas.</p></div>
                <div class="bx reveal"><div class="bi">🗺️</div><h4>Seat Map</h4><p>Pilih kursi interaktif.</p></div>
                <div class="bx reveal"><div class="bi">📱</div><h4>Mobile Scanner</h4><p>Check-in dari mana saja pakai HP.</p></div>
                <div class="bx wide reveal"><div class="bi">💸</div><h4>Pencairan &amp; Penjualan Real-time</h4><p>Pantau penjualan langsung &amp; cairkan hasil kapan saja, transparan.</p></div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="sec soft">
        <div class="wrap">
            <div class="sec-head reveal" style="justify-content:center;text-align:center"><div><h2>Cara Kerja</h2><p>Lima langkah dari ide sampai cair.</p></div></div>
            <div class="steps">
                <div class="step reveal"><div class="n">1</div><h4>Buat Event</h4><p>Daftarkan eventmu dengan mudah.</p></div>
                <div class="step reveal"><div class="n">2</div><h4>Publish</h4><p>Publikasikan &amp; atur tiket.</p></div>
                <div class="step reveal"><div class="n">3</div><h4>Jual Tiket</h4><p>Bagikan &amp; mulai jual.</p></div>
                <div class="step reveal"><div class="n">4</div><h4>Scan QR</h4><p>Scan tiket saat acara.</p></div>
                <div class="step reveal"><div class="n">5</div><h4>Dapatkan Dana</h4><p>Tarik hasil kapan saja.</p></div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="sec">
        <div class="wrap">
            <div class="sec-head reveal"><div><h2>Kata Mereka</h2><p>Organizer &amp; pembeli yang puas.</p></div></div>
            <div class="tests">
                @foreach ([['Rina P.','Organizer Konser','EventMooda bikin jualan tiket jadi gampang banget, check-in QR-nya cepat & dananya cair tepat waktu.'],['Andi S.','Pembeli Tiket','Beli tiket cuma butuh 1 menit, e-ticket langsung masuk. Aman & praktis!'],['Maya L.','Event Organizer','Dashboard-nya lengkap — analytics, promo, affiliate. Naikin penjualan tiket kami 3x.']] as $t)
                    <div class="tcard reveal">
                        <div class="stars">★★★★★</div>
                        <p>"{{ $t[2] }}"</p>
                        <div class="who"><span class="av">{{ substr($t[0],0,1) }}</span><span><b>{{ $t[0] }}</b><small>{{ $t[1] }}</small></span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="sec soft" id="faq">
        <div class="wrap">
            <div class="sec-head reveal" style="justify-content:center;text-align:center"><div><h2>Pertanyaan Umum</h2></div></div>
            <div class="faq">
                @foreach ([['Bagaimana cara membeli tiket?','Pilih event, klik Beli Tiket, pilih jenis & jumlah, lalu bayar via VA/QRIS. E-ticket QR langsung tersedia di menu Tiket Saya.'],['Apakah gratis membuat event?','Ya, gratis. Kamu hanya membayar biaya layanan saat tiket terjual.'],['Bagaimana pencairan dana?','Ajukan pencairan dari dashboard organizer; dana ditransfer ke rekening/e-wallet setelah diproses admin.'],['Metode pembayaran apa saja?','Virtual Account semua bank utama, QRIS, dan e-wallet melalui gateway Tripay.']] as $i => $f)
                    <div class="fitem reveal {{ $i === 0 ? 'open' : '' }}">
                        <div class="fq" onclick="this.parentElement.classList.toggle('open')"><span>{{ $f[0] }}</span><span class="ar">+</span></div>
                        <div class="fa"><div>{{ $f[1] }}</div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="sec">
        <div class="wrap">
            <div class="news reveal">
                <h2>Jangan Ketinggalan Event Seru</h2>
                <p>Dapatkan info event terbaru & promo tiket langsung ke emailmu.</p>
                <form onsubmit="return false;" data-noloader><input placeholder="Masukkan email kamu"><button class="btn btn-primary">Subscribe</button></form>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer>
        <div class="wrap">
            <div class="foot-grid">
                <div class="foot-brand">
                    <a href="/" class="brand"><span class="m"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg></span>Event<span class="grad-text">Mooda</span></a>
                    <p>Platform tiket #1 di Indonesia. Temukan event terbaik & buat eventmu sendiri.</p>
                    <div class="socs">
                        <a href="https://www.instagram.com/moodateknologi.id" target="_blank" rel="noopener" aria-label="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.86s0 3.6-.07 4.86c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.9.07s-3.6 0-4.86-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.6 2.2 15.2 2.2 12s0-3.6.07-4.86c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.21 8.8 2.2 12 2.2Zm0 3.06A6.74 6.74 0 1 0 12 18.7a6.74 6.74 0 0 0 0-13.44Zm0 1.8a4.94 4.94 0 1 1 0 9.88 4.94 4.94 0 0 1 0-9.88Zm5.14-.66a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z"/></svg></a>
                        <a href="https://www.tiktok.com/@moodateknologi.id" target="_blank" rel="noopener" aria-label="TikTok"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82a4.28 4.28 0 0 1-1.05-2.82h-3.1v12.4a2.53 2.53 0 0 1-2.53 2.36 2.53 2.53 0 0 1-.66-4.97v-3.17a5.64 5.64 0 0 0-5.53 5.64A5.64 5.64 0 0 0 9.36 20a5.64 5.64 0 0 0 5.64-5.64V8.1a7.34 7.34 0 0 0 4.3 1.38V6.4a4.28 4.28 0 0 1-2.7-.58Z"/></svg></a>
                        <a href="https://www.facebook.com/mooda.id" target="_blank" rel="noopener" aria-label="Facebook"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg></a>
                    </div>
                </div>
                <div class="foot-col"><h5>Explore</h5><a href="#trending">Event</a><a href="#kategori">Kategori</a><a href="#organizer">For Organizer</a><a href="#features">Business</a></div>
                <div class="foot-col"><h5>Support</h5><a href="#faq">Help Center</a><a href="#faq">Cara Beli Tiket</a><a href="#organizer">Pembayaran</a><a href="{{ $waUrl }}" target="_blank" rel="noopener">Kontak</a></div>
                <div class="foot-col"><h5>Organizer</h5><a href="{{ route('register') }}">Buat Event</a><a href="#organizer">Jual Tiket</a><a href="#features">Dashboard</a></div>
                <div class="foot-col"><h5>Legal</h5><a href="#">Syarat &amp; Ketentuan</a><a href="#">Kebijakan Privasi</a><a href="#">Keamanan</a></div>
            </div>
            <div class="foot-bot">© {{ date('Y') }} EventMooda · Mooda Teknologi Indonesia. All rights reserved.</div>
        </div>
    </footer>

    <script>
        var nav = document.getElementById('nav');
        window.addEventListener('scroll', function () { nav.classList.toggle('scrolled', window.scrollY > 20); }, { passive:true });
        var io = new IntersectionObserver(function (es) { es.forEach(function (en, i) { if (en.isIntersecting) { en.target.style.transitionDelay = (Math.min(i,6)*40) + 'ms'; en.target.classList.add('in'); io.unobserve(en.target); } }); }, { threshold:.1 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    </script>
    @include('partials._button_loader')
</body>
</html>
