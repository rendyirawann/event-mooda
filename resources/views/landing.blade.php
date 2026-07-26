{{-- Event Mooda — Landing gaya NEWSPAPER (broadsheet gelap, merah-putih). Data dari controller. --}}
@php
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
    <title>Event Mooda — Kabar & Tiket Event Indonesia</title>
    <meta name="description" content="The Event Mooda — surat kabar tiket event Indonesia. Konser, festival, seminar, workshop. Beli tiket, atau jual tiket eventmu.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            var t = localStorage.getItem('em-theme');
            if (!t) t = 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <style>
        :root {
            --bg:#faf6ee; --paper:#fffdf8; --ink:#141210; --muted:#5c554c; --rule:#d8cfbf; --rule-strong:#141210; --red:#c1121f;
        }
        html[data-theme="dark"] {
            --bg:#0a0a0a; --paper:#111010; --ink:#f4f1ea; --muted:#9a938a; --rule:#2c2926; --rule-strong:#f4f1ea; --red:#ff2d3f;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { background:var(--bg); color:var(--ink); font-family:Georgia,'Times New Roman',serif; line-height:1.5; -webkit-font-smoothing:antialiased; overflow-x:hidden; }
        .serif { font-family:'Playfair Display',Georgia,serif; }
        .sans { font-family:'Inter',system-ui,sans-serif; }
        a { color:inherit; text-decoration:none; }
        img { max-width:100%; display:block; }
        .wrap { width:100%; max-width:1180px; margin:0 auto; padding:0 22px; }
        .kicker { font-family:'Inter',sans-serif; text-transform:uppercase; letter-spacing:.22em; font-size:11px; font-weight:700; color:var(--red); }
        .rule { border-top:1px solid var(--rule); }
        .rule-strong { border-top:2px solid var(--rule-strong); }
        .btn { display:inline-flex; align-items:center; gap:8px; font-family:'Inter',sans-serif; font-weight:700; font-size:13.5px; padding:11px 20px; border-radius:2px; border:1.5px solid var(--rule-strong); cursor:pointer; transition:.15s; text-transform:uppercase; letter-spacing:.04em; }
        .btn-red { background:var(--red); color:#fff; border-color:var(--red); }
        .btn-red:hover { filter:brightness(1.1); }
        .btn-ink { background:transparent; color:var(--ink); }
        .btn-ink:hover { background:var(--ink); color:var(--bg); }

        /* NAV */
        .topbar { border-bottom:1px solid var(--rule); background:var(--paper); position:sticky; top:0; z-index:50; }
        .topbar-in { display:flex; align-items:center; justify-content:space-between; height:44px; font-family:'Inter',sans-serif; font-size:12px; }
        .topbar .date { color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
        .topbar .links { display:flex; gap:18px; align-items:center; }
        .topbar .links a { color:var(--muted); font-weight:600; }
        .topbar .links a:hover { color:var(--red); }
        .icon-btn { background:none; border:0; cursor:pointer; color:var(--ink); display:grid; place-items:center; }
        .theme-dark-only{display:none} html[data-theme="dark"] .theme-dark-only{display:block}
        .theme-light-only{display:block} html[data-theme="dark"] .theme-light-only{display:none}

        /* MASTHEAD */
        .masthead { text-align:center; padding:26px 0 14px; border-bottom:3px double var(--rule-strong); }
        .masthead .edition { font-family:'Inter',sans-serif; font-size:11px; letter-spacing:.16em; text-transform:uppercase; color:var(--muted); display:flex; justify-content:space-between; border-bottom:1px solid var(--rule); padding-bottom:8px; margin-bottom:10px; }
        .masthead h1 { font-weight:900; font-size:clamp(42px,9vw,104px); line-height:.92; letter-spacing:-.02em; }
        .masthead h1 .red { color:var(--red); }
        .masthead .tag { font-style:italic; color:var(--muted); margin-top:8px; font-size:15px; border-top:1px solid var(--rule); padding-top:8px; display:inline-block; }

        /* LEAD */
        .lead { display:grid; grid-template-columns:2.2fr 1fr; gap:0; border-bottom:2px solid var(--rule-strong); }
        .lead-main { padding:34px 34px 34px 0; border-right:1px solid var(--rule); }
        .lead-main .headline { font-weight:900; font-size:clamp(30px,4.6vw,56px); line-height:1.02; letter-spacing:-.01em; margin:10px 0 14px; }
        .lead-main .standfirst { font-size:19px; color:var(--muted); max-width:640px; margin-bottom:22px; }
        .lead-main .standfirst::first-letter { }
        .search { display:flex; gap:8px; flex-wrap:wrap; max-width:600px; }
        .search input { flex:1 1 200px; border:1.5px solid var(--rule-strong); background:var(--paper); color:var(--ink); padding:11px 14px; font-family:'Inter',sans-serif; font-size:14px; border-radius:2px; }
        .search input::placeholder { color:var(--muted); }
        .lead-side { padding:34px 0 34px 30px; }
        .lead-side h4 { font-family:'Inter',sans-serif; text-transform:uppercase; letter-spacing:.14em; font-size:11px; color:var(--red); font-weight:700; border-bottom:2px solid var(--rule-strong); padding-bottom:8px; margin-bottom:6px; }
        .idx a { display:flex; justify-content:space-between; align-items:baseline; padding:10px 0; border-bottom:1px solid var(--rule); font-weight:700; font-size:17px; }
        .idx a:hover { color:var(--red); }
        .idx a .ic { font-family:'Inter',sans-serif; }
        .idx a small { font-family:'Inter',sans-serif; font-size:10.5px; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); font-weight:600; }

        /* SECTION HEAD */
        .sec { padding:34px 0; }
        .sec-head { display:flex; align-items:center; gap:14px; border-bottom:2px solid var(--rule-strong); padding-bottom:8px; margin-bottom:22px; }
        .sec-head h2 { font-weight:900; font-size:clamp(24px,3.4vw,36px); }
        .sec-head .flag { font-family:'Inter',sans-serif; background:var(--red); color:#fff; font-size:10.5px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; padding:4px 9px; }
        .sec-head .more { margin-left:auto; font-family:'Inter',sans-serif; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--red); }

        /* EVENT ARTICLES */
        .articles { display:grid; grid-template-columns:repeat(4,1fr); gap:0; }
        .art { padding:0 22px; border-right:1px solid var(--rule); }
        .art:first-child{padding-left:0} .art:last-child{border-right:0;padding-right:0}
        .art .cut { aspect-ratio:3/2; display:grid; place-items:center; color:#fff; text-align:center; padding:14px; margin-bottom:12px; position:relative; filter:saturate(.6) contrast(1.05); }
        html[data-theme="dark"] .art .cut { filter:saturate(.7) brightness(.95); }
        .art .cut .cap { font-family:'Playfair Display',serif; font-weight:800; font-size:17px; text-shadow:0 1px 8px rgba(0,0,0,.5); }
        .art .cut .flag { position:absolute; top:8px; left:8px; font-family:'Inter',sans-serif; background:#000; color:#fff; font-size:9.5px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; padding:3px 7px; }
        .art .hot { position:absolute; top:8px; right:8px; background:var(--red); color:#fff; font-family:'Inter',sans-serif; font-size:9.5px; font-weight:800; padding:3px 7px; letter-spacing:.06em; }
        .art .byline { font-family:'Inter',sans-serif; font-size:10px; text-transform:uppercase; letter-spacing:.1em; color:var(--red); font-weight:700; }
        .art h3 { font-weight:800; font-size:20px; line-height:1.12; margin:5px 0 7px; }
        .art .meta { font-family:'Inter',sans-serif; font-size:12px; color:var(--muted); border-top:1px solid var(--rule); padding-top:8px; margin-top:10px; display:flex; justify-content:space-between; align-items:center; gap:8px; }
        .art .price { font-weight:800; }
        .art .price.free { color:var(--red); }
        .art .buy { font-family:'Inter',sans-serif; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--red); white-space:nowrap; }

        /* CITIES */
        .cities { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
        .city { position:relative; aspect-ratio:3/2; display:flex; align-items:flex-end; padding:14px; color:#fff; overflow:hidden; border:1px solid var(--rule-strong); background-size:cover; background-position:center; filter:grayscale(.3) contrast(1.05); }
        .city:hover{filter:grayscale(0)}
        .city .em { position:absolute; top:10px; right:12px; font-size:34px; filter:drop-shadow(0 2px 6px rgba(0,0,0,.4)); }
        .city b { font-family:'Playfair Display',serif; font-weight:900; font-size:20px; text-shadow:0 2px 10px rgba(0,0,0,.6); }
        .city small { font-family:'Inter',sans-serif; display:block; font-size:11px; text-shadow:0 1px 6px rgba(0,0,0,.6); }

        /* EDITORIAL / ORGANIZER */
        .edi { display:grid; grid-template-columns:1fr 1fr; gap:0; border-top:2px solid var(--rule-strong); border-bottom:2px solid var(--rule-strong); }
        .edi-l { padding:34px 34px 34px 0; border-right:1px solid var(--rule); }
        .edi-l h2 { font-weight:900; font-size:clamp(26px,3.6vw,42px); line-height:1.05; margin-bottom:14px; }
        .edi-l p { color:var(--muted); font-size:17px; margin-bottom:20px; }
        .edi-l ul { list-style:none; }
        .edi-l li { padding:11px 0; border-top:1px solid var(--rule); font-family:'Inter',sans-serif; font-size:14px; display:flex; gap:10px; }
        .edi-l li b { color:var(--red); }
        .edi-r { padding:34px 0 34px 34px; display:flex; flex-direction:column; justify-content:center; }
        .edi-stats { display:flex; gap:0; border-top:2px solid var(--rule-strong); border-bottom:2px solid var(--rule-strong); }
        .edi-stats div { flex:1; text-align:center; padding:18px 8px; border-right:1px solid var(--rule); }
        .edi-stats div:last-child{border-right:0}
        .edi-stats b { font-family:'Playfair Display',serif; font-weight:900; font-size:32px; display:block; }
        .edi-stats small { font-family:'Inter',sans-serif; font-size:10.5px; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); }

        /* STEPS */
        .steps { display:grid; grid-template-columns:repeat(4,1fr); gap:0; }
        .step { padding:0 22px; border-right:1px solid var(--rule); }
        .step:first-child{padding-left:0}.step:last-child{border-right:0}
        .step .n { font-family:'Playfair Display',serif; font-weight:900; font-size:40px; color:var(--red); line-height:1; }
        .step h4 { font-weight:800; font-size:18px; margin:8px 0 6px; }
        .step p { font-family:'Inter',sans-serif; font-size:13px; color:var(--muted); }

        /* FOOTER */
        footer { border-top:3px double var(--rule-strong); margin-top:20px; padding:30px 0; text-align:center; }
        footer .fh { font-family:'Playfair Display',serif; font-weight:900; font-size:34px; }
        footer .fh .red{color:var(--red)}
        footer .fmeta { font-family:'Inter',sans-serif; font-size:12px; color:var(--muted); margin-top:8px; }
        footer .socials { display:flex; gap:14px; justify-content:center; margin:16px 0; }
        footer .socials a { color:var(--muted); }
        footer .socials a:hover{color:var(--red)}

        .reveal { opacity:0; transform:translateY(16px); transition:.6s; }
        .reveal.in { opacity:1; transform:none; }

        @media(max-width:900px){
            .lead,.edi{grid-template-columns:1fr}
            .lead-main,.edi-l{border-right:0;border-bottom:1px solid var(--rule);padding-right:0}
            .lead-side,.edi-r{padding-left:0}
            .articles,.steps{grid-template-columns:repeat(2,1fr)}
            .art,.step{border-right:0;padding:0 0 20px 0;border-bottom:1px solid var(--rule);margin-bottom:20px}
            .cities{grid-template-columns:repeat(2,1fr)}
        }
    </style>
</head>
<body>

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="wrap topbar-in">
            <span class="date">{{ now()->translatedFormat('l, d F Y') }} · Jakarta</span>
            <div class="links">
                <a href="#kanal">Kanal</a>
                <a href="#kota">Kota</a>
                <a href="#organizer">Jual Tiket</a>
                <button class="icon-btn" id="themeToggle" aria-label="Tema">
                    <svg class="theme-dark-only" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                    <svg class="theme-light-only" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
                </button>
                <a href="{{ route('login') }}" style="color:var(--ink)">Masuk</a>
                <a href="{{ route('register') }}" style="color:var(--red)">Daftar</a>
            </div>
        </div>
    </div>

    {{-- MASTHEAD --}}
    <div class="wrap">
        <header class="masthead">
            <div class="edition">
                <span>Vol. I — No. 1</span>
                <span>Edisi Harian</span>
                <span>Gratis</span>
            </div>
            <h1 class="serif">The Event<span class="red">Mooda</span></h1>
            <span class="tag serif">“Semua Tiket Event Indonesia dalam Satu Halaman”</span>
        </header>
    </div>

    {{-- LEAD --}}
    <div class="wrap">
        <section class="lead">
            <div class="lead-main">
                <span class="kicker">Berita Utama</span>
                <h1 class="headline serif reveal">Temukan &amp; Jual Tiket Event Favoritmu di Satu Tempat.</h1>
                <p class="standfirst reveal">Dari konser, festival, seminar, sampai workshop — beli tiket dengan aman, atau jual tiket eventmu sendiri hanya dalam hitungan menit.</p>
                <form class="search sans reveal" onsubmit="return false;">
                    <input type="text" placeholder="Cari event, artis, atau komunitas…">
                    <input type="text" placeholder="Kota" style="flex:0 1 160px">
                    <button class="btn btn-red" type="submit">Cari</button>
                </form>
                <div style="margin-top:14px" class="reveal">
                    <a href="{{ route('register') }}" class="btn btn-ink">Buat Event</a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-ink">Konsultasi</a>
                </div>
            </div>
            <aside class="lead-side">
                <h4>Indeks Kanal</h4>
                <div class="idx">
                    @forelse ($categories as $c)
                        <a href="#kanal"><span><span class="ic">{{ $c->icon }}</span> {{ $c->name }}</span><small>Lihat →</small></a>
                    @empty
                        <a href="#kanal"><span>Semua Kanal</span></a>
                    @endforelse
                </div>
            </aside>
        </section>
    </div>

    {{-- EVENTS --}}
    <div class="wrap">
        <section class="sec" id="kanal">
            <div class="sec-head">
                <span class="flag">Hari Ini</span>
                <h2 class="serif">Event Pilihan Redaksi</h2>
                <a href="{{ route('register') }}" class="more">Semua Event →</a>
            </div>
            <div class="articles">
                @forelse ($events as $e)
                    @php $price = $e->priceFrom(); @endphp
                    <article class="art reveal">
                        <a href="{{ route('event.show', $e->slug) }}">
                            <div class="cut" style="@if ($e->posterUrl())background-image:url('{{ $e->posterUrl() }}');background-size:cover;background-position:center;@else background:{{ $e->gradient() }};@endif">
                                <span class="flag">{{ $e->category?->name }}</span>
                                @if ($e->is_featured)<span class="hot">Terlaris</span>@endif
                                @unless ($e->posterUrl())<span class="cap">{{ $e->title }}</span>@endunless
                            </div>
                            <div class="byline">{{ strtoupper($e->starts_at?->translatedFormat('d M Y')) }} · {{ $e->city?->name }}</div>
                            <h3 class="serif">{{ $e->title }}</h3>
                        </a>
                        <div class="meta">
                            <span class="price {{ $price <= 0 ? 'free' : '' }}">{{ $rupiah($price) }}</span>
                            <a href="{{ route('event.show', $e->slug) }}" class="buy">Beli Tiket →</a>
                        </div>
                    </article>
                @empty
                    <p class="sans" style="color:var(--muted)">Belum ada event dipublikasikan.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- CITIES --}}
    @if ($cities->isNotEmpty())
    <div class="wrap">
        <section class="sec" id="kota">
            <div class="sec-head">
                <span class="flag">Daerah</span>
                <h2 class="serif">Kabar Event dari Berbagai Kota</h2>
            </div>
            <div class="cities">
                @foreach ($cities as $city)
                    <a href="#kanal" class="city reveal" style="@if ($city->landmarkUrl())background-image:linear-gradient(180deg,rgba(0,0,0,.1),rgba(0,0,0,.7)),url('{{ $city->landmarkUrl() }}');@else background:{{ $city->gradient() }};@endif">
                        @unless ($city->landmarkUrl())<span class="em">{{ $city->landmark_emoji }}</span>@endunless
                        <span><b class="serif">{{ $city->name }}</b><small>{{ $city->events_count ?? 0 }} event</small></span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- ORGANIZER --}}
    <div class="wrap">
        <section class="edi" id="organizer">
            <div class="edi-l">
                <span class="kicker">Opini · Untuk Penyelenggara</span>
                <h2 class="serif">Punya Event? Jual Tiketnya di Sini.</h2>
                <p>Buat halaman event profesional, atur berbagai jenis tiket, dan pantau penjualan real-time. Kami urus pembayaran &amp; e-ticket — Anda fokus bikin acaranya keren.</p>
                <ul>
                    <li><b>01.</b> Buat event dalam hitungan menit — poster, jadwal, lokasi, tiket.</li>
                    <li><b>02.</b> E-ticket QR otomatis, tinggal scan saat check-in.</li>
                    <li><b>03.</b> Pencairan dana cepat, transparan, dashboard real-time.</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-red">Mulai Jual Tiket</a>
            </div>
            <div class="edi-r">
                <div class="edi-stats">
                    <div><b class="serif">12K+</b><small>Tiket Terjual</small></div>
                    <div><b class="serif">320+</b><small>Event Aktif</small></div>
                    <div><b class="serif">40+</b><small>Kota</small></div>
                </div>
                <p class="sans" style="color:var(--muted);font-size:13px;margin-top:16px;text-align:center">Gratis bikin akun. Tanpa biaya di muka — bayar saat tiket terjual.</p>
            </div>
        </section>
    </div>

    {{-- STEPS --}}
    <div class="wrap">
        <section class="sec" id="cara">
            <div class="sec-head"><span class="flag">Panduan</span><h2 class="serif">Cara Kerja</h2></div>
            <div class="steps">
                <div class="step reveal"><div class="n serif">1</div><h4 class="serif">Buat Event</h4><p>Daftar, isi detail acara, unggah poster, tentukan jadwal &amp; lokasi.</p></div>
                <div class="step reveal"><div class="n serif">2</div><h4 class="serif">Atur Tiket</h4><p>Buat kategori tiket (Reguler, VIP, Early Bird) beserta harga &amp; kuota.</p></div>
                <div class="step reveal"><div class="n serif">3</div><h4 class="serif">Promosikan</h4><p>Bagikan halaman event. Pembeli bayar aman lewat berbagai metode.</p></div>
                <div class="step reveal"><div class="n serif">4</div><h4 class="serif">Check-in &amp; Cairkan</h4><p>Scan e-ticket QR saat acara, lalu cairkan hasil penjualanmu.</p></div>
            </div>
        </section>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="wrap">
            <div class="fh serif">The Event<span class="red">Mooda</span></div>
            <div class="socials">
                <a href="https://www.instagram.com/moodateknologi.id" target="_blank" rel="noopener" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.86s0 3.6-.07 4.86c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.9.07s-3.6 0-4.86-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.6 2.2 15.2 2.2 12s0-3.6.07-4.86c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.21 8.8 2.2 12 2.2Zm0 1.8c-3.15 0-3.52.01-4.76.07-.9.04-1.38.19-1.7.32-.43.16-.74.36-1.06.68-.32.32-.52.63-.68 1.06-.13.32-.28.8-.32 1.7C3.21 8.48 3.2 8.85 3.2 12s.01 3.52.07 4.76c.04.9.19 1.38.32 1.7.16.43.36.74.68 1.06.32.32.63.52 1.06.68.32.13.8.28 1.7.32 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.9-.04 1.38-.19 1.7-.32.43-.16.74-.36 1.06-.68.32-.32.52-.63.68-1.06.13-.32.28-.8.32-1.7.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.9-.19-1.38-.32-1.7a2.85 2.85 0 0 0-.68-1.06 2.85 2.85 0 0 0-1.06-.68c-.32-.13-.8-.28-1.7-.32C15.52 4.01 15.15 4 12 4Zm0 3.06A4.94 4.94 0 1 1 12 17a4.94 4.94 0 0 1 0-9.88Zm0 1.8a3.14 3.14 0 1 0 0 6.28 3.14 3.14 0 0 0 0-6.28Zm5.14-.66a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z"/></svg></a>
                <a href="https://www.tiktok.com/@moodateknologi.id" target="_blank" rel="noopener" aria-label="TikTok"><svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82a4.28 4.28 0 0 1-1.05-2.82h-3.1v12.4a2.53 2.53 0 0 1-2.53 2.36 2.53 2.53 0 0 1-.66-4.97v-3.17a5.64 5.64 0 0 0-5.53 5.64A5.64 5.64 0 0 0 9.36 20a5.64 5.64 0 0 0 5.64-5.64V8.1a7.34 7.34 0 0 0 4.3 1.38V6.4a4.28 4.28 0 0 1-2.7-.58Z"/></svg></a>
                <a href="https://www.facebook.com/mooda.id" target="_blank" rel="noopener" aria-label="Facebook"><svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg></a>
            </div>
            <div class="fmeta">© {{ date('Y') }} Event Mooda · Mooda Teknologi Indonesia — Platform tiket event Indonesia.</div>
        </div>
    </footer>

    <script>
        document.getElementById('themeToggle').addEventListener('click', function () {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', cur);
            localStorage.setItem('em-theme', cur);
        });
        var io = new IntersectionObserver(function (es) { es.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } }); }, { threshold:.12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    </script>
</body>
</html>
