@extends('layouts.warga')

@section('title', 'Riwayat setoran, Desa Sirkular')
@section('description', 'Riwayat setoran sampah warga Desa Sukamaju.')

@section('content')
    <p class="border-t-2 border-t-forest pt-4 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1 class="mt-2 font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Riwayat setoran</h1>
    <p class="mt-2 max-w-[48ch] text-[15px] leading-[1.6] text-muted">
        Riwayat setoran belum tersedia di tampilan ini. Catatan setoran sementara disimpan oleh pengelola bank sampah.
    </p>

    <div class="mt-6 rounded-card border border-stone bg-paper p-4 md:p-5">
        <h2 class="judul-seksi text-[17px] font-semibold leading-[1.4] text-ink">Belum ada riwayat yang bisa ditampilkan</h2>
        <p class="mt-1 text-[13px] leading-[1.5] text-muted">
            Setelah fitur riwayat tersambung ke akun warga, setoran yang sudah ditimbang akan muncul di sini.
        </p>
        <a href="{{ route('warga.estimasi') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white transition-colors duration-150 hover:bg-forest-hover active:translate-y-px">
            Hitung estimasi sampah
        </a>
    </div>
@endsection
