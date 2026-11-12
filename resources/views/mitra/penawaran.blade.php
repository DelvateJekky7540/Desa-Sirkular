@extends('layouts.mitra')

@section('title', 'Penawaran harga, Desa Sirkular')
@section('description', 'Formulir penawaran harga bahan baku untuk Mitra Daur Ulang, Desa Sukamaju. Fitur belum dibuka.')

@section('content')
    <div class="border-t-2 border-t-forest border-b border-b-stone pt-4 pb-4">
        <p class="mb-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Formulir penawaran, Desa Sukamaju</p>
        <h1 class="font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Penawaran harga</h1>
        <p class="mt-2 max-w-[64ch] text-[15px] leading-[1.6] text-muted">
            Ajukan harga untuk bahan baku yang dipilih di katalog.
        </p>
    </div>

    <div class="mt-6 rounded-card border border-stone bg-paper px-4 py-6 md:px-5">
        <h2 class="judul-seksi text-[17px] font-semibold leading-[1.4] text-ink">Formulir penawaran belum dibuka.</h2>
        <p class="mt-1 max-w-[64ch] text-[13px] leading-[1.5] text-muted">
            @if ($jenisTerpilih !== null && $bankTerpilih !== null)
                Pilihanmu tercatat: {{ $jenisTerpilih->nama_sampah }} di {{ $bankTerpilih->nama_bank }}. Formulir pengajuan untuk bahan baku ini dibuka di halaman yang sama setelah fitur penawaran selesai dibangun.
            @else
                Pilih bahan baku di katalog, lalu tekan tombol Ajukan. Formulir pengajuannya dibuka di halaman ini setelah fitur penawaran selesai dibangun.
            @endif
        </p>
        <a href="{{ route('mitra.katalog') }}" class="tautan-garis mt-3 inline-flex h-11 items-center text-[13px] font-medium text-forest">Kembali ke katalog</a>
    </div>
@endsection
