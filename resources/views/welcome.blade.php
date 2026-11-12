<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desa Sirkular, Ekonomi sirkular sampah desa</title>
    <meta name="description" content="Platform ekonomi sirkular sampah desa Sukamaju: warga menabung dari setoran, bank sampah bertransaksi, mitra daur ulang mendapat bahan baku.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    @use('App\Support\Angka')

    @php
        $peran = [
            ['Admin Desa', 'Membaca laporan desa dari satu halaman: sampah tercatat, nilai ekonomi, dan setoran terbaru.'],
            ['Bank Sampah', 'Mencatat setoran warga lewat form timbang, lalu mengelola antrean penimbangan.'],
            ['Warga', 'Menyetor sampah terpilah, menghitung perkiraan nilainya, dan memantau tabungan sampah.'],
            ['Mitra Daur Ulang', 'Melihat katalog bahan baku yang tersedia di bank sampah, lalu mengajukan penawaran.'],
            ['Petugas', 'Menimbang setoran warga di bank sampah dan menjaga antrean tetap jalan.'],
        ];

        $fitur = [
            ['Smart Waste Mapping', 'Peta titik setoran tiap dusun dengan sisa kapasitas dan jadwal pengangkutan.', 'Dalam pembangunan'],
            ['Waste Value Calculator', 'Perkiraan nilai setoran sebelum ditimbang, dihitung dari harga acuan per kilogram.', 'Sudah jalan di demo'],
            ['Waste Matching', 'Menjodohkan stok bank sampah dengan kebutuhan bahan baku mitra, lengkap dengan berat minimum.', 'Sudah jalan di demo'],
            ['Waste Tracking', 'Status setoran mengalir dari menunggu timbang, sudah ditimbang, sampai masuk tabungan.', 'Sudah jalan di demo'],
            ['Environmental Impact', 'Rekap sampah yang didaur ulang dan nilainya, siap jadi laporan bulanan desa.', 'Dalam pembangunan'],
            ['Waste Recommendation', 'Saran jenis sampah yang layak dipilah bulan ini, berdasarkan harga acuan tertinggi.', 'Dalam pembangunan'],
        ];

        $alur = [
            ['Warga', 'Bu Siti Rahayu', 'Memilah sampah di rumah, lalu menyetor ke bank sampah desa.'],
            ['Bank sampah', 'Bank Sampah Sumber Rejeki', 'Menimbang setoran, mencatat nilai, dan menyimpan stok per jenis sampah.'],
            ['Mitra', 'Mitra Plastik Jaya', 'Membeli bahan baku terpilah untuk dijadikan produk daur ulang.'],
        ];

        $penghubung = [
            'Setoran ditimbang, nilai masuk tabungan sampah',
            'Stok per jenis dikumpulkan, dijual ke mitra',
        ];
    @endphp

    <header class="sticky top-0 z-50 border-b border-stone bg-bone">
        <div class="mx-auto flex min-h-16 max-w-[1100px] items-center justify-between gap-4 px-4 py-4 md:px-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('landing') }}" class="flex flex-col justify-center">
                    <span class="font-display text-[20px] font-medium leading-none text-forest">Desa Sirkular</span>
                    <span class="mt-1 text-[10px] font-medium leading-[12px] text-muted">Ekonomi Sirkular Desa</span>
                </a>
                <p class="hidden items-center gap-2 sm:inline-flex">
                    <span class="h-2 w-2 rounded-full bg-moss" aria-hidden="true"></span>
                    <span class="text-[12px] leading-none text-muted">Sistem Aktif</span>
                </p>
            </div>
            @guest
                <div class="flex items-center gap-3 md:gap-4">
                    <p class="hidden whitespace-nowrap font-mono text-[13px] leading-none text-muted md:inline-flex">{{ now()->locale('id')->translatedFormat('l, j M Y') }}</p>
                    <a href="{{ route('tracking.form') }}" class="inline-flex h-11 items-center text-[13px] font-medium leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline">
                        Lacak sampah
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex h-11 items-center justify-center rounded-control border border-forest px-5 text-[13px] font-medium leading-none text-forest hover:bg-forest hover:text-white active:translate-y-px">
                        Masuk
                    </a>
                </div>
            @else
                <div class="flex items-center gap-3">
                    <p class="hidden whitespace-nowrap font-mono text-[13px] leading-none text-muted md:inline-flex">{{ now()->locale('id')->translatedFormat('l, j M Y') }}</p>
                    <a href="{{ route('tracking.form') }}" class="hidden h-11 items-center text-[13px] font-medium leading-none text-ink hover:text-forest sm:inline-flex">
                        Lacak sampah
                    </a>
                    <p class="text-[12px] leading-[1.4] text-muted">{{ auth()->user()?->nama }}</p>
                    @include('partials.keluar')
                </div>
            @endguest
        </div>
    </header>

    <main id="konten" tabindex="-1">
        <section aria-labelledby="judul-hero" class="mx-auto max-w-[1100px] px-4 pt-12 md:px-6 md:pt-20">
            <div class="max-w-[720px]">
                <h1 id="judul-hero" class="font-display text-[32px] font-medium leading-[1.15] text-ink md:text-[48px] md:leading-[1.1]">
                    Sampah desa, rantai yang tidak putus.
                </h1>
                <p class="mt-3 max-w-[58ch] text-[15px] leading-[1.6] text-muted md:mt-4">
                    Desa Sirkular mencatat perjalanan sampah dari dapur warga sampai pabrik daur ulang. Warga menabung dari setoran, bank sampah bertransaksi, mitra mendapat bahan baku, dan desa membaca semuanya dalam satu laporan.
                </p>
                <div class="mt-6 md:mt-8">
                    <a href="{{ route('login') }}" class="inline-flex h-12 items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px">
                        Masuk ke sistem
                    </a>
                </div>
            </div>

            @if ($statistikError)
                <div role="alert" class="mt-12 border border-stone border-l-[3px] border-l-brick bg-paper px-4 py-3 md:mt-16">
                    <p class="text-[14px] font-medium leading-[1.5] text-ink">Ringkasan belum bisa ditampilkan.</p>
                    <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $statistikError }}</p>
                </div>
            @else
                <dl class="mt-12 grid grid-cols-1 border-y border-stone md:mt-16 md:grid-cols-3 md:divide-x md:divide-stone">
                    <div class="py-4 md:py-5 md:pr-6">
                        <dt class="text-[13px] leading-[1.4] text-muted">Sampah tercatat di bank sampah</dt>
                        <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ Angka::kg($statistik['berat']) }}</dd>
                    </div>
                    <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:px-6">
                        <dt class="text-[13px] leading-[1.4] text-muted">Nilai ekonomi setoran</dt>
                        <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ochre md:text-[32px]">{{ Angka::rupiah($statistik['nilai']) }}</dd>
                    </div>
                    <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:pl-6">
                        <dt class="text-[13px] leading-[1.4] text-muted">Bank sampah aktif</dt>
                        <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ $statistik['bank'] }}</dd>
                    </div>
                </dl>
                <p class="mt-2 text-[12px] leading-[1.4] text-muted">Angka di atas dihitung langsung dari data demo Desa Sukamaju.</p>
            @endif
        </section>

        <section aria-labelledby="judul-peran" class="mx-auto max-w-[1100px] px-4 py-16 md:px-6 md:py-24">
            <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-2 border-b border-stone pb-4">
                <h2 id="judul-peran" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Lima peran, satu rantai</h2>
                <p class="text-[13px] leading-[1.4] text-muted">Setiap peran punya halaman kerjanya sendiri.</p>
            </div>
            <ol>
                @foreach ($peran as $item)
                    <li class="grid grid-cols-1 gap-y-1 border-b border-stone py-4 md:grid-cols-[64px_240px_1fr] md:gap-x-8 md:py-5">
                        <span class="font-mono text-[12px] leading-[1.6] text-muted" aria-hidden="true">0{{ $loop->iteration }}</span>
                        <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">{{ $item[0] }}</h3>
                        <p class="max-w-[64ch] text-[15px] leading-[1.6] text-muted">{{ $item[1] }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        <section aria-labelledby="judul-fitur" class="mx-auto max-w-[1100px] px-4 pb-16 md:px-6 md:pb-24">
            <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-2">
                <h2 id="judul-fitur" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Enam fitur unggulan</h2>
                <p class="text-[13px] leading-[1.4] text-muted">Tiga sudah jalan di data demo, tiga masih dibangun.</p>
            </div>
            <ol class="mt-4">
                @foreach ($fitur as $item)
                    <li class="grid grid-cols-1 gap-y-1 border-t border-stone py-4 md:grid-cols-[64px_260px_1fr_170px] md:gap-x-8 md:py-5">
                        <span class="font-mono text-[12px] leading-[1.6] text-muted" aria-hidden="true">0{{ $loop->iteration }}</span>
                        <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">{{ $item[0] }}</h3>
                        <p class="max-w-[64ch] text-[15px] leading-[1.6] text-muted">{{ $item[1] }}</p>
                        <p class="font-mono text-[12px] leading-[1.6] text-muted md:text-right">{{ $item[2] }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        <section aria-labelledby="judul-alur" class="mx-auto max-w-[1100px] px-4 pb-20 md:px-6 md:pb-32">
            <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-2">
                <h2 id="judul-alur" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Dari rumah warga ke pabrik</h2>
                <p class="text-[13px] leading-[1.4] text-muted">Contoh dari data demo Desa Sukamaju.</p>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-x-4 gap-y-5 md:mt-10 md:grid-cols-[1fr_auto_1fr_auto_1fr] md:items-stretch md:gap-y-4">
                <div class="rounded-card border border-stone bg-paper p-4 md:p-5">
                    <p class="font-mono text-[12px] leading-[1.4] text-muted">{{ $alur[0][0] }}</p>
                    <h3 class="mt-1 font-display text-[18px] font-medium leading-[1.4] text-ink">{{ $alur[0][1] }}</h3>
                    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">{{ $alur[0][2] }}</p>
                </div>

                <div class="flex items-center justify-center gap-2 md:max-w-[190px] md:flex-col md:gap-1.5 md:py-2" aria-hidden="true">
                    <p class="text-[12px] leading-[1.4] text-muted md:text-center">{{ $penghubung[0] }}</p>
                    <svg class="h-4 w-4 shrink-0 text-moss md:-rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M6 13l6 6 6-6" />
                    </svg>
                </div>

                <div class="rounded-card border border-stone bg-paper p-4 md:p-5">
                    <p class="font-mono text-[12px] leading-[1.4] text-muted">{{ $alur[1][0] }}</p>
                    <h3 class="mt-1 font-display text-[18px] font-medium leading-[1.4] text-ink">{{ $alur[1][1] }}</h3>
                    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">{{ $alur[1][2] }}</p>
                </div>

                <div class="flex items-center justify-center gap-2 md:max-w-[190px] md:flex-col md:gap-1.5 md:py-2" aria-hidden="true">
                    <p class="text-[12px] leading-[1.4] text-muted md:text-center">{{ $penghubung[1] }}</p>
                    <svg class="h-4 w-4 shrink-0 text-moss md:-rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M6 13l6 6 6-6" />
                    </svg>
                </div>

                <div class="rounded-card border border-stone bg-paper p-4 md:p-5">
                    <p class="font-mono text-[12px] leading-[1.4] text-muted">{{ $alur[2][0] }}</p>
                    <h3 class="mt-1 font-display text-[18px] font-medium leading-[1.4] text-ink">{{ $alur[2][1] }}</h3>
                    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">{{ $alur[2][2] }}</p>
                </div>
            </div>

            @if ($statistikError)
                <p class="mt-6 max-w-[70ch] text-[15px] leading-[1.6] text-muted md:mt-8">
                    Setiap kilogram yang berpindah di rantai ini tercatat: siapa menyetor, siapa menimbang, dan ke mana stoknya keluar.
                </p>
            @else
                <p class="mt-6 max-w-[70ch] text-[15px] leading-[1.6] text-muted md:mt-8">
                    Sampai hari ini, {{ Angka::kg($statistik['berat']) }} sampah sudah berpindah lewat rantai ini di data demo Desa Sukamaju.
                </p>
            @endif
        </section>
    </main>

    <footer class="border-t border-stone">
        <div class="mx-auto max-w-[1100px] px-4 py-12 md:px-6 md:py-16">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-[1.4fr_1fr_1.1fr_1.2fr]">
                <div>
                    <p class="font-display text-[24px] font-medium leading-[1.3] text-forest">Desa Sirkular</p>
                    <p class="mt-3 max-w-[36ch] text-[13px] leading-[1.6] text-muted">Menghubungkan warga, bank sampah, mitra daur ulang, dan pemerintah desa.</p>
                </div>

                <nav aria-labelledby="footer-navigasi">
                    <p id="footer-navigasi" class="text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Navigasi</p>
                    <ul class="mt-3">
                        <li><a href="{{ route('landing') }}" class="flex min-h-11 items-center text-[13px] leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline md:min-h-9">Beranda</a></li>
                        <li><a href="{{ route('tracking.form') }}" class="flex min-h-11 items-center text-[13px] leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline md:min-h-9">Lacak sampah</a></li>
                        <li><a href="{{ route('warga.estimasi') }}" class="flex min-h-11 items-center text-[13px] leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline md:min-h-9">Perkiraan nilai setoran</a></li>
                        <li><a href="{{ route('mitra.katalog') }}" class="flex min-h-11 items-center text-[13px] leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline md:min-h-9">Katalog bahan baku</a></li>
                        <li><a href="{{ route('login') }}" class="flex min-h-11 items-center text-[13px] leading-none text-ink decoration-1 decoration-forest underline-offset-4 hover:text-forest hover:underline md:min-h-9">Masuk</a></li>
                    </ul>
                </nav>

                <div>
                    <p class="text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Fitur Utama</p>
                    <ul class="mt-3 space-y-1.5">
                        @foreach ($fitur as $item)
                            <li class="text-[13px] leading-[1.6] text-muted">{{ $item[0] }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <p class="text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Informasi</p>
                    <ul class="mt-3 space-y-1.5">
                        <li class="text-[13px] leading-[1.6] text-muted">Data demo: akun pengguna, bank sampah, dan setoran ditanam lewat seeder.</li>
                        <li class="text-[13px] leading-[1.6] text-muted">Tiga fitur sudah jalan di demo, tiga masih dibangun.</li>
                        <li class="text-[13px] leading-[1.6] text-muted">Desa percontohan: Sukamaju.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-stone">
            <div class="mx-auto max-w-[1100px] px-4 py-5 md:px-6">
                <p class="text-[12px] leading-[1.4] text-muted">&copy; 2026 Desa Sirkular. Dibangun untuk KRENOVA UNISRI.</p>
            </div>
        </div>
    </footer>
</body>
</html>
