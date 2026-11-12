@extends('layouts.warga')

@section('title', 'Ajukan setoran, Desa Sirkular')
@section('description', 'Formulir pengajuan setoran sampah warga Desa Sukamaju.')

@section('content')
    <p class="border-t-2 border-t-forest pt-4 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Ajukan setoran</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Isi jenis dan berat sampah. Pengajuan masuk antrean bank sampah untuk ditimbang ulang.
    </p>

    @if ($daftarError)
        <div role="alert" class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Formulir belum bisa dibuka.</h2>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $daftarError }}</p>
            <a href="{{ route('warga.setor') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">
                Muat ulang halaman
            </a>
        </div>
    @elseif ($bankAktif === null)
        <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Bank sampah belum siap.</h2>
            <p class="mt-1 max-w-[48ch] text-[13px] leading-[1.5] text-muted">
                Pengajuan belum bisa dikirim karena belum ada bank sampah aktif. Coba lagi nanti atau hubungi pengelola bank sampah.
            </p>
            <a href="{{ route('warga.beranda') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">
                Kembali ke beranda
            </a>
        </div>
    @elseif ($wargaAktif === null)
        <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Data warga belum tersedia.</h2>
            <p class="mt-1 max-w-[48ch] text-[13px] leading-[1.5] text-muted">
                Pengajuan belum bisa dicatat karena data warga belum tersedia. Hubungi pengelola bank sampah.
            </p>
            <a href="{{ route('warga.beranda') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">
                Kembali ke beranda
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
        <section aria-labelledby="judul-form" class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
            <div class="border-b border-stone pb-4">
                <p class="font-mono text-[11px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Pengajuan untuk</p>
                <p class="mt-1 text-[15px] font-medium leading-[1.4] text-ink">{{ $wargaAktif->user?->nama ?? 'Warga' }}</p>
            </div>

            <h2 id="judul-form" class="mt-4 text-[17px] font-semibold leading-[1.4] text-ink">Jenis dan berat</h2>

            @if ($errors->any())
                <div role="alert" class="mt-4 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
                    <p class="text-[14px] font-medium leading-[1.5] text-ink">Pengajuan belum terkirim. Periksa isian berikut.</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('warga.setor.store') }}" class="mt-3 space-y-3" data-estimasi-warga data-pengajuan-form>
                @csrf

                <div>
                    <label for="jenis_sampah" class="label-form">Jenis sampah</label>
                    <select
                        id="jenis_sampah"
                        name="jenis_sampah_id"
                        data-jenis-sampah
                        required
                        @error('jenis_sampah_id') aria-invalid="true" aria-describedby="error-jenis_sampah_id" @else aria-describedby="harga-terpilih" @enderror
                        class="mt-2 h-11 w-full rounded-control border bg-paper px-3 text-[15px] text-ink @error('jenis_sampah_id') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                    >
                        <option value="" disabled @selected(old('jenis_sampah_id') === null)>Pilih jenis sampah</option>
                        @foreach ($daftarJenis as $jenis)
                            <option
                                value="{{ $jenis->id }}"
                                data-harga="{{ (int) $jenis->harga_per_kg }}"
                                data-satuan="{{ $jenis->satuan }}"
                                @selected((string) old('jenis_sampah_id') === (string) $jenis->id)
                            >
                                {{ $jenis->nama_sampah }}, {{ $jenis->kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_sampah_id')
                        <p id="error-jenis_sampah_id" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                    @else
                        <p id="harga-terpilih" data-harga-terpilih class="mt-1 font-mono text-[13px] leading-[1.4] text-muted">
                            Pilih jenis untuk melihat harga acuan.
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="berat" class="label-form">Berat</label>
                    <div class="relative mt-2">
                        <input
                            id="berat"
                            name="berat"
                            data-berat
                            type="text"
                            inputmode="decimal"
                            autocomplete="off"
                            required
                            value="{{ old('berat') }}"
                            placeholder="0"
                            @error('berat') aria-invalid="true" aria-describedby="error-berat" @else aria-describedby="hint-berat" @enderror
                            class="h-14 w-full rounded-control border bg-paper pl-3 pr-12 text-right font-mono text-[28px] font-medium leading-none text-ink placeholder:text-muted @error('berat') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                        >
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 font-mono text-[14px] text-muted" aria-hidden="true">kg</span>
                    </div>
                    @error('berat')
                        <p id="error-berat" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                    @else
                        <p id="hint-berat" class="mt-1 text-[12px] leading-[1.4] text-muted">Pakai koma untuk desimal, contoh 2,5. Maksimal 500 kg.</p>
                    @enderror
                </div>

                <div class="border-t border-stone pt-4">
                    <label for="catatan" class="label-form">Catatan</label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="3"
                        maxlength="500"
                        @error('catatan') aria-invalid="true" aria-describedby="error-catatan" @else aria-describedby="hint-catatan" @enderror
                        class="mt-2 min-h-[88px] w-full rounded-control border bg-paper px-3 py-2 text-[15px] leading-[1.6] text-ink @error('catatan') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                    >{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p id="error-catatan" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                    @else
                        <p id="hint-catatan" class="mt-1 text-[12px] leading-[1.4] text-muted">Opsional, misal: botol sudah dipilah dan dikeringkan.</p>
                    @enderror
                </div>

                <div class="border-t border-stone pt-4">
                    <p class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Perkiraan nilai setoran</p>
                    <p class="mt-1 text-right" aria-live="polite" aria-atomic="true">
                        <span class="sr-only">Perkiraan nilai setoran: </span>
                        <span data-estimasi-angka data-aktif="false" class="inline-block border-b-2 border-stone pb-1 font-mono text-[28px] font-medium leading-[1.1] text-muted data-[aktif=true]:border-ochre data-[aktif=true]:text-ochre">Rp 0</span>
                    </p>
                    <p data-rincian-estimasi class="mt-1 text-right font-mono text-[13px] leading-[1.4] text-muted">Pilih jenis sampah dulu.</p>
                    <p class="mt-2 text-[12px] leading-[1.5] text-muted">Perkiraan dari harga acuan. Nilai akhir mengikuti hasil timbang.</p>
                </div>

                <div class="border-t border-stone pt-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex min-h-7 items-center rounded-full bg-mustard/15 px-3 font-mono text-[11px] uppercase leading-[1.4] tracking-[0.06em] text-ink">Menunggu timbang</span>
                        <p class="text-[12px] leading-[1.4] text-muted">Status awal setelah pengajuan dikirim.</p>
                    </div>

                    <button type="submit" data-submit class="mt-3 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white transition-colors duration-150 hover:bg-forest-hover active:translate-y-px disabled:bg-forest/60">
                        <span data-submit-label>Kirim pengajuan</span>
                    </button>
                    <p data-status-pengajuan role="status" class="sr-only"></p>
                    <p class="mt-2 text-[12px] leading-[1.5] text-muted">Bawa sampah ke {{ $bankAktif->nama_bank }} untuk ditimbang. Nilai akhir mengikuti hasil timbang.</p>
                </div>
            </form>
        </section>
    @endif
@endsection
