@extends('layouts.warga')

@section('title', 'Edukasi sampah, Desa Sirkular')
@section('description', 'Panduan singkat memilah sampah untuk warga Desa Sukamaju.')

@section('content')
    <p class="font-mono text-[12px] leading-[1.4] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Edukasi sampah</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Materi edukasi belum tersedia. Sementara itu, pisahkan sampah kering dan bersihkan dari sisa makanan sebelum disetor.
    </p>

    <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
        <h2 class="text-[17px] font-semibold leading-[1.4] text-ink">Pilah sebelum setor</h2>
        <ul class="mt-2 space-y-2 text-[14px] leading-[1.6] text-ink">
            <li>Pisahkan plastik, kertas, logam, dan sampah organik.</li>
            <li>Keringkan botol dan kaleng supaya beratnya tidak terbawa air.</li>
            <li>Bawa sampah yang sudah dipilah ke bank sampah.</li>
        </ul>
        <a href="{{ route('warga.estimasi') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px">
            Hitung estimasi sampah
        </a>
    </div>
@endsection
