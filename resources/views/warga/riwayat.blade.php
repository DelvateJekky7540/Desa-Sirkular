@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 96px;">
    <p class="mono-label text-sm text-muted uppercase mb-6 border-t-2 border-t-primary pt-6">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    
    <h1 class="section-title mb-6">Riwayat setoran</h1>
    <p class="body-text text-muted mb-12">
        Riwayat setoran belum tersedia di tampilan ini. Catatan setoran sementara disimpan oleh pengelola bank sampah.
    </p>

    <div class="card-app">
        <h2 class="card-title mb-6">Belum ada riwayat yang bisa ditampilkan</h2>
        <p class="body-text text-muted mb-8">
            Setelah fitur riwayat tersambung ke akun warga, setoran yang sudah ditimbang akan muncul di sini.
        </p>
        <a href="{{ route('warga.estimasi') }}" class="btn btn-primary w-full">Hitung estimasi sampah</a>
    </div>
</div>
@endsection