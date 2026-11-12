<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=resizes-content">
    <title>@yield('title', 'Catatan warga, Desa Sirkular')</title>
    <meta name="description" content="@yield('description', 'Catatan setoran sampah warga Desa Sukamaju.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    @php
        $ruteAktif = request()->route()?->getName();
        $menuWarga = [
            [
                'rute' => 'warga.beranda',
                'label' => 'Beranda',
                'ikon' => 'M219.31,108.68l-80-80a16,16,0,0,0-22.62,0l-80,80A15.87,15.87,0,0,0,32,120v96a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V160h32v56a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V120A15.87,15.87,0,0,0,219.31,108.68ZM208,208H160V152a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8v56H48V120l80-80,80,80Z',
            ],
            [
                'rute' => 'warga.setor',
                'label' => 'Setor',
                'ikon' => 'M96,208a8,8,0,0,1-8,8H40a24,24,0,0,1-20.77-36l34.29-59.25L39.47,124.5A8,8,0,1,1,35.33,109l32.77-8.77a8,8,0,0,1,9.8,5.66l8.79,32.77A8,8,0,0,1,81,148.5a8.37,8.37,0,0,1-2.08.27,8,8,0,0,1-7.72-5.93l-3.8-14.15L33.11,188A8,8,0,0,0,40,200H88A8,8,0,0,1,96,208Zm140.73-28-23.14-40a8,8,0,0,0-13.84,8l23.14,40A8,8,0,0,1,216,200H147.31l10.34-10.34a8,8,0,0,0-11.31-11.32l-24,24a8,8,0,0,0,0,11.32l24,24a8,8,0,0,0,11.31-11.32L147.31,216H216a24,24,0,0,0,20.77-36ZM128,32a7.85,7.85,0,0,1,6.92,4l34.29,59.25-14.08-3.78A8,8,0,0,0,151,106.92l32.78,8.79a8.23,8.23,0,0,0,2.07.27,8,8,0,0,0,7.72-5.93l8.79-32.79a8,8,0,1,0-15.45-4.14l-3.8,14.17L148.77,28a24,24,0,0,0-41.54,0L84.07,68a8,8,0,0,0,13.85,8l23.16-40A7.85,7.85,0,0,1,128,32Z',
            ],
            [
                'rute' => 'warga.riwayat',
                'label' => 'Riwayat',
                'ikon' => 'M136,80v43.47l36.12,21.67a8,8,0,0,1-8.24,13.72l-40-24A8,8,0,0,1,120,128V80a8,8,0,0,1,16,0Zm-8-48A95.44,95.44,0,0,0,60.08,60.15C52.81,67.51,46.35,74.59,40,82V64a8,8,0,0,0-16,0v40a8,8,0,0,0,8,8H72a8,8,0,0,0,0-16H49c7.15-8.42,14.27-16.35,22.39-24.57a80,80,0,1,1,1.66,114.75,8,8,0,1,0-11,11.64A96,96,0,1,0,128,32Z',
            ],
            [
                'rute' => 'warga.edukasi',
                'label' => 'Edukasi',
                'ikon' => 'M232,48H160a40,40,0,0,0-32,16A40,40,0,0,0,96,48H24a8,8,0,0,0-8,8V200a8,8,0,0,0,8,8H96a24,24,0,0,1,24,24,8,8,0,0,0,16,0,24,24,0,0,1,24-24h72a8,8,0,0,0,8-8V56A8,8,0,0,0,232,48ZM96,192H32V64H96a24,24,0,0,1,24,24V200A39.81,39.81,0,0,0,96,192Zm128,0H160a39.81,39.81,0,0,0-24,8V88a24,24,0,0,1,24-24h64ZM160,88h40a8,8,0,0,1,0,16H160a8,8,0,0,1,0-16Zm48,40a8,8,0,0,1-8,8H160a8,8,0,0,1,0-16h40A8,8,0,0,1,208,128Zm0,32a8,8,0,0,1-8,8H160a8,8,0,0,1,0-16h40A8,8,0,0,1,208,160Z',
            ],
        ];
    @endphp

    <header class="border-b border-stone bg-bone md:sticky md:top-0 md:z-40">
        <div class="mx-auto flex min-h-16 w-full max-w-[560px] items-center justify-between gap-4 px-4 md:max-w-[1100px] md:px-6">
            <div class="flex min-h-16 items-center gap-6">
                <a href="{{ route('warga.beranda') }}" class="inline-flex min-h-11 items-center font-display text-[20px] font-medium leading-none text-forest">
                    Desa Sirkular
                </a>
                <nav aria-label="Navigasi warga" class="hidden self-stretch md:flex">
                    @foreach ($menuWarga as $menu)
                        @php
                            $aktif = $ruteAktif === $menu['rute']
                                || ($menu['rute'] === 'warga.setor' && $ruteAktif === 'warga.estimasi');
                            $current = $menu['rute'] === 'warga.setor' && $ruteAktif === 'warga.estimasi'
                                ? 'step'
                                : 'page';
                        @endphp
                        <a
                            href="{{ route($menu['rute']) }}"
                            @if ($aktif) aria-current="{{ $current }}" @endif
                            class="flex min-h-16 items-center border-b-2 px-3 text-[13px] font-medium leading-none {{ $aktif ? 'border-forest text-forest' : 'border-transparent text-muted hover:text-forest' }}"
                        >
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <p class="text-right text-[12px] leading-[1.4] text-muted">
                    <span class="block font-medium text-ink">{{ auth()->user()?->nama ?? 'Warga' }}</span>
                    <span class="md:hidden lg:inline">Catatan warga</span>
                </p>
                @include('partials.keluar')
            </div>
        </div>
    </header>

    <main id="konten" tabindex="-1" class="mx-auto w-full max-w-[560px] flex-1 px-4 pb-[calc(6rem_+_env(safe-area-inset-bottom))] pt-6 md:pb-16 md:pt-8">
        @include('partials.flash')
        @yield('content')
    </main>

    <nav aria-label="Navigasi utama" class="nav-bawah-warga fixed inset-x-0 bottom-0 z-40 border-t border-stone bg-bone pb-[env(safe-area-inset-bottom)] pl-[env(safe-area-inset-left)] pr-[env(safe-area-inset-right)] md:hidden">
        <ul class="mx-auto grid w-full max-w-[560px] grid-cols-4 gap-1 px-1">
            @foreach ($menuWarga as $menu)
                @php
                    $aktif = $ruteAktif === $menu['rute']
                        || ($menu['rute'] === 'warga.setor' && $ruteAktif === 'warga.estimasi');
                    $current = $menu['rute'] === 'warga.setor' && $ruteAktif === 'warga.estimasi'
                        ? 'step'
                        : 'page';
                @endphp
                <li>
                    <a
                        href="{{ route($menu['rute']) }}"
                        @if ($aktif) aria-current="{{ $current }}" @endif
                        class="flex min-h-16 flex-col items-center justify-center gap-1 border-t-2 px-1 text-[12px] font-medium leading-[1.2] focus-visible:outline-offset-[-2px] {{ $aktif ? 'border-forest text-forest' : 'border-transparent text-muted hover:border-moss hover:text-forest' }}"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
                            <path d="{{ $menu['ikon'] }}" />
                        </svg>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</body>
</html>
