@extends('layouts.warga')

@section('title', 'Estimasi nilai sampah, Desa Sirkular')
@section('description', 'Hitung perkiraan nilai setoran sampah dari harga acuan bank sampah Desa Sukamaju.')

@use('App\Support\Angka')

@section('content')
    <p class="border-t-2 border-t-forest pt-4 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Estimasi nilai sampah</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Isi jenis dan berat sampah. Perkiraan nilai muncul dari harga acuan bank sampah, sebelum setoran ditimbang ulang.
    </p>

    @if ($daftarError)
        <div role="alert" class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Harga acuan belum bisa dimuat.</h2>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $daftarError }}</p>
            <a href="{{ route('warga.estimasi') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">
                Muat ulang halaman
            </a>
        </div>
    @elseif ($daftarJenis->isEmpty())
        <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Harga acuan belum tersedia.</h2>
            <p class="mt-1 max-w-[48ch] text-[13px] leading-[1.5] text-muted">
                Bank sampah belum menerbitkan harga untuk jenis sampah aktif. Coba lagi nanti atau hubungi pengelola bank sampah.
            </p>
            <a href="{{ route('warga.beranda') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">
                Kembali ke beranda
            </a>
        </div>
    @else
        <section aria-labelledby="judul-form" data-estimasi-warga class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 id="judul-form" class="judul-seksi text-[17px] font-semibold leading-[1.4] text-ink">Jenis dan berat</h2>

            <div class="mt-3 space-y-3">
                <div>
                    <label for="jenis_sampah" class="label-form">Jenis sampah</label>
                    <select
                        id="jenis_sampah"
                        data-jenis-sampah
                        aria-describedby="harga-terpilih"
                        class="mt-2 h-11 w-full rounded-control border border-stone bg-paper px-3 text-[15px] text-ink hover:border-moss focus:border-forest"
                    >
                        <option value="" selected>Pilih jenis sampah</option>
                        @foreach ($daftarJenis as $jenis)
                            <option
                                value="{{ $jenis->id }}"
                                data-harga="{{ (int) $jenis->harga_per_kg }}"
                                data-satuan="{{ $jenis->satuan }}"
                            >
                                {{ $jenis->nama_sampah }}, {{ $jenis->kategori }}
                            </option>
                        @endforeach
                    </select>
                    <p id="harga-terpilih" data-harga-terpilih class="mt-1 font-mono text-[13px] leading-[1.4] text-muted">
                        Pilih jenis untuk melihat harga acuan.
                    </p>
                </div>

                <div>
                    <label for="berat" class="label-form">Berat</label>
                    <div class="relative mt-2">
                        <input
                            id="berat"
                            data-berat
                            type="text"
                            inputmode="decimal"
                            autocomplete="off"
                            placeholder="0"
                            aria-describedby="hint-berat"
                            class="h-14 w-full rounded-control border border-stone bg-paper pl-3 pr-12 text-right font-mono text-[28px] font-medium leading-none text-ink placeholder:text-muted hover:border-moss focus:border-forest"
                        >
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 font-mono text-[14px] text-muted" aria-hidden="true">kg</span>
                    </div>
                    <p id="hint-berat" class="mt-1 text-[12px] leading-[1.4] text-muted">Pakai koma untuk desimal, contoh 2,5.</p>
                </div>
            </div>

            <div class="mt-4 border-t border-stone pt-4">
                <p class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Perkiraan nilai setoran</p>
                <p data-estimasi-nilai aria-live="polite" aria-atomic="true" class="mt-1 text-right">
                    <span class="sr-only">Perkiraan nilai setoran: </span>
                    <span data-estimasi-angka data-aktif="false" class="inline-block border-b-2 border-stone pb-1 font-mono text-[28px] font-medium leading-[1.1] text-muted md:text-[32px] data-[aktif=true]:border-ochre data-[aktif=true]:text-ochre">Rp 0</span>
                </p>
                <p data-rincian-estimasi class="mt-1 text-right font-mono text-[13px] leading-[1.4] text-muted">
                    Pilih jenis sampah dulu.
                </p>
            </div>

            <a href="{{ route('warga.setor') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white transition-colors duration-150 hover:bg-forest-hover active:translate-y-px">
                Ajukan Setoran
            </a>
            <p class="mt-2 text-[12px] leading-[1.4] text-muted">Nilai akhir mengikuti hasil penimbangan di bank sampah.</p>

            <section aria-labelledby="judul-harga" class="mt-5 border-t-2 border-t-forest pt-5">
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                    <h2 id="judul-harga" class="text-[17px] font-semibold leading-[1.4] text-ink">Harga acuan hari ini</h2>
                    <p class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">{{ $bankAktif?->nama_bank ?? 'Bank sampah Desa Sukamaju' }}</p>
                </div>

                <table class="mt-3 w-full text-left">
                    <caption class="sr-only">Harga acuan per kilogram untuk jenis sampah aktif</caption>
                    <thead>
                        <tr class="border-b border-stone">
                            <th scope="col" class="py-2 pr-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Jenis sampah</th>
                            <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Harga per kg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarJenis as $jenis)
                            <tr class="border-b border-stone align-middle transition-colors hover:bg-[#F0EDE4]">
                                <td class="py-2.5 pr-3">
                                    <span class="block text-[14px] font-medium leading-[1.4] text-ink">{{ $jenis->nama_sampah }}</span>
                                    <span class="block text-[12px] leading-[1.4] text-muted">{{ $jenis->kategori }}</span>
                                </td>
                                <td class="py-2.5 pl-3 text-right whitespace-nowrap">
                                    <span class="font-mono text-[15px] font-medium leading-[1.4] text-ink">{{ Angka::rupiah($jenis->harga_per_kg) }}</span>
                                    <span class="font-mono text-[12px] leading-[1.4] text-muted">/{{ $jenis->satuan }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="mt-3 text-[12px] leading-[1.5] text-muted">Harga per kilogram untuk jenis sampah aktif.</p>
            </section>
        </section>
    @endif
@endsection
