<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Platform ekonomi sirkular sampah desa Sukamaju: warga menabung dari setoran, bank sampah bertransaksi, mitra daur ulang mendapat bahan baku.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    <header class="border-b border-stone">
        <div class="mx-auto flex min-h-16 w-full max-w-[1100px] items-center justify-between gap-4 px-4 md:px-6">
            <a href="{{ route('landing') }}" class="inline-flex min-h-11 items-center font-display text-[20px] font-medium leading-none text-forest">
                Desa Sirkular
            </a>
            <div class="flex items-center gap-3 md:gap-4">
                <a
                    href="{{ route('tracking.form') }}"
                    @if (request()->route()?->getName() === 'tracking.form') aria-current="page" @endif
                    class="tautan-garis tautan-garis-nav inline-flex h-11 items-center text-[13px] font-medium leading-none text-ink hover:text-forest"
                >
                    Lacak sampah
                </a>
                @guest
                    <a href="{{ route('login') }}" class="inline-flex h-11 items-center justify-center rounded-control border border-stone bg-paper px-4 text-[13px] font-medium leading-none text-ink transition-colors duration-150 hover:border-forest hover:bg-forest hover:text-bone">
                        Masuk
                    </a>
                @else
                    <p class="hidden text-[12px] leading-[1.4] text-muted sm:block">{{ auth()->user()?->nama }}</p>
                    @include('partials.keluar')
                @endguest
            </div>
        </div>
    </header>

    <main id="konten" tabindex="-1" class="mx-auto w-full max-w-[720px] flex-1 px-4 pb-16 pt-10 md:px-6 md:pb-24 md:pt-14">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="border-t border-stone">
        <div class="mx-auto flex max-w-[1100px] flex-wrap items-baseline justify-between gap-x-6 gap-y-3 px-4 py-6 md:px-6">
            <div>
                <p class="font-display text-[16px] font-medium leading-none text-forest">Desa Sirkular</p>
                <p class="mt-1.5 text-[12px] leading-[1.4] text-muted">Ekonomi sirkular sampah desa. Dibangun untuk Desa Sukamaju.</p>
                <a href="{{ route('tracking.form') }}" class="tautan-garis mt-1 inline-flex min-h-11 items-center text-[12px] font-medium leading-none text-forest">
                    Lacak sampah
                </a>
            </div>
            <p class="text-[12px] leading-[1.4] text-muted">Data demo: akun pengguna, bank sampah, dan setoran ditanam lewat seeder.</p>
        </div>
    </footer>
</body>
</html>
