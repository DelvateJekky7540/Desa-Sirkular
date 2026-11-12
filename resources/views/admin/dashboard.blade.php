@extends('layouts.admin')

@section('title', 'Laporan desa, Desa Sirkular')
@section('description', 'Rekap sampah dan nilai ekonomi dari seluruh bank sampah di Desa Sukamaju.')

@use('App\Support\Angka')
@use('App\Models\Setoran')

@php
    $chipStatus = [
        Setoran::STATUS_MENUNGGU => 'bg-mustard/15',
        Setoran::STATUS_DITIMBANG => 'bg-moss/15',
        Setoran::STATUS_DIKONFIRMASI => 'bg-berhasil/15',
        Setoran::STATUS_DIBATALKAN => 'bg-brick/15',
    ];
@endphp

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-3 border-t-2 border-t-forest border-b border-b-stone pt-4 pb-4">
        <div>
            <p class="mb-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Monitoring bank sampah, Desa Sukamaju</p>
            <h1 class="font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Laporan desa</h1>
            <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
                Rekap pencatatan sampah dari seluruh bank sampah di Desa Sukamaju, tanpa perlu membuka satu per satu.
            </p>
        </div>
        <p class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">
            {{ now()->locale('id')->translatedFormat('l, j F Y') }}
        </p>
    </div>

    @if ($dataError)
        <div role="alert" class="mt-6 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">Laporan belum bisa ditampilkan.</p>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $dataError }}</p>
            <a href="{{ route('admin.dashboard') }}" class="tautan-garis mt-2 inline-flex h-11 items-center text-[13px] font-medium text-forest">Muat ulang halaman</a>
        </div>
    @else
        <dl class="mt-6 grid grid-cols-1 border-y border-stone md:grid-cols-3 md:divide-x md:divide-stone">
            <div class="py-4 md:py-5 md:pr-8">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Total sampah tercatat</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ Angka::kg($ringkasan['berat']) }}</dd>
            </div>
            <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:px-8">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Sudah dikonfirmasi, masuk tabungan</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ Angka::kg($ringkasan['beratKonfirmasi']) }}</dd>
            </div>
            <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:pl-8">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Nilai ekonomi setoran</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ochre md:text-[32px]">{{ Angka::rupiah($ringkasan['nilai']) }}</dd>
            </div>
        </dl>
        <p class="mt-2 text-[12px] leading-[1.4] text-muted">
            Angka daur ulang belum dihitung: penjualan stok ke mitra daur ulang belum dicatat di sistem.
        </p>

        <div class="judul-seksi mt-8 flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 border-b border-b-stone pb-3">
            <h2 class="font-display text-[20px] font-semibold leading-[1.3] text-ink md:text-[24px]">Setoran terbaru</h2>
            <p class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">10 setoran terakhir</p>
        </div>

        @if ($setoranTerbaru->isEmpty())
            <div class="mt-4 border border-stone bg-bone px-4 py-5">
                <p class="text-[14px] font-medium leading-[1.5] text-ink">Belum ada setoran tercatat.</p>
                <p class="mt-1 text-[13px] leading-[1.5] text-muted">Tabel ini terisi setelah warga menyetor sampah dan petugas menimbangnya di bank sampah.</p>
            </div>
        @else
            <div class="hidden md:block">
                <table class="w-full text-left text-[14px]">
                    <caption class="sr-only">Sepuluh setoran terbaru dari seluruh bank sampah</caption>
                    <thead>
                        <tr class="border-b border-stone">
                            <th scope="col" class="py-2 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Tanggal</th>
                            <th scope="col" class="py-2 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Warga</th>
                            <th scope="col" class="py-2 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Bank sampah</th>
                            <th scope="col" class="py-2 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Berat</th>
                            <th scope="col" class="py-2 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Nilai</th>
                            <th scope="col" class="py-2 pl-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($setoranTerbaru as $setoran)
                            <tr class="h-12 border-b border-stone align-middle transition-colors hover:bg-[#F0EDE4]">
                                <td class="py-2 pr-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.06em] text-muted">{{ $setoran->tanggal_setoran->locale('id')->translatedFormat('j M Y') }}</td>
                                <td class="py-2 pr-2 text-[14px] leading-[1.4] text-ink">{{ $setoran->masyarakat?->user?->nama ?? 'Warga' }}</td>
                                <td class="py-2 pr-2 text-[13px] leading-[1.4] text-muted">{{ $setoran->bankSampah?->nama_bank ?? 'Bank sampah' }}</td>
                                <td class="py-2 pl-2 text-right font-mono text-[15px] font-medium text-ink">{{ Angka::kg($setoran->total_berat) }}</td>
                                <td class="py-2 pl-2 text-right font-mono text-[15px] font-medium text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</td>
                                <td class="py-2 pl-3 pr-2">
                                    <span class="inline-flex min-h-7 items-center rounded-full {{ $chipStatus[$setoran->status] ?? 'bg-stone/40' }} px-3 font-mono text-[11px] uppercase leading-[1.4] tracking-[0.06em] text-ink">{{ $setoran->labelStatus() }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <ul class="md:hidden">
                @foreach ($setoranTerbaru as $setoran)
                    <li class="border-b border-stone py-4 transition-colors hover:bg-[#F0EDE4]">
                        <div class="flex items-baseline justify-between gap-3">
                            <p class="text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->masyarakat?->user?->nama ?? 'Warga' }}</p>
                            <p class="font-mono text-[15px] font-medium leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}</p>
                        </div>
                        <div class="mt-0.5 flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1 text-[12px] leading-[1.4] text-muted">
                            <p class="font-mono text-[11px] uppercase tracking-[0.08em]">{{ $setoran->tanggal_setoran->locale('id')->translatedFormat('j M Y') }}, {{ $setoran->bankSampah?->nama_bank ?? 'Bank sampah' }}</p>
                            <p class="font-mono text-[15px] font-medium text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</p>
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex min-h-7 items-center rounded-full {{ $chipStatus[$setoran->status] ?? 'bg-stone/40' }} px-3 font-mono text-[11px] uppercase leading-[1.4] tracking-[0.06em] text-ink">{{ $setoran->labelStatus() }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
@endsection
