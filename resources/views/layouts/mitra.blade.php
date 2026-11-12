<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog bahan baku, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Bahan baku sampah berstatus sudah ditimbang untuk Mitra Daur Ulang, Desa Sukamaju.')">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    @php
        $routeAktif = request()->route()?->getName();
    @endphp

    <div class="mx-auto flex max-w-[1280px] flex-col md:flex-row">
        <aside class="border-b border-stone md:w-[240px] md:shrink-0 md:border-b-0 md:border-r">
            <div class="flex items-start justify-between gap-3 px-4 py-3 md:px-5 md:py-6">
                <div>
                    <span class="font-display text-[20px] font-medium leading-none text-forest">Desa Sirkular</span>
                    <p class="mt-1.5 text-[12px] leading-[1.4] text-muted md:mt-2">Mitra Daur Ulang, Desa Sukamaju</p>
                </div>
                @include('partials.keluar')
            </div>

            <nav aria-label="Navigasi mitra" class="flex gap-x-5 overflow-x-auto px-4 pb-2 md:flex-col md:gap-x-0 md:overflow-visible md:px-5 md:pb-12">
                <a
                    href="{{ route('mitra.katalog') }}"
                    @if ($routeAktif === 'mitra.katalog') aria-current="page" @endif
                    class="flex shrink-0 items-center border-b-2 py-3 text-[14px] leading-[1.4] md:w-full md:border-b-0 md:border-l-2 md:py-1.5 md:pl-3 {{ $routeAktif === 'mitra.katalog' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                >
                    Katalog bahan baku
                </a>
                <a
                    href="{{ route('mitra.penawaran') }}"
                    @if ($routeAktif === 'mitra.penawaran') aria-current="page" @endif
                    class="flex shrink-0 items-center border-b-2 py-3 text-[14px] leading-[1.4] md:w-full md:border-b-0 md:border-l-2 md:py-1.5 md:pl-3 {{ $routeAktif === 'mitra.penawaran' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                >
                    Penawaran saya
                </a>
            </nav>
        </aside>

        <main id="konten" class="min-w-0 flex-1 px-4 pb-16 pt-6 md:px-6 md:pb-24 md:pt-10">
            @yield('content')
        </main>
    </div>

    <footer class="border-t border-stone">
        <div class="mx-auto max-w-[1280px] px-4 py-4 text-[12px] leading-[1.4] text-muted md:px-6">
            Angka berat di halaman ini berasal dari hasil timbang bank sampah dan diperbarui setiap petugas menyimpan data.
        </div>
    </footer>
</body>
</html>
