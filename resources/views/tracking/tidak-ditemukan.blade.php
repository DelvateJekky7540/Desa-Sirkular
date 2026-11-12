@extends('layouts.publik')

@section('title', 'Kode setoran tidak ditemukan, Desa Sirkular')
@section('description', 'Kode setoran tidak ditemukan di catatan Desa Sukamaju. Periksa lagi bukti setor lalu coba masukkan ulang.')

@section('content')
    <div class="border-t-2 border-t-forest border-b border-b-stone pt-4 pb-6 md:pb-8">
        <p class="mb-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Lacak setoran, Desa Sukamaju</p>
        <h1 class="font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Kode setoran tidak ditemukan</h1>
        <p class="mt-3 max-w-[58ch] text-[15px] leading-[1.6] text-muted">
            Kode <span class="font-mono text-[14px] text-ink">{{ $kode }}</span> tidak ada di catatan Desa Sukamaju.
            Periksa lagi tulisan di bukti setor, lalu masukkan ulang di form berikut.
        </p>
    </div>

    @include('tracking.partials.form-kode', ['kodeDiminta' => $kode])
@endsection
