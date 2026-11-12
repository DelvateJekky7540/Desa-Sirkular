<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catatan bank sampah, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Pencatatan setoran sampah bank sampah Desa Sukamaju.')">
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
            <div class="flex w-full items-center justify-between gap-4 md:w-auto md:justify-end">
                <p class="text-left text-[12px] leading-[1.4] text-muted md:text-right">
                    <span class="block font-medium text-ink">Pengelola Bank Sampah</span>
                    {{ $bankAktif?->nama_bank ?? 'Bank Sampah Sumber Rejeki' }}, Desa Sukamaju
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
            Angka di halaman ini berasal dari pencatatan bank sampah dan diperbarui setiap setoran disimpan.
        </div>
    </footer>
</body>
</html>
