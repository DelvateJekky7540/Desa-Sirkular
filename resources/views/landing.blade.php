<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Desa-Sirkular — Ekonomi Sirkular untuk Desa</title>
<meta name="color-scheme" content="light">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<script>document.documentElement.classList.add("js")</script>
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script>
tailwind.config = {
  theme: { extend: {
    colors: {
      brand: { DEFAULT: '#2D6A4F', dark: '#1B4332', deep: '#1B4332', mint: '#F3F0E6', tint: '#E4DFCF' },
      accent: { DEFAULT: '#E5B93C', hover: '#CFA126', light: '#F5DE85', dark: '#7A5A0C' },
      sand: '#F3F0E6', brown: '#1C2420',
      ink: '#1C2420', slate2: '#4B5563', surface: '#FAF9F6', line: '#E7E5E4'
    },
    fontFamily: {
      display: ['Fraunces', 'Georgia', 'serif'],
      sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif']
    }
  } }
}
</script>
<style>
  :root { box-sizing: border-box; padding-top: env(safe-area-inset-top, 0px); padding-bottom: env(safe-area-inset-bottom, 0px); color-scheme: light; }
  html { scroll-padding-top: 5rem; }
  body { background: #FAF9F6; color: #1C2420; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
  .ic { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round; }
  @media (prefers-reduced-motion: reduce) { * { transition: none !important; scroll-behavior: auto !important; } }
  @keyframes up { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
  @keyframes settle { from { transform: scale(1.06); } to { transform: scale(1); } }
  .hero-in { animation: up .8s cubic-bezier(.22,.61,.36,1) both; animation-delay: var(--d, 0s); }
  .hero-bg { animation: settle 1.6s cubic-bezier(.22,.61,.36,1) both; }
  .js .rv { opacity: 0; transform: translateY(12px); transition: opacity .6s ease-out, transform .6s ease-out; }
  .js .rv.in { opacity: 1; transform: none; }
  @media (prefers-reduced-motion: reduce) { .hero-in, .hero-bg { animation: none; } .js .rv { opacity: 1; transform: none; transition: none; } }
</style>
</head>
<body class="antialiased">

<!-- Header -->
<header class="sticky top-0 z-20 border-b border-line bg-surface">
  <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-5">
    <a href="#top" class="font-display text-lg font-semibold uppercase tracking-wide text-brand-dark rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">Desa-Sirkular</a>
    <nav aria-label="Navigasi utama" class="hidden items-center gap-8 text-sm font-medium text-slate2 md:flex">
      <a href="#pilar" class="rounded hover:text-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">Pilar</a>
      <a href="#inovasi" class="rounded hover:text-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">Inovasi</a>
      <a href="#alur" class="rounded hover:text-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">Alur Kerja</a>
    </nav>
    <details id="menu" class="relative ml-auto md:hidden">
      <summary aria-label="Menu" class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-md border border-line bg-white text-brand-dark transition-colors hover:border-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand [&::-webkit-details-marker]:hidden"><svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg></summary>
      <div class="absolute right-0 top-14 w-60 rounded-lg border border-line bg-white p-2 shadow-sm">
        <a href="#pilar" class="block rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-brand-mint focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand">Pilar</a>
        <a href="#inovasi" class="block rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-brand-mint focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand">Inovasi</a>
        <a href="#alur" class="block rounded-md px-3 py-3 text-sm font-medium text-ink hover:bg-brand-mint focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand">Alur Kerja</a>
        <a href="#inovasi" class="mt-1 block rounded-md bg-accent px-3 py-3 text-center text-sm font-semibold text-brand-deep hover:bg-accent-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand">Jelajahi Platform</a>
      </div>
    </details>
    <a href="#inovasi" class="hidden min-h-[44px] items-center rounded-md bg-accent px-4 text-sm font-semibold text-brand-deep shadow-sm transition-colors hover:bg-accent-hover active:bg-accent-hover sm:inline-flex focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">Jelajahi Platform</a>
  </div>
</header>

<main id="top">
<!-- Hero (full-bleed) -->
<section class="relative isolate flex min-h-[600px] flex-col overflow-hidden bg-brand-deep lg:min-h-[calc(100svh-4rem)]">
  <svg id="herobg" class="hero-bg absolute inset-0 -z-20 h-full w-full" viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Hamparan sawah desa saat matahari terbit">
    <defs>
      <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3C7F61"/><stop offset=".5" stop-color="#9CC8A8"/><stop offset=".78" stop-color="#EBEBB8"/><stop offset="1" stop-color="#F8F1CB"/></linearGradient>
      <radialGradient id="glow" cx=".5" cy=".5" r=".5"><stop offset="0" stop-color="#FFFBE6" stop-opacity=".95"/><stop offset=".35" stop-color="#FFF0A8" stop-opacity=".45"/><stop offset="1" stop-color="#FFF0A8" stop-opacity="0"/></radialGradient>
      <linearGradient id="field" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8DB85C"/><stop offset=".5" stop-color="#4C8A55"/><stop offset="1" stop-color="#2D6A4F"/></linearGradient>
    </defs>
    <rect width="1440" height="800" fill="url(#sky)"/>
    <circle cx="1020" cy="470" r="300" fill="url(#glow)"/>
    <circle cx="1020" cy="470" r="46" fill="#FFFBE6"/>
    <path d="M0 470 L0 430 Q60 405 110 430 Q170 398 230 428 Q300 404 360 432 Q440 410 520 434 Q620 412 720 436 Q840 414 940 438 Q1080 416 1180 438 Q1300 414 1440 436 L1440 470Z" fill="#3B7A55"/>
    <path d="M0 470 H1440 V800 H0Z" fill="url(#field)"/>
    <path d="M1020 470 L520 800 L640 800Z" fill="#FFFBE6" opacity=".07"/><path d="M1020 470 L760 800 L840 800Z" fill="#FFFBE6" opacity=".07"/><path d="M1020 470 L1140 800 L1260 800Z" fill="#FFFBE6" opacity=".07"/><path d="M1020 470 L1380 800 L1500 800Z" fill="#FFFBE6" opacity=".07"/>
    <g id="rows" stroke="#C9E4A8" stroke-linecap="round" fill="none"></g>
    <path d="M0 470 H1440" stroke="#F1F5DC" stroke-opacity=".5"/>
  </svg>
  <div class="absolute inset-0 -z-10 bg-gradient-to-t from-brand-deep/85 via-brand-deep/55 to-brand-deep/25 md:bg-gradient-to-r md:from-brand-deep/80 md:via-brand-deep/40 md:to-transparent"></div>

  <div class="mx-auto flex w-full max-w-6xl flex-1 flex-col justify-center px-5 pb-8 pt-14 md:pt-20">
    <h1 class="hero-in max-w-3xl font-display text-[2.5rem] font-semibold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-6xl" style="--d:.1s">Membangun Masa Depan Desa dengan Ekonomi Sirkular</h1>
    <p class="hero-in mt-6 max-w-xl text-lg leading-relaxed text-white/90" style="--d:.25s">Platform terintegrasi yang menghubungkan masyarakat, bank sampah, mitra daur ulang, dan pemerintah desa. Sampah desa, rantai yang tidak putus.</p>
    <div class="hero-in mt-8" style="--d:.4s">
      <a href="#inovasi" class="inline-flex min-h-[48px] items-center rounded-md bg-accent px-6 text-base font-semibold text-brand-deep shadow-sm transition-colors duration-150 hover:bg-accent-light active:bg-accent-light focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Jelajahi Platform</a>
    </div>
  </div>

  <dl id="pilar" class="hero-in mx-auto grid w-full max-w-6xl grid-cols-2 gap-6 px-5 pb-10 sm:grid-cols-[repeat(2,minmax(0,12rem))] sm:gap-12" style="--d:.6s">
    <div><dd class="font-display text-4xl font-semibold text-accent-light">340+</dd><dt class="mt-1 text-sm text-white/90">Keluarga tertunjang</dt></div>
    <div><dd class="font-display text-4xl font-semibold text-accent-light">87%</dd><dt class="mt-1 text-sm text-white/90">Sampah terolah</dt></div>
  </dl>
</section>

<!-- Inovasi -->
<section id="inovasi" class="border-b border-tint bg-brand-mint">
  <div class="mx-auto grid max-w-6xl gap-12 px-5 py-20 md:py-24 lg:grid-cols-12 lg:gap-16">
    <div class="rv lg:col-span-5">
      <h2 class="font-display text-3xl font-semibold tracking-tight text-ink sm:text-4xl">Bukan Sekadar Pencatatan Sampah</h2>
      <p class="mt-4 text-lg leading-relaxed text-slate2">Tiga inovasi inti yang mengubah bank sampah dari pencatat manual menjadi mesin pertukaran nilai otomatis.</p>

      <div class="mt-10 divide-y divide-tint border-y border-tint">
        <div class="flex gap-4 py-6">
          <svg class="ic mt-0.5 shrink-0 text-brand" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
          <div><h3 class="font-semibold text-ink">Waste Matching System</h3>
          <p class="mt-1.5 leading-relaxed text-slate2">Mempertemukan stok sampah bank sampah dengan kebutuhan bahan baku mitra secara otomatis. Algoritma pencocokan real-time menghilangkan pencarian manual.</p></div>
        </div>
        <div class="flex gap-4 py-6">
          <svg class="ic mt-0.5 shrink-0 text-brand" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><h3 class="font-semibold text-ink">Waste Tracking</h3>
          <p class="mt-1.5 leading-relaxed text-slate2">Lacak perjalanan sampah dari sumbernya hingga menjadi produk baru. Audit trail lengkap untuk laporan ESG dan sertifikasi.</p></div>
        </div>
      </div>
    </div>

    <div class="rv lg:col-span-7" style="transition-delay:120ms">
      <div class="rounded-xl border border-brand bg-brand p-6 shadow-sm sm:p-8">
        <div class="flex items-center gap-3">
          <svg class="ic text-accent-light" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 6h8"/><path d="M8 11h.01M12 11h.01M16 11h.01M8 15h.01M12 15h.01M16 15h.01M8 19h8"/></svg>
          <h3 class="text-lg font-semibold text-white">Waste Value Calculator</h3>
        </div>
        <p class="mt-2 max-w-md leading-relaxed text-white/80">Hitung estimasi nilai sampah secara real-time. Transparansi harga untuk warga dan mitra.</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 sm:items-end">
          <div>
            <label for="kg" class="text-sm font-medium text-white">Berat sampah (kg)</label>
            <input id="kg" type="number" inputmode="decimal" min="0" step="0.5" value="12" placeholder="0" class="mt-2 w-full rounded-lg border border-line bg-white px-3.5 py-3 text-lg text-ink focus-visible:border-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-accent-light">
            <div class="mt-3 flex gap-2" role="group" aria-label="Pilih berat cepat">
              <button type="button" data-kg="5" class="preset rounded-md border border-white/30 px-3.5 py-2.5 text-sm font-medium text-white/85 transition-colors duration-150 hover:border-accent-light hover:text-white active:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-accent-light">5 kg</button>
              <button type="button" data-kg="12" class="preset rounded-md border border-white/30 px-3.5 py-2.5 text-sm font-medium text-white/85 transition-colors duration-150 hover:border-accent-light hover:text-white active:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-accent-light">12 kg</button>
              <button type="button" data-kg="50" class="preset rounded-md border border-white/30 px-3.5 py-2.5 text-sm font-medium text-white/85 transition-colors duration-150 hover:border-accent-light hover:text-white active:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-accent-light">50 kg</button>
            </div>
          </div>
          <div class="rounded-lg bg-sand px-5 py-4">
            <p class="text-sm text-slate2">Estimasi nilai</p>
            <p id="hasil" aria-live="polite" class="mt-1 font-display text-3xl font-semibold text-accent-dark sm:text-4xl">Rp 408.000</p>
          </div>
        </div>
        <p class="mt-6 border-t border-white/15 pt-4 text-sm text-white/90">Dihitung dengan harga Rp 34.000/kg. Harga akhir mengikuti penawaran mitra yang cocok.</p>
      </div>
    </div>
  </div>
</section>

<!-- Alur Kerja -->
<section id="alur" class="bg-surface">
  <div class="mx-auto grid max-w-6xl gap-12 px-5 py-20 md:py-24 lg:grid-cols-12">
    <div class="rv lg:col-span-4">
      <h2 class="font-display text-3xl font-semibold tracking-tight text-ink sm:text-4xl">Alur Kerja</h2>
      <p class="mt-4 max-w-sm text-lg leading-relaxed text-slate2">Dari rumah warga hingga laporan pemerintah. Empat langkah, satu platform terintegrasi.</p>
    </div>

    <ol class="rv lg:col-span-8" style="transition-delay:120ms">
      <li class="grid grid-cols-[2.5rem_1fr] gap-4 border-t border-line py-6 first:border-t-0 first:pt-0">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent font-semibold text-brand-deep" aria-hidden="true">1</span>
        <div>
          <h3 class="text-lg font-semibold text-ink">Warga memilah &amp; menyetor</h3>
          <p class="mt-1 leading-relaxed text-slate2">Warga memilah sampah di rumah, menyetorkan ke bank sampah, dan memonitor perpoinannya via aplikasi. Setiap kilogram tercatat otomatis.</p>
        </div>
      </li>
      <li class="grid grid-cols-[2.5rem_1fr] gap-4 border-t border-line py-6">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent font-semibold text-brand-deep" aria-hidden="true">2</span>
        <div>
          <h3 class="text-lg font-semibold text-ink">Bank sampah menimbang &amp; mencatat</h3>
          <p class="mt-1 leading-relaxed text-slate2">Petugas menimbang, memvalidasi jenis, dan memasukkan ke sistem. Saldo warga bertambah instan, stok bahan baku ter-update real-time.</p>
        </div>
      </li>
      <li class="grid grid-cols-[2.5rem_1fr] gap-4 border-t border-line py-6">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent font-semibold text-brand-deep" aria-hidden="true">3</span>
        <div>
          <h3 class="text-lg font-semibold text-ink">Sistem mencocokkan dengan mitra</h3>
          <p class="mt-1 leading-relaxed text-slate2">Algoritma mencocokkan stok bank sampah dengan pesanan mitra daur ulang. Penawaran harga transparan, deal instan.</p>
        </div>
      </li>
      <li class="grid grid-cols-[2.5rem_1fr] gap-4 border-t border-line py-6">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent font-semibold text-brand-deep" aria-hidden="true">4</span>
        <div>
          <h3 class="text-lg font-semibold text-ink">Mitra mengambil, dampak tercatat</h3>
          <p class="mt-1 leading-relaxed text-slate2">Mitra mengambil bahan baku, sistem mencatat CO₂ yang tereliminasi, nilai ekonomi, dan volume. Laporan siap untuk Pemdes &amp; ESG.</p>
        </div>
      </li>
    </ol>
  </div>
</section>
</main>

<!-- Footer -->
<footer class="bg-brand text-white">
  <div class="mx-auto max-w-6xl px-5 py-14">
    <div class="grid gap-10 md:grid-cols-12">
      <div class="md:col-span-6">
        <p class="font-display text-xl font-semibold">Desa-Sirkular</p>
        <p class="mt-3 max-w-xs text-sm leading-relaxed text-white/85">Inovasi pengolahan sampah desa berbasis ekonomi sirkular.</p>
      </div>
      <nav aria-label="Produk" class="md:col-span-3">
        <p class="text-sm font-semibold">Produk</p>
        <ul class="mt-4 space-y-3 text-sm text-white/85">
          <li><a href="#pilar" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Pilar ekosistem</a></li>
          <li><a href="#inovasi" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Inovasi unggulan</a></li>
          <li><a href="#alur" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Alur kerja</a></li>
        </ul>
      </nav>
      <nav aria-label="Perusahaan" class="md:col-span-3">
        <p class="text-sm font-semibold">Perusahaan</p>
        <ul class="mt-4 space-y-3 text-sm text-white/85">
          <li><a href="#top" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Tentang kami</a></li>
          <li><a href="#top" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Karir</a></li>
          <li><a href="#top" class="inline-block rounded py-1 hover:text-white hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Blog</a></li>
        </ul>
      </nav>
    </div>
    <div class="mt-12 flex flex-col gap-2 border-t border-white/20 pt-6 text-xs text-white/85 sm:flex-row sm:justify-between">
      <p>© 2026 Desa-Sirkular. Hak cipta dilindungi.</p>
      <p>Dibangun untuk masa depan desa yang berkelanjutan.</p>
    </div>
  </div>
</footer>
<script>
(function () {
  var bg = document.getElementById('herobg'), mq = window.matchMedia('(max-aspect-ratio: 1/1)');
  function fit() { if (bg) bg.setAttribute('viewBox', mq.matches ? '700 0 640 800' : '0 0 1440 800'); }
  fit(); window.addEventListener('resize', fit);
  var NS = 'http://www.w3.org/2000/svg', g = document.getElementById('rows');
  if (g) for (var i = -14; i <= 14; i++) {
    var l = document.createElementNS(NS, 'line');
    l.setAttribute('x1', 1020 + i * 14); l.setAttribute('y1', 472);
    l.setAttribute('x2', 1020 + i * 190); l.setAttribute('y2', 800);
    l.setAttribute('stroke-width', 1.5 + Math.abs(i) * .12);
    l.setAttribute('stroke-opacity', .28);
    g.appendChild(l);
  }
  var kg = document.getElementById('kg'), out = document.getElementById('hasil'), RATE = 34000;
  function hitung() {
    var v = parseFloat(String(kg.value).replace(',', '.'));
    if (!(v >= 0)) v = 0;
    out.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v * RATE));
  }
  kg.addEventListener('input', hitung);
  document.querySelectorAll('.preset').forEach(function (b) {
    b.addEventListener('click', function () { kg.value = b.dataset.kg; hitung(); });
  });
  var m = document.getElementById('menu');
  if (m) m.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { m.removeAttribute('open'); }); });
  var rv = document.querySelectorAll('.rv');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }); }, { threshold: .12, rootMargin: '0px 0px -5% 0px' });
    rv.forEach(function (el) { io.observe(el); });
  } else { rv.forEach(function (el) { el.classList.add('in'); }); }
  hitung();
})();
</script>
</body>
</html>