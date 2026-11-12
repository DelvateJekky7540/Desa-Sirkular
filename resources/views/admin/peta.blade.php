@extends('layouts.admin')

@section('title', 'Peta bank sampah, Desa Sirkular')
@section('description', 'Sebaran bank sampah di Desa Sukamaju untuk monitoring Admin Desa, dengan alamat dan tautan katalog tiap bank.')

@php
    $pinPeta = $berkoordinat->map(fn ($bank) => [
        'nama' => $bank->nama_bank,
        'alamat' => $bank->alamat,
        'lat' => (float) $bank->latitude,
        'lng' => (float) $bank->longitude,
        'koordinat' => $bank->latitude.', '.$bank->longitude,
        'katalog' => route('mitra.katalog', ['bank' => $bank->id]),
    ])->values();

    $totalBank = $daftarBank->count();
@endphp

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        .pin-bank {
            background: none;
            border: none;
        }

        .leaflet-container {
            font-family: 'Public Sans', system-ui, sans-serif;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 4px;
            border: 1px solid #E2DED3;
            box-shadow: 0 4px 12px rgba(27, 33, 28, 0.06);
        }

        .leaflet-popup-content {
            margin: 16px;
            line-height: 1.5;
        }

        .peta-popup-judul {
            font-family: Fraunces, Georgia, serif;
            font-weight: 500;
            font-size: 17px;
            line-height: 1.4;
            color: #1B211C;
        }

        .peta-popup-alamat {
            margin-top: 4px;
            font-size: 13px;
            line-height: 1.5;
            color: #5A6357;
        }

        .peta-popup-koordinat {
            margin-top: 4px;
            font-family: 'JetBrains Mono', ui-monospace, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #5A6357;
        }

        .peta-popup-tautan {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #2E4A3B;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .peta-popup-tautan:hover {
            text-decoration-thickness: 2px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
@endpush

@section('content')
    <div class="border-b border-stone pb-4">
        <h1 class="font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Peta bank sampah</h1>
        <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
            Sebaran bank sampah di Desa Sukamaju. Klik pin untuk membuka nama, alamat, dan katalog bahan baku bank tersebut.
        </p>
    </div>

    @if ($dataError)
        <div role="alert" class="mt-6 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">Peta belum bisa ditampilkan.</p>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $dataError }}</p>
            <a href="{{ route('admin.peta') }}" class="mt-2 inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">Muat ulang halaman</a>
        </div>
    @elseif ($totalBank === 0)
        <div class="mt-6 border border-stone bg-bone px-4 py-5">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">Belum ada bank sampah terdaftar.</p>
            <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">Peta terisi setelah bank sampah didaftarkan dan koordinat lokasinya diisi di data bank sampah.</p>
        </div>
    @else
        <dl class="mt-6 grid grid-cols-1 border-y border-stone md:grid-cols-3 md:divide-x md:divide-stone">
            <div class="py-4 md:py-5 md:pr-8">
                <dt class="text-[13px] leading-[1.4] text-muted">Bank sampah terdaftar</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ $totalBank }}</dd>
            </div>
            <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:px-8">
                <dt class="text-[13px] leading-[1.4] text-muted">Sudah berkoordinat, tampil di peta</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ $berkoordinat->count() }}</dd>
            </div>
            <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:pl-8">
                <dt class="text-[13px] leading-[1.4] text-muted">Belum berkoordinat</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ $tanpaKoordinat->count() }}</dd>
            </div>
        </dl>
        <p class="mt-2 text-[12px] leading-[1.4] text-muted">
            Pin hanya muncul untuk bank sampah yang koordinatnya sudah diisi. Bank tanpa koordinat didaftarkan di bawah peta.
        </p>

        <div data-peta aria-label="Peta sebaran bank sampah Desa Sukamaju" class="relative isolate mt-6 h-[500px] w-full overflow-hidden rounded-card border border-stone bg-bone md:h-[640px]">
            <div data-peta-fallback class="absolute inset-0 overflow-auto px-4 py-4 md:px-6">
                <p class="text-[14px] font-medium leading-[1.5] text-ink">Peta belum bisa dimuat.</p>
                <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">
                    Peta butuh koneksi ke layanan peta OpenStreetMap. Lokasi bank sampah tetap bisa dibaca di daftar berikut.
                </p>
                <ul class="mt-3">
                    @foreach ($berkoordinat as $bank)
                        <li class="border-b border-stone py-3">
                            <p class="text-[14px] font-medium leading-[1.4] text-ink">{{ $bank->nama_bank }}</p>
                            <p class="mt-0.5 text-[12px] leading-[1.4] text-muted">{{ $bank->alamat }}</p>
                            <p class="mt-0.5 font-mono text-[12px] leading-[1.4] text-muted">{{ $bank->latitude }}, {{ $bank->longitude }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <script type="application/json" data-peta-data>{!! $pinPeta->toJson(JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>

        @if ($tanpaKoordinat->isNotEmpty())
            <div class="mt-8 flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 border-b border-stone pb-3">
                <h2 class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Belum berkoordinat</h2>
                <p class="text-[12px] leading-[1.4] text-muted">{{ $tanpaKoordinat->count() }} bank sampah</p>
            </div>
            <ul>
                @foreach ($tanpaKoordinat as $bank)
                    <li class="border-b border-stone py-3 md:flex md:items-baseline md:justify-between md:gap-x-4">
                        <div class="min-w-0">
                            <p class="text-[14px] font-medium leading-[1.4] text-ink">{{ $bank->nama_bank }}</p>
                            <p class="mt-0.5 text-[12px] leading-[1.4] text-muted">{{ $bank->alamat }}</p>
                        </div>
                        <div class="mt-1.5 flex items-baseline gap-x-4 md:mt-0 md:shrink-0">
                            <span class="text-[12px] leading-[1.4] text-muted">Koordinat belum diisi</span>
                            <a href="{{ route('mitra.katalog', ['bank' => $bank->id]) }}" class="inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">Lihat katalog</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
@endsection
