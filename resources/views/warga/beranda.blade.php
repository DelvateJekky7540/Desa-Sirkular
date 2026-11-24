@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 96px;">
    <p class="mono-label text-sm text-muted uppercase mb-6 border-t-2 border-t-primary pt-6">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    
    <h1 class="section-title mb-6">Beranda warga</h1>
    <p class="body-text text-muted mb-12">
        Ringkasan saldo dan setoran belum tersedia di tampilan ini. Mulai dari menghitung perkiraan nilai sampah yang mau kamu setor.
    </p>

    <div class="card-app">
        <h2 class="card-title mb-6">Mulai dari catatan hari ini</h2>
        <p class="body-text text-muted mb-8">
            Pilih jenis sampah dan isi berat. Harga acuan diambil dari daftar bank sampah.
        </p>
        <a href="{{ route('warga.estimasi') }}" class="btn btn-primary w-full">Hitung estimasi sampah</a>
    </div>
</div>
@endsection