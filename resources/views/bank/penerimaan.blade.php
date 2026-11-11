@extends('layouts.bank')

@section('title', 'Catat setoran sampah, Bank Sampah Sumber Rejeki')
@section('description', 'Form penerimaan sampah untuk Pengelola Bank Sampah Sumber Rejeki, Desa Sukamaju.')

@use('App\Support\Angka')

@php
    $beratLama = (float) str_replace(',', '.', (string) old('berat', ''));
    $hargaLama = (float) str_replace('.', '', (string) old('harga_per_kg', ''));
    $estimasiAwal = $beratLama * $hargaLama;
@endphp

@section('content')
    @if (session('status'))
        <div role="status" class="mb-6 border border-stone border-l-[3px] border-l-berhasil bg-paper px-4 py-3">
            <p class="text-[14px] leading-[1.6] text-ink">{{ session('status') }}</p>
        </div>
    @endif

    <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-3 border-b border-stone pb-4">
        <div>
            <h1 class="font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Catat setoran sampah</h1>
            <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
                Timbang sampah warga, isi harga per kilogram, lalu simpan. Setoran baru masuk antrean Menunggu Timbang.
            </p>
        </div>
        <p class="text-[13px] leading-[1.4] text-muted">
            {{ now()->locale('id')->translatedFormat('l, j F Y') }}
        </p>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        <section aria-labelledby="judul-form" class="lg:col-span-7">
            <div class="rounded-card border border-stone bg-paper p-4 md:p-5">
                <h2 id="judul-form" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Form timbang</h2>

                @if ($errors->any())
                    <div role="alert" class="mt-4 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
                        <p class="text-[14px] font-medium leading-[1.5] text-ink">Setoran belum tersimpan. Periksa isian berikut.</p>
                        <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('bank.penerimaan.store') }}" class="mt-4 space-y-3" data-setoran-form>
                    @csrf

                    <div>
                        <label for="user_id" class="block text-[13px] font-medium leading-[1.4] text-ink">Warga</label>
                        @if ($daftarWarga->isEmpty())
                            <p class="mt-2 border border-stone bg-bone px-3 py-2 text-[13px] leading-[1.5] text-muted">
                                Belum ada data warga. Tambahkan warga dulu, lalu kembali ke halaman ini.
                            </p>
                            <input type="hidden" name="user_id" value="">
                        @else
                            <select
                                id="user_id"
                                name="user_id"
                                required
                                @error('user_id') aria-invalid="true" aria-describedby="error-user_id" @enderror
                                class="mt-2 h-11 w-full rounded-control border bg-paper px-3 text-[15px] text-ink @error('user_id') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                            >
                                <option value="" disabled @selected(old('user_id') === null)>Pilih nama warga</option>
                                @foreach ($daftarWarga as $warga)
                                    <option value="{{ $warga->id }}" @selected((string) old('user_id') === (string) $warga->id)>{{ $warga->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p id="error-user_id" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label for="jenis_sampah" class="block text-[13px] font-medium leading-[1.4] text-ink">Jenis sampah</label>
                            <select
                                id="jenis_sampah"
                                name="jenis_sampah"
                                required
                                @error('jenis_sampah') aria-invalid="true" aria-describedby="error-jenis_sampah" @enderror
                                class="mt-2 h-11 w-full rounded-control border bg-paper px-3 text-[15px] text-ink @error('jenis_sampah') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                            >
                                <option value="" disabled @selected(old('jenis_sampah') === null)>Pilih jenis</option>
                                @foreach ($daftarJenis as $nama => $harga)
                                    <option value="{{ $nama }}" data-harga="{{ $harga }}" @selected(old('jenis_sampah') === $nama)>{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_sampah')
                                <p id="error-jenis_sampah" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="berat" class="block text-[13px] font-medium leading-[1.4] text-ink">Berat</label>
                            <div class="relative mt-2">
                                <input
                                    id="berat"
                                    name="berat"
                                    type="text"
                                    inputmode="decimal"
                                    autocomplete="off"
                                    required
                                    value="{{ old('berat') }}"
                                    @error('berat') aria-invalid="true" aria-describedby="error-berat" @else aria-describedby="hint-berat" @enderror
                                    class="h-16 w-full rounded-control border bg-paper pr-12 text-center font-mono text-[32px] font-medium leading-[1.1] text-ink @error('berat') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                                >
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 font-mono text-[14px] text-muted" aria-hidden="true">kg</span>
                            </div>
                            @error('berat')
                                <p id="error-berat" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                            @else
                                <p id="hint-berat" class="mt-1 text-[12px] leading-[1.4] text-muted">Pakai koma untuk desimal, contoh 12,5.</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="harga_per_kg" class="block text-[13px] font-medium leading-[1.4] text-ink">Harga per kilogram</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] text-muted" aria-hidden="true">Rp</span>
                            <input
                                id="harga_per_kg"
                                name="harga_per_kg"
                                type="text"
                                inputmode="decimal"
                                autocomplete="off"
                                required
                                value="{{ old('harga_per_kg') }}"
                                @error('harga_per_kg') aria-invalid="true" aria-describedby="error-harga_per_kg" @else aria-describedby="hint-harga_per_kg" @enderror
                                class="h-11 w-full rounded-control border bg-paper pl-10 pr-12 font-mono text-[15px] text-ink @error('harga_per_kg') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                            >
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[13px] text-muted" aria-hidden="true">/kg</span>
                        </div>
                        @error('harga_per_kg')
                            <p id="error-harga_per_kg" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                        @else
                            <p id="hint-harga_per_kg" class="mt-1 text-[12px] leading-[1.4] text-muted">Terisi otomatis dari jenis sampah, masih bisa diubah.</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan" class="block text-[13px] font-medium leading-[1.4] text-ink">Catatan</label>
                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="3"
                            maxlength="500"
                            aria-describedby="hint-catatan"
                            class="mt-2 min-h-[88px] w-full rounded-control border bg-paper px-3 py-2 text-[15px] leading-[1.6] text-ink @error('catatan') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                        >{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p id="error-catatan" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                        @else
                            <p id="hint-catatan" class="mt-1 text-[12px] leading-[1.4] text-muted">Opsional, misal: botol sudah dipilah dan dikeringkan.</p>
                        @enderror
                    </div>

                    <div class="flex items-baseline justify-between gap-4 border-t border-stone pt-3">
                        <p class="text-[13px] leading-[1.4] text-muted">Perkiraan nilai setoran</p>
                        <p id="estimasi-nilai" aria-live="polite" class="font-mono text-[28px] font-medium leading-none text-ochre">{{ Angka::rupiah($estimasiAwal) }}</p>
                    </div>

                    <div class="pt-1">
                        <button
                            type="submit"
                            data-submit
                            @disabled($daftarWarga->isEmpty())
                            class="inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px disabled:bg-forest/60 md:w-auto"
                        >
                            <span data-submit-label>Catat setoran</span>
                        </button>
                        <p id="status-simpan" role="status" class="sr-only"></p>
                        <p class="mt-2 text-[12px] leading-[1.4] text-muted">Status awal setiap setoran: Menunggu Timbang.</p>
                    </div>
                </form>
            </div>
        </section>

        <aside aria-labelledby="judul-ringkasan" class="lg:col-span-5">
            <div class="rounded-card border border-stone bg-paper p-4 md:p-5">
                <div class="flex items-baseline justify-between gap-3">
                    <h2 id="judul-ringkasan" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">Ringkasan hari ini</h2>
                    <p class="font-mono text-[12px] leading-[1.4] text-muted">{{ now()->format('d.m.Y') }}</p>
                </div>

                <dl class="mt-4 divide-y divide-stone border-t border-stone">
                    <div class="flex items-baseline justify-between gap-3 py-3">
                        <dt class="text-[13px] leading-[1.4] text-muted">Setoran tercatat</dt>
                        <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ $ringkasan['jumlah'] }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-3 py-3">
                        <dt class="text-[13px] leading-[1.4] text-muted">Total berat</dt>
                        <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ Angka::kg($ringkasan['berat']) }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-3 py-3">
                        <dt class="text-[13px] leading-[1.4] text-muted">Nilai setoran</dt>
                        <dd class="font-mono text-[28px] font-medium leading-none text-ochre">{{ Angka::rupiah($ringkasan['nilai']) }}</dd>
                    </div>
                </dl>

                <p class="mt-3 text-[12px] leading-[1.4] text-muted">Nilai memakai harga acuan per kilogram saat setoran dicatat.</p>

                <div class="mt-5 border-t border-stone pt-4">
                    <div class="flex items-baseline justify-between gap-3">
                        <h3 class="text-[17px] font-semibold leading-[1.4] text-ink md:text-[18px]">Riwayat hari ini</h3>
                        @if (! $riwayatError)
                            <p class="text-[12px] leading-[1.4] text-muted">{{ $jumlahRiwayat }} setoran</p>
                        @endif
                    </div>

                    @if ($riwayatError)
                        <div role="alert" class="mt-3 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-4">
                            <p class="text-[14px] font-medium leading-[1.5] text-ink">Riwayat belum bisa ditampilkan.</p>
                            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $riwayatError }}</p>
                            <a href="{{ route('bank.penerimaan') }}" class="mt-2 inline-flex h-11 items-center text-[13px] font-medium text-forest underline underline-offset-4 hover:decoration-2">Muat ulang halaman</a>
                        </div>
                    @elseif ($riwayat->isEmpty())
                        <div class="mt-3 border border-stone bg-bone px-4 py-5">
                            <p class="text-[14px] font-medium leading-[1.5] text-ink">Belum ada setoran hari ini.</p>
                            <p class="mt-1 text-[13px] leading-[1.5] text-muted">Mulai dari menimbang botol PET warga pertama, hasilnya langsung muncul di sini.</p>
                        </div>
                    @else
                        <div id="riwayat-loading" hidden aria-hidden="true" class="mt-3 space-y-3">
                            <div class="h-3 w-3/4 bg-stone"></div>
                            <div class="h-3 w-1/2 bg-stone"></div>
                            <div class="h-3 w-2/3 bg-stone"></div>
                        </div>

                        <div class="hidden md:block">
                            <table class="w-full text-left text-[14px]">
                                <caption class="sr-only">Setoran sampah yang tercatat hari ini</caption>
                                <thead>
                                    <tr class="border-b border-stone">
                                        <th scope="col" class="py-2 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Warga</th>
                                        <th scope="col" class="py-2 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Jenis</th>
                                        <th scope="col" class="py-2 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Berat</th>
                                        <th scope="col" class="py-2 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayat as $setoran)
                                        @php $detail = $setoran->detailSampah->first(); @endphp
                                        <tr class="h-11 border-b border-stone align-middle hover:bg-[#F0EDE4]">
                                            <td class="py-2 pr-2">
                                                <span class="block text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->user?->name ?? 'Warga' }}</span>
                                                <span class="block font-mono text-[11px] leading-[1.4] text-muted">{{ $setoran->created_at->format('H:i') }}</span>
                                            </td>
                                            <td class="py-2 pr-2 text-[13px] leading-[1.4] text-muted">{{ $detail?->jenis_sampah ?? 'Campuran' }}</td>
                                            <td class="py-2 pl-2 text-right font-mono text-[14px] text-ink">{{ Angka::kg($setoran->total_berat) }}</td>
                                            <td class="py-2 pl-2 text-right font-mono text-[14px] text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <ul class="md:hidden">
                            @foreach ($riwayat as $setoran)
                                @php $detail = $setoran->detailSampah->first(); @endphp
                                <li class="border-b border-stone py-3">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="text-[14px] font-medium leading-[1.4] text-ink">{{ $setoran->user?->name ?? 'Warga' }}</p>
                                        <p class="font-mono text-[14px] leading-[1.4] text-ink">{{ Angka::kg($setoran->total_berat) }}</p>
                                    </div>
                                    <div class="mt-0.5 flex items-baseline justify-between gap-3 text-[12px] leading-[1.4] text-muted">
                                        <p>{{ $setoran->created_at->format('H:i') }}, {{ $detail?->jenis_sampah ?? 'Campuran' }}</p>
                                        <p class="font-mono text-[14px] text-ink">{{ Angka::rupiah($setoran->total_nilai) }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if ($jumlahRiwayat > $riwayat->count())
                            <p class="mt-3 text-[12px] leading-[1.4] text-muted">Menampilkan {{ $riwayat->count() }} dari {{ $jumlahRiwayat }} setoran hari ini.</p>
                        @endif
                    @endif
                </div>
            </div>
        </aside>
    </div>
@endsection
