@extends('layouts.publik')

@section('title', 'Setoran '.$setoran->kode_setoran.', Desa Sirkular')
@section('description', 'Perjalanan setoran '.$setoran->kode_setoran.' di Desa Sukamaju: disetor warga, ditimbang bank sampah, dan masuk tabungan.')

@use('App\Support\Angka')
@use('App\Models\Setoran')

@php
    $status = $setoran->status;
    $menunggu = $status === Setoran::STATUS_MENUNGGU;
    $ditimbang = in_array($status, [Setoran::STATUS_DITIMBANG, Setoran::STATUS_DIKONFIRMASI], true);
    $dikonfirmasi = $status === Setoran::STATUS_DIKONFIRMASI;
    $dibatalkan = $status === Setoran::STATUS_DIBATALKAN;

    $chipStatus = [
        Setoran::STATUS_MENUNGGU => 'bg-mustard/15',
        Setoran::STATUS_DITIMBANG => 'bg-moss/15',
        Setoran::STATUS_DIKONFIRMASI => 'bg-berhasil/15',
        Setoran::STATUS_DIBATALKAN => 'bg-brick/15',
    ];

    $captionRingkasan = match ($status) {
        Setoran::STATUS_MENUNGGU => 'Angka di atas masih perkiraan dari waktu setoran. Berat final diisi saat penimbangan di bank sampah.',
        Setoran::STATUS_DITIMBANG => 'Berat final sudah ditimbang. Nilai masuk tabungan setelah dikonfirmasi bank sampah.',
        Setoran::STATUS_DIKONFIRMASI => 'Nilai setoran sudah masuk saldo tabungan warga.',
        default => 'Setoran dibatalkan sebelum ditimbang. Angka di atas hanya catatan setoran awal.',
    };
@endphp

