@extends('layouts.mitra')

@section('title', 'Katalog bahan baku, Desa Sirkular')
@section('description', 'Bahan baku sampah berstatus sudah ditimbang yang tersedia di bank sampah untuk Mitra Daur Ulang, Desa Sukamaju.')

@use('App\Support\Angka')

@php
    $adaFilter = $filterJenis !== null || $filterBerat !== null || $filterBank !== null;

    $tautanSemuaBank = route('mitra.katalog', array_filter([
        'jenis_sampah_id' => $filterJenis,
        'berat_min' => $filterBerat === null ? null : Angka::desimal($filterBerat),
    ]));
@endphp

@section('content')
    @if ($errors->any())
        <div role="alert" class="mb-6 border border-stone border-l-[3px] border-l-brick bg-paper px-4 py-3">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">Filter belum bisa diterapkan. Periksa isian berikut.</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="border-t-2 border-t-forest border-b border-b-stone pt-4 pb-4">
        <p class="mb-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Data per {{ now()->locale('id')->translatedFormat('j F Y') }}</p>
        <h1 class="font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Katalog bahan baku</h1>
        <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
            Bahan baku dari setoran yang sudah ditimbang di bank sampah, siap diajukan. Angka di bawah mengikuti filter yang dipakai.
        </p>
    </div>

    @if (! $katalogError)
        <dl class="mt-6 flex flex-wrap items-baseline gap-x-8 gap-y-2 border-b border-stone pb-4">
            <div class="flex items-baseline gap-2">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Jenis sampah</dt>
                <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ $ringkasan['jenis'] }}</dd>
            </div>
            <div class="flex items-baseline gap-2">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Berat tersedia</dt>
                <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ Angka::kg($ringkasan['berat']) }}</dd>
            </div>
            <div class="flex items-baseline gap-2">
                <dt class="font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Bank sampah</dt>
                <dd class="font-mono text-[20px] font-medium leading-none text-ink">{{ $ringkasan['bank'] }}</dd>
            </div>
        </dl>
    @endif

    <form method="GET" action="{{ route('mitra.katalog') }}" class="mt-6 flex flex-wrap items-end gap-x-4 gap-y-3 border-b border-stone pb-4">
        <div class="min-w-[200px] max-w-[280px] flex-1">
            <label for="jenis_sampah_id" class="label-form">Jenis sampah</label>
            <select
                id="jenis_sampah_id"
                name="jenis_sampah_id"
                class="mt-2 h-11 w-full rounded-control border border-stone bg-paper px-3 text-[15px] text-ink hover:border-moss focus:border-forest"
            >
                <option value="">Semua jenis</option>
                @foreach ($daftarJenis as $jenis)
                    <option value="{{ $jenis->id }}" @selected($filterJenis === (int) $jenis->id)>
                        {{ $jenis->nama_sampah }}, {{ $jenis->kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-[160px]">
            <label for="berat_min" class="label-form">Berat minimum</label>
            <input
                id="berat_min"
                name="berat_min"
                type="text"
                inputmode="decimal"
                autocomplete="off"
                placeholder="Contoh: 50"
                value="{{ old('berat_min', $filterBerat === null ? '' : Angka::desimal($filterBerat)) }}"
                class="mt-2 h-11 w-full rounded-control border border-stone bg-paper px-3 font-mono text-[15px] text-ink hover:border-moss focus:border-forest"
            />
        </div>

        <div class="flex items-end gap-2">
            <button
                type="submit"
                class="inline-flex h-11 items-center justify-center rounded-control border border-forest px-4 text-[13px] font-medium text-forest transition-colors duration-150 hover:bg-forest hover:text-bone active:translate-y-px"
            >
                Terapkan
            </button>

            @if ($adaFilter)
                <a
                    href="{{ route('mitra.katalog') }}"
                    class="inline-flex h-11 items-center justify-center rounded-control border border-forest px-4 text-[13px] font-medium text-forest transition-colors duration-150 hover:bg-forest hover:text-bone active:translate-y-px"
                >
                    Reset
                </a>
            @endif
        </div>
    </form>

    @if ($filterBank !== null)
        <div class="mt-4 border border-stone border-l-[3px] border-l-moss bg-bone px-4 py-3">
            <p class="text-[13px] leading-[1.5] text-muted">
                Menampilkan bahan baku dari <span class="font-medium text-ink">{{ $namaBankTerpilih ?? 'bank sampah terpilih' }}</span>.
                <a href="{{ $tautanSemuaBank }}" class="tautan-garis font-medium text-forest">Tampilkan semua bank</a>
            </p>
        </div>
    @endif

    @if ($katalogError)
        <div role="alert" class="mt-6 rounded-card border border-stone bg-paper px-4 py-5 md:px-5">
            <p class="text-[15px] font-medium leading-[1.5] text-ink">Katalog belum bisa ditampilkan.</p>
            <p class="mt-1 text-[13px] leading-[1.5] text-muted">{{ $katalogError }}</p>
            <a href="{{ route('mitra.katalog') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">Muat ulang halaman</a>
        </div>
    @elseif ($katalogGrup->isEmpty() && $adaFilter)
        <div class="mt-6 rounded-card border border-stone bg-paper px-4 py-6 md:px-5">
            <p class="text-[15px] font-medium leading-[1.5] text-ink">Tidak ada bahan baku yang cocok dengan filter ini.</p>
            <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">
                @if ($filterBank !== null)
                    Belum ada stok {{ $namaJenisTerpilih ?? 'jenis manapun' }} di {{ $namaBankTerpilih ?? 'bank sampah terpilih' }}{{ $filterBerat !== null ? ' dengan berat di atas '.Angka::kg($filterBerat) : '' }}. Tampilkan semua bank untuk melihat bahan baku lain.
                @elseif ($filterJenis !== null && $filterBerat !== null)
                    Belum ada stok {{ $namaJenisTerpilih ?? 'jenis ini' }} dengan berat di atas {{ Angka::kg($filterBerat) }}. Turunkan berat minimum atau pilih semua jenis.
                @elseif ($filterJenis !== null)
                    Belum ada stok {{ $namaJenisTerpilih ?? 'jenis ini' }} yang berstatus sudah ditimbang. Pilih semua jenis untuk melihat bahan baku lain.
                @else
                    Tidak ada bahan baku dengan berat di atas {{ Angka::kg($filterBerat) }}. Turunkan berat minimum.
                @endif
            </p>
            <a href="{{ route('mitra.katalog') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">Reset filter</a>
        </div>
    @elseif ($katalogGrup->isEmpty())
        <div class="mt-6 rounded-card border border-stone bg-paper px-4 py-6 md:px-5">
            <p class="text-[15px] font-medium leading-[1.5] text-ink">Belum ada bahan baku berstatus sudah ditimbang.</p>
            <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">
                Data muncul setelah petugas bank sampah menimbang setoran dan menyimpan hasil timbangnya.
            </p>
        </div>
    @else
        <div class="mt-4 hidden border-t-2 border-t-forest pt-4 md:block">
            <table class="w-full text-left text-[14px]">
                <caption class="sr-only">Bahan baku setoran berstatus sudah ditimbang, dikelompokkan per jenis sampah lalu per bank sampah</caption>
                <thead>
                    <tr class="border-b border-stone">
                        <th scope="col" class="py-2 pr-3 text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Bank sampah</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Berat tersedia</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Setoran</th>
                        <th scope="col" class="py-2 pl-3 text-right text-[13px] font-medium uppercase leading-[1.4] tracking-[0.02em] text-muted">Aksi</th>
                    </tr>
                </thead>
                @foreach ($katalogGrup as $namaJenis => $barisJenis)
                    <tbody>
                        <tr class="h-12">
                            <th scope="colgroup" colspan="4" class="border-b border-stone pb-2 text-left align-middle {{ $loop->first ? 'pt-2' : 'pt-6' }}">
                                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                                    <span class="text-[14px] font-semibold leading-[1.4] text-ink">
                                        {{ $namaJenis }}
                                        <span class="text-[12px] font-normal text-muted">, {{ $barisJenis->first()->kategori }}</span>
                                    </span>
                                    <span class="font-mono text-[13px] leading-[1.4] text-muted">Total {{ Angka::kg($barisJenis->sum('total_berat')) }}</span>
                                </div>
                            </th>
                        </tr>
                        @foreach ($barisJenis as $baris)
                            <tr class="h-12 border-b border-stone align-middle transition-colors hover:bg-[#F0EDE4]">
                                <td class="py-2 pr-3 text-[14px] font-medium leading-[1.4] text-ink">{{ $baris->nama_bank }}</td>
                                <td class="py-2 pl-3 text-right font-mono text-[15px] font-medium leading-[1.4] text-ink">{{ Angka::kg($baris->total_berat) }}</td>
                                <td class="py-2 pl-3 text-right font-mono text-[13px] leading-[1.4] text-muted">{{ $baris->jumlah_setoran }} setoran</td>
                                <td class="py-2 pl-3 text-right">
                                    <a
                                        href="{{ route('mitra.penawaran', ['jenis' => $baris->jenis_sampah_id, 'bank' => $baris->bank_sampah_id]) }}"
                                        class="inline-flex h-11 items-center justify-center rounded-control border border-forest px-4 text-[13px] font-medium text-forest transition-colors duration-150 hover:bg-forest hover:text-bone active:translate-y-px"
                                    >
                                        Ajukan
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endforeach
            </table>
        </div>

        <ul class="mt-4 border-t-2 border-t-forest pt-4 md:hidden">
            @foreach ($katalogGrup as $namaJenis => $barisJenis)
                <li class="{{ $loop->first ? '' : 'mt-6' }}">
                    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 border-b border-stone pb-2">
                        <span class="text-[15px] font-semibold leading-[1.4] text-ink">
                            {{ $namaJenis }}
                            <span class="text-[12px] font-normal text-muted">, {{ $barisJenis->first()->kategori }}</span>
                        </span>
                        <span class="font-mono text-[13px] leading-[1.4] text-muted">Total {{ Angka::kg($barisJenis->sum('total_berat')) }}</span>
                    </div>
                    @foreach ($barisJenis as $baris)
                        <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2 border-b border-stone py-4 transition-colors hover:bg-[#F0EDE4]">
                            <div class="min-w-0">
                                <p class="text-[15px] font-medium leading-[1.4] text-ink">{{ $baris->nama_bank }}</p>
                                <p class="mt-1 font-mono text-[14px] leading-[1.4] text-muted">{{ Angka::kg($baris->total_berat) }}, {{ $baris->jumlah_setoran }} setoran</p>
                            </div>
                            <a
                                href="{{ route('mitra.penawaran', ['jenis' => $baris->jenis_sampah_id, 'bank' => $baris->bank_sampah_id]) }}"
                                class="inline-flex h-11 shrink-0 items-center justify-center rounded-control border border-forest px-4 text-[13px] font-medium text-forest transition-colors duration-150 hover:bg-forest hover:text-bone active:translate-y-px"
                            >
                                Ajukan
                            </a>
                        </div>
                    @endforeach
                </li>
            @endforeach
        </ul>
    @endif
@endsection
