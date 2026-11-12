<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laporan desa, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Laporan monitoring bank sampah Desa Sukamaju untuk Admin Desa.')">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    <header class="border-b border-stone">
        <div class="mx-auto flex min-h-16 max-w-[1280px] flex-wrap items-center justify-between gap-x-4 gap-y-2 px-4 py-3 md:px-6">
            <span class="font-display text-[20px] font-medium leading-none text-forest">Desa Sirkular</span>
            <div class="flex items-center justify-end gap-3">
                <p class="text-right text-[12px] leading-[1.4] text-muted">
                    <span class="block font-medium text-ink">Admin Desa</span>
                    Desa Sukamaju
                </p>
                @include('partials.keluar')
            </div>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-[1280px] px-4 pb-16 pt-6 md:px-6 md:pb-24 md:pt-10">
        @yield('content')
    </main>

    <footer class="border-t border-stone">
        <div class="mx-auto max-w-[1280px] px-4 py-4 text-[12px] leading-[1.4] text-muted md:px-6">
            Angka di laporan ini berasal dari pencatatan seluruh bank sampah di Desa Sukamaju dan diperbarui setiap setoran disimpan.
        </div>
    </footer>
</body>
</html>
