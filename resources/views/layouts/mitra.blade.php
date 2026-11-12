<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog bahan baku, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Bahan baku sampah berstatus sudah ditimbang untuk Mitra Daur Ulang, Desa Sukamaju.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    @php
        $routeAktif = request()->route()?->getName();
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-1 flex-col md:flex-row md:items-stretch">
        <aside class="sticky top-0 z-40 border-b border-stone bg-bone md:static md:z-auto md:w-[240px] md:shrink-0 md:self-stretch md:border-b-0 md:border-r">
            <div class="md:sticky md:top-0 md:z-40">
                <div class="flex min-h-16 items-center justify-between gap-3 px-4 py-2 md:px-5 md:py-6">
                    <div>
                        <span class="font-display text-[20px] font-medium leading-none text-forest">Desa Sirkular</span>
                        <p class="mt-1.5 text-[12px] leading-[1.4] text-muted md:mt-2">Mitra Daur Ulang, Desa Sukamaju</p>
                    </div>
                    @include('partials.keluar')
                </div>

                <nav aria-label="Navigasi mitra" class="flex gap-x-5 border-t border-stone px-4 md:flex-col md:gap-x-0 md:border-t-0 md:px-5 md:pb-12">
                    <a
                        href="{{ route('mitra.katalog') }}"
                        @if ($routeAktif === 'mitra.katalog') aria-current="page" @endif
                        class="flex min-h-12 shrink-0 items-center border-b-2 text-[14px] leading-[1.4] md:w-full md:border-b-0 md:border-l-2 md:pl-3 {{ $routeAktif === 'mitra.katalog' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                    >
                        Katalog bahan baku
                    </a>
                    <a
                        href="{{ route('mitra.penawaran') }}"
                        @if ($routeAktif === 'mitra.penawaran') aria-current="page" @endif
                        class="flex min-h-12 shrink-0 items-center border-b-2 text-[14px] leading-[1.4] md:w-full md:border-b-0 md:border-l-2 md:pl-3 {{ $routeAktif === 'mitra.penawaran' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                    >
                        Penawaran saya
                    </a>
                </nav>
            </div>
        </aside>

        <main id="konten" class="min-w-0 flex-1 px-4 pb-16 pt-6 md:px-6 md:pb-24 md:pt-6">
            @yield('content')
        </main>
    </div>

    <footer class="border-t border-stone">
        <div class="mx-auto max-w-[1280px] px-4 py-4 text-[12px] leading-[1.4] text-muted md:pl-[264px] md:pr-6">
            Angka berat di halaman ini berasal dari hasil timbang bank sampah dan diperbarui setiap petugas menyimpan data.
        </div>
    </footer>
</body>
</html>