@section('content')
    <div class="border-b border-stone pb-6 md:pb-8">
        <h1 class="font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Perjalanan setoran sampah</h1>

        <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2">
            <p class="font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ $setoran->kode_setoran }}</p>
            <span class="inline-flex min-h-7 items-center rounded-full {{ $chipStatus[$status] ?? 'bg-stone/40' }} px-3 text-[12px] font-medium leading-[1.4] text-ink">{{ $setoran->labelStatus() }}</span>
        </div>

        <p class="mt-3 text-[13px] leading-[1.4] text-muted">
            {{ $setoran->bankSampah?->nama_bank ?? 'Bank sampah' }}, {{ $setoran->tanggal_setoran->locale('id')->translatedFormat('j F Y') }}
        </p>
    </div>

    <section aria-labelledby="judul-tahapan" class="mt-8 md:mt-10">
        <h2 id="judul-tahapan" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Perjalanan sampah ini</h2>

        <ol class="mt-4 rounded-card border border-stone bg-paper p-4 md:mt-6 md:p-5">
            <li class="grid grid-cols-[32px_1fr] gap-x-4 md:grid-cols-[40px_1fr]">
                <div class="flex flex-col items-center">
                    <span class="font-mono text-[13px] font-medium leading-[1.5] text-forest">01</span>
                    <span class="mt-1 w-px flex-1 bg-stone" aria-hidden="true"></span>
                </div>
                <div class="pb-6 md:pb-8">
                    <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">Disetor oleh warga</h3>
                    <dl class="mt-3 space-y-1.5">
                        <div class="flex items-baseline justify-between gap-x-4">
                            <dt class="text-[13px] leading-[1.4] text-muted">Warga</dt>
                            <dd class="text-right text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->masyarakat?->user?->nama ?? 'Warga' }}</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-x-4">
                            <dt class="text-[13px] leading-[1.4] text-muted">Tanggal</dt>
                            <dd class="text-right text-[14px] leading-[1.4] text-ink">{{ $setoran->tanggal_setoran->locale('id')->translatedFormat('j F Y') }}</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-x-4">
                            <dt class="text-[13px] leading-[1.4] text-muted">Jenis sampah</dt>
                            <dd class="text-right text-[14px] leading-[1.4] text-ink">{{ $setoran->detailSampah->first()?->jenisSampah?->nama_sampah ?? 'Campuran' }}</dd>
                        </div>
                        @if ($menunggu)
                            <div class="flex items-baseline justify-between gap-x-4">
                                <dt class="text-[13px] leading-[1.4] text-muted">Perkiraan setoran</dt>
                                <dd class="text-right font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}, {{ Angka::rupiah($setoran->total_nilai) }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </li>

            @if ($dibatalkan)
                <li class="grid grid-cols-[32px_1fr] gap-x-4 md:grid-cols-[40px_1fr]">
                    <div class="flex flex-col items-center">
                        <span class="font-mono text-[13px] font-medium leading-[1.5] text-brick">02</span>
                    </div>
                    <div>
                        <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">Dibatalkan</h3>
                        @if ($setoran->catatan)
                            <p class="mt-2 max-w-[48ch] text-[14px] leading-[1.6] text-muted">{{ $setoran->catatan }}</p>
                        @endif
                        <p class="mt-2 max-w-[48ch] text-[14px] leading-[1.6] text-muted">Tahap penimbangan dan setelahnya tidak dilanjutkan.</p>
                    </div>
                </li>
            @else
                <li class="grid grid-cols-[32px_1fr] gap-x-4 md:grid-cols-[40px_1fr]">
                    <div class="flex flex-col items-center">
                        <span class="font-mono text-[13px] font-medium leading-[1.5] {{ $ditimbang ? 'text-forest' : 'text-muted' }}">02</span>
                        <span class="mt-1 w-px flex-1 bg-stone" aria-hidden="true"></span>
                    </div>
                    <div class="pb-6 md:pb-8">
                        @if ($ditimbang)
                            <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">Ditimbang oleh bank sampah</h3>
                            <dl class="mt-3 space-y-1.5">
                                <div class="flex items-baseline justify-between gap-x-4">
                                    <dt class="text-[13px] leading-[1.4] text-muted">Bank sampah</dt>
                                    <dd class="text-right text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->bankSampah?->nama_bank ?? 'Bank sampah' }}</dd>
                                </div>
                                <div class="flex items-baseline justify-between gap-x-4">
                                    <dt class="text-[13px] leading-[1.4] text-muted">Berat final</dt>
                                    <dd class="text-right font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}</dd>
                                </div>
                                @if ($setoran->petugas !== null)
                                    <div class="flex items-baseline justify-between gap-x-4">
                                        <dt class="text-[13px] leading-[1.4] text-muted">Petugas</dt>
                                        <dd class="text-right text-[14px] leading-[1.4] text-ink">{{ $setoran->petugas->nama }}</dd>
                                    </div>
                                @endif
                            </dl>
                        @else
                            <h3 class="text-[17px] font-medium leading-[1.4] text-muted">Ditimbang oleh bank sampah</h3>
                            <p class="mt-2 max-w-[48ch] text-[14px] leading-[1.6] text-muted">Menunggu penimbangan oleh petugas bank sampah.</p>
                        @endif
                    </div>
                </li>

                <li class="grid grid-cols-[32px_1fr] gap-x-4 md:grid-cols-[40px_1fr]">
                    <div class="flex flex-col items-center">
                        <span class="font-mono text-[13px] font-medium leading-[1.5] {{ $dikonfirmasi ? 'text-forest' : 'text-muted' }}">03</span>
                        <span class="mt-1 w-px flex-1 bg-stone" aria-hidden="true"></span>
                    </div>
                    <div class="pb-6 md:pb-8">
                        @if ($dikonfirmasi)
                            <h3 class="text-[17px] font-semibold leading-[1.4] text-ink">Dikonfirmasi masuk tabungan</h3>
                            <dl class="mt-3 space-y-1.5">
                                <div class="flex items-baseline justify-between gap-x-4">
                                    <dt class="text-[13px] leading-[1.4] text-muted">Nilai masuk saldo</dt>
                                    <dd class="text-right font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::rupiah($setoran->mutasiSaldo?->nominal ?? $setoran->total_nilai) }}</dd>
                                </div>
                                <div class="flex items-baseline justify-between gap-x-4">
                                    <dt class="text-[13px] leading-[1.4] text-muted">Tanggal</dt>
                                    <dd class="text-right text-[14px] leading-[1.4] text-ink">{{ ($setoran->mutasiSaldo?->created_at ?? $setoran->updated_at)->locale('id')->translatedFormat('j F Y') }}</dd>
                                </div>
                            </dl>
                        @else
                            <h3 class="text-[17px] font-medium leading-[1.4] text-muted">Dikonfirmasi masuk tabungan</h3>
                            <p class="mt-2 max-w-[48ch] text-[14px] leading-[1.6] text-muted">Menunggu konfirmasi bank sampah. Setelah dikonfirmasi, nilai setoran masuk saldo tabungan warga.</p>
                        @endif
                    </div>
                </li>

                <li class="grid grid-cols-[32px_1fr] gap-x-4 md:grid-cols-[40px_1fr]">
                    <div class="flex flex-col items-center">
                        <span class="font-mono text-[13px] font-medium leading-[1.5] text-muted">04</span>
                    </div>
                    <div>
                        <h3 class="text-[17px] font-medium leading-[1.4] text-muted">Dijual ke mitra</h3>
                        <p class="mt-2 max-w-[48ch] text-[14px] leading-[1.6] text-muted">
                            Menunggu mitra daur ulang. Setelah stok terjual, tahap ini berisi nama mitra dan berat yang dibeli.
                        </p>
                    </div>
                </li>
            @endif
        </ol>
    </section>

    <section aria-labelledby="judul-ringkasan" class="mt-10 md:mt-12">
        <h2 id="judul-ringkasan" class="sr-only">Ringkasan setoran</h2>

        <dl class="grid grid-cols-1 border-y border-stone md:grid-cols-2 md:divide-x md:divide-stone">
            <div class="py-4 md:py-5 md:pr-6">
                <dt class="text-[13px] leading-[1.4] text-muted">Berat sampah tercatat</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none text-ink md:text-[32px]">{{ Angka::kg($setoran->total_berat) }}</dd>
            </div>
            <div class="border-t border-stone py-4 md:border-t-0 md:py-5 md:pl-6">
                <dt class="text-[13px] leading-[1.4] text-muted">Nilai ekonomi setoran</dt>
                <dd class="mt-1 font-mono text-[28px] font-medium leading-none {{ $dibatalkan ? 'text-ink' : 'text-ochre' }} md:text-[32px]">{{ Angka::rupiah($setoran->total_nilai) }}</dd>
            </div>
        </dl>

        <p class="mt-2 text-[12px] leading-[1.4] text-muted">{{ $captionRingkasan }}</p>

        <a href="{{ route('tracking.form') }}" class="mt-6 inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">
            Lacak kode setoran lain
        </a>
    </section>
@endsection
