@extends('layouts.warga')

@section('title', 'Beranda warga, Desa Sirkular')
@section('description', 'Catatan sampah warga Desa Sukamaju.')

@section('content')
    <p class="font-mono text-[12px] leading-[1.4] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Beranda warga</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Ringkasan saldo dan setoran belum tersedia di tampilan ini. Mulai dari menghitung perkiraan nilai sampah yang mau kamu setor.
    </p>

    <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
        <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Mulai dari catatan hari ini</h2>
        <p class="mt-1 text-[13px] leading-[1.5] text-muted">
            Pilih jenis sampah dan isi berat. Harga acuan diambil dari daftar bank sampah.
        </p>
        <a href="{{ route('warga.estimasi') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px">
            Hitung estimasi sampah
        </a>
    </div>
@endsection
