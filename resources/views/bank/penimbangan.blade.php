@extends('layouts.bank')

@section('title', 'Antrean penimbangan, Bank Sampah Sumber Rejeki')
@section('description', 'Antrean setoran sampah yang menunggu ditimbang untuk Pengelola Bank Sampah Sumber Rejeki, Desa Sukamaju.')

@use('App\Support\Angka')

@php
    $gagalId = session('penimbangan_id');
    $gagalAdaDiAntrean = $gagalId !== null && $antrean->contains(fn ($setoran) => $setoran->id === (int) $gagalId);
@endphp

@section('content')
    @if (session('status'))
        <div role="status" class="mb-6 border border-stone border-l-[3px] border-l-berhasil bg-paper px-4 py-3">
            <p class="text-[14px] leading-[1.6] text-ink">{{ session('status') }}</p>
        </div>
    @endif

    @if (session('peringatan'))
        <div role="status" class="mb-6 border border-stone border-l-[3px] border-l-mustard bg-paper px-4 py-3">
            <p class="text-[14px] leading-[1.6] text-ink">{{ session('peringatan') }}</p>
        </div>
    @endif

    @if ($errors->any() && ! $gagalAdaDiAntrean)
        <div role="alert" class="mb-6 border border-stone border-l-[3px] border-l-brick bg-paper px-4 py-3">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">Hasil timbang belum tersimpan. Periksa isian berikut.</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-3 border-b border-stone pb-4">
        <div>
            <h1 class="font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Antrean penimbangan</h1>
            <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
                Setoran yang menunggu timbang, dari yang paling lama masuk. Pilih satu baris, masukkan angka timbangan, lalu simpan.
            </p>
        </div>
        <p class="text-[13px] leading-[1.4] text-muted">
            {{ now()->locale('id')->translatedFormat('l, j F Y') }}
        </p>
    </div>

    @if (! $antreanError)
        <dl class="mt-6 flex flex-wrap items-baseline gap-x-8 gap-y-2 border-b border-stone pb-4">
            <div class="flex items-baseline gap-2">
                <dt class="text-[13px] leading-[1.4] text-muted">Menunggu timbang</dt>
                <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ $ringkasan['jumlah'] }} setoran</dd>
            </div>
            <div class="flex items-baseline gap-2">
                <dt class="text-[13px] leading-[1.4] text-muted">Berat tercatat</dt>
                <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ Angka::kg($ringkasan['berat']) }}</dd>
            </div>
            <div class="flex items-baseline gap-2">
                <dt class="text-[13px] leading-[1.4] text-muted">Perkiraan nilai</dt>
                <dd class="font-mono text-[24px] font-medium leading-none text-ochre">{{ Angka::rupiah($ringkasan['nilai']) }}</dd>
            </div>
            <div class="flex items-baseline gap-2 sm:ml-auto">
                <dt class="text-[13px] leading-[1.4] text-muted">Sudah ditimbang hari ini</dt>
                <dd class="font-mono text-[14px] leading-[1.4] text-ink">{{ $sudahDitimbangHariIni }} setoran</dd>
            </div>
        </dl>
    @endif

    @if ($antreanError)
        <div role="alert" class="mt-6 border border-stone bg-paper px-4 py-5 md:px-5">
            <p class="text-[15px] font-medium leading-[1.5] text-ink">Antrean belum bisa ditampilkan.</p>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $antreanError }}</p>
            <a href="{{ route('bank.penimbangan') }}" class="mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">Muat ulang halaman</a>
        </div>
    @elseif ($antrean->isEmpty())
        <div class="mt-6 border border-stone bg-paper px-4 py-6 md:px-5">
            <p class="text-[15px] font-medium leading-[1.5] text-ink">Antrean penimbangan kosong.</p>
            <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">
                Semua setoran sudah ditimbang. Setoran baru muncul di sini setelah dicatat di halaman Catat setoran.
            </p>
            <a href="{{ route('bank.penerimaan') }}" class="mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">Catat setoran baru</a>
        </div>
    @else
        <div class="hidden md:block">
            <table class="w-full text-left text-[14px]">
                <caption class="sr-only">Setoran sampah yang menunggu ditimbang, urut dari yang paling lama masuk</caption>
                <thead>
                    <tr class="border-b border-stone">
                        <th scope="col" class="py-2 pr-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Masuk</th>
                        <th scope="col" class="py-2 pr-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Warga</th>
                        <th scope="col" class="py-2 pr-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Jenis</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Berat tercatat</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Nilai perkiraan</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($antrean as $setoran)
                        @php
                            $detail = $setoran->detailSampah->first();
                            $tertunda = $setoran->created_at->lt(now()->subDay());
                        @endphp
                        <tr class="h-11 border-b border-stone align-middle hover:bg-[#F0EDE4]">
                            <td class="py-2 pr-3 font-mono text-[12px] leading-[1.4] text-muted @if ($tertunda) border-l-[3px] border-l-mustard @endif">
                                @if ($tertunda)
                                    <span class="sr-only">Menunggu lebih dari sehari, </span>
                                @endif
                                {{ $setoran->created_at->isToday() ? $setoran->created_at->format('H:i') : $setoran->created_at->locale('id')->translatedFormat('j M, H:i') }}
                            </td>
                            <td class="py-2 pr-3 text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->user?->name ?? 'Warga' }}</td>
                            <td class="py-2 pr-3 text-[13px] leading-[1.4] text-muted">{{ $detail?->jenis_sampah ?? 'Campuran' }}</td>
                            <td class="py-2 pl-3 text-right font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}</td>
                            <td class="py-2 pl-3 text-right font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</td>
                            <td class="py-2 pl-3 text-right">
                                <button
                                    type="button"
                                    data-buka-timbang="dialog-timbang-{{ $setoran->id }}"
                                    aria-haspopup="dialog"
                                    class="inline-flex h-11 items-center justify-center rounded-control border border-forest px-4 text-[13px] font-medium text-forest hover:bg-forest hover:text-white active:translate-y-px"
                                >
                                    Timbang
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <ul class="md:hidden">
            @foreach ($antrean as $setoran)
                @php
                    $detail = $setoran->detailSampah->first();
                    $tertunda = $setoran->created_at->lt(now()->subDay());
                @endphp
                <li class="border-b border-stone py-3 @if ($tertunda) border-l-[3px] border-l-mustard pl-3 @endif">
                    @if ($tertunda)
                        <p class="text-[12px] leading-[1.4] text-muted">Menunggu lebih dari sehari</p>
                    @endif
                    <div class="flex items-baseline justify-between gap-3">
                        <p class="text-[15px] font-medium leading-[1.4] text-ink">{{ $setoran->user?->name ?? 'Warga' }}</p>
                        <p class="font-mono text-[15px] leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}</p>
                    </div>
                    <div class="mt-0.5 flex items-baseline justify-between gap-3 text-[12px] leading-[1.4] text-muted">
                        <p>{{ $detail?->jenis_sampah ?? 'Campuran' }}, {{ $setoran->created_at->isToday() ? $setoran->created_at->format('H:i') : $setoran->created_at->locale('id')->translatedFormat('j M, H:i') }}</p>
                        <p class="font-mono text-[14px] text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</p>
                    </div>
                    <button
                        type="button"
                        data-buka-timbang="dialog-timbang-{{ $setoran->id }}"
                        aria-haspopup="dialog"
                        class="mt-2 inline-flex h-11 w-full items-center justify-center rounded-control border border-forest px-4 text-[14px] font-medium text-forest hover:bg-forest hover:text-white active:translate-y-px"
                    >
                        Timbang
                    </button>
                </li>
            @endforeach
        </ul>

        @foreach ($antrean as $setoran)
            @include('bank.partials.dialog-timbang', ['setoran' => $setoran, 'gagalId' => $gagalId])
        @endforeach

        @if ($gagalAdaDiAntrean)
            <p data-buka-otomatis="dialog-timbang-{{ $gagalId }}" hidden></p>
        @endif
    @endif
@endsection
