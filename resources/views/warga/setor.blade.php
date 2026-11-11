@extends('layouts.warga')

@section('title', 'Ajukan setoran, Desa Sirkular')
@section('description', 'Langkah pengajuan setoran sampah warga Desa Sukamaju.')

@section('content')
    <p class="font-mono text-[12px] leading-[1.4] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Ajukan setoran</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Pengajuan setoran lewat web belum tersedia. Sementara itu, bawa sampah yang sudah dipilah ke bank sampah di Desa Sukamaju.
    </p>

    <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
        <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Langkah sementara</h2>
        <ol class="mt-2 list-decimal space-y-2 pl-5 text-[14px] leading-[1.6] text-ink">
            <li>Pilah sampah dan pastikan kering.</li>
            <li>Bawa ke bank sampah pada jam operasional.</li>
            <li>Petugas akan menimbang dan mencatat setoran.</li>
        </ol>
        <a href="{{ route('warga.estimasi') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px">
            Kembali ke estimasi
        </a>
    </div>
@endsection
