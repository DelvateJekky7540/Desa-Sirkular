<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catatan bank sampah, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Pencatatan setoran sampah bank sampah Desa Sukamaju.')">
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

    <header class="sticky top-0 z-40 border-b border-stone bg-bone">
        <div class="mx-auto max-w-[1280px] px-4 md:px-6">
            <div class="flex min-h-16 items-center justify-between gap-4">
                <div>
                    <span class="font-display text-[20px] font-medium leading-none text-forest">Desa Sirkular</span>
                    <p class="mt-1.5 text-[12px] leading-[1.4] text-muted">Pengelola Bank Sampah</p>
                </div>
                <div class="flex items-center gap-3 md:gap-4">
                    <p class="hidden max-w-[300px] text-right text-[12px] leading-[1.4] text-muted md:block">
                        <span class="block font-medium text-ink">{{ $bankAktif?->nama_bank ?? 'Bank Sampah Sumber Rejeki' }}</span>
                        Desa Sukamaju
                    </p>
                    @include('partials.keluar')
                </div>
            </div>
        </div>

        <div class="border-t border-stone">
            <nav aria-label="Navigasi bank" class="mx-auto flex max-w-[1280px] gap-x-6 overflow-x-auto px-4 md:px-6">
                <a
                    href="{{ route('bank.penerimaan') }}"
                    @if ($routeAktif === 'bank.penerimaan') aria-current="page" @endif
                    class="flex min-h-12 shrink-0 items-center border-b-2 text-[14px] leading-[1.4] {{ $routeAktif === 'bank.penerimaan' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                >
                    Penerimaan
                </a>
                <a
                    href="{{ route('bank.penimbangan') }}"
                    @if ($routeAktif === 'bank.penimbangan') aria-current="page" @endif
                    class="flex min-h-12 shrink-0 items-center border-b-2 text-[14px] leading-[1.4] {{ $routeAktif === 'bank.penimbangan' ? 'border-forest font-medium text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                >
                    Penimbangan
                </a>
            </nav>
        </div>
    </header>

    <div class="flex w-full flex-1 flex-col">
        <main id="konten" class="mx-auto w-full max-w-[1280px] flex-1 px-4 pb-16 pt-6 md:px-6 md:pb-24 md:pt-8">
            @yield('content')
        </main>
    </div>

    <footer class="border-t border-stone">
        <div class="mx-auto max-w-[1280px] px-4 py-4 text-[12px] leading-[1.4] text-muted md:px-6">
            Angka di halaman ini berasal dari pencatatan bank sampah dan diperbarui setiap setoran disimpan.
        </div>
    </footer>
</body>
</html>
