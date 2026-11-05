<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Thoughtful travel essentials for a more organized journey.">
    <title>@yield('title', 'ROAMFORM — Travel Better. Live Organized.')</title>
    <style>
        :root{--ink:#17212b;--muted:#697581;--paper:#f7f6f2;--line:#e7e5df;--accent:#b58b62}
        *{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font-family:Inter,Arial,sans-serif}
        a{color:inherit;text-decoration:none}.wrap{width:min(1160px,calc(100% - 40px));margin:auto}
        .top{border-bottom:1px solid var(--line);background:#fbfaf7}.nav{height:76px;display:flex;align-items:center;justify-content:space-between}
        .logo{font-size:22px;font-weight:800;letter-spacing:.18em}.navlinks{display:flex;gap:28px;font-size:13px}
        .hero{padding:92px 0 84px;background:linear-gradient(110deg,#e9e5dc,#f7f6f2 65%)}.eyebrow{font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--accent);font-weight:700}
        h1{font-size:clamp(42px,7vw,76px);line-height:.98;letter-spacing:-.055em;max-width:760px;margin:18px 0}
        .lead{max-width:530px;color:var(--muted);font-size:17px;line-height:1.7}.button{display:inline-block;margin-top:22px;padding:15px 24px;background:var(--ink);color:white;font-size:12px;letter-spacing:.08em;text-transform:uppercase}
        .section{padding:64px 0}.sectionhead{display:flex;justify-content:space-between;align-items:end;margin-bottom:26px}.section h2{font-size:30px;letter-spacing:-.04em;margin:8px 0}
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}.product{background:#fff;border:1px solid var(--line)}.product img{width:100%;aspect-ratio:1/1;object-fit:cover;background:#eeece6}.productinfo{padding:16px}.productinfo h3{font-size:14px;margin:0 0 8px}.price{font-weight:700;font-size:14px}
        .empty{padding:35px;background:white;border:1px solid var(--line);color:var(--muted);grid-column:1/-1}
        footer{padding:34px 0;border-top:1px solid var(--line);color:var(--muted);font-size:12px}
        @media(max-width:760px){.navlinks{gap:13px}.grid{grid-template-columns:repeat(2,1fr)}.hero{padding:65px 0}.sectionhead{display:block}}
        @media(max-width:420px){.navlinks a:nth-child(2){display:none}.wrap{width:calc(100% - 28px)}}
    </style>
</head>
<body>
<header class="top"><div class="wrap nav">
    <a class="logo" href="{{ route('home') }}">ROAMFORM</a>
    <nav class="navlinks"><a href="{{ route('home') }}">HOME</a><a href="#collection">COLLECTION</a><a href="mailto:hello@roamform.com">CONTACT</a></nav>
</div></header>
<main>@yield('content')</main>
<footer><div class="wrap">© {{ date('Y') }} ROAMFORM. Travel better. Live organized.</div></footer>
</body>
</html>
