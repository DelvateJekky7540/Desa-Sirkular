@extends('layouts.publik')

@section('title', 'Lacak setoran sampah, Desa Sirkular')
@section('description', 'Masukkan kode setoran untuk melihat perjalanan sampah dari warga, bank sampah, sampai tabungan di Desa Sukamaju.')

@section('content')
    <div class="border-t-2 border-t-forest border-b border-b-stone pt-4 pb-6 md:pb-8">
        <p class="mb-2 font-mono text-[12px] uppercase leading-[1.4] tracking-[0.08em] text-muted">Lacak setoran, Desa Sukamaju</p>
        <h1 class="font-display text-[26px] font-semibold leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Lacak setoran sampah</h1>
        <p class="mt-3 max-w-[58ch] text-[15px] leading-[1.6] text-muted">
            Masukkan kode setoran untuk melihat perjalanan sampahnya: dari warga, penimbangan di bank sampah, sampai masuk tabungan.
        </p>
    </div>

    @include('tracking.partials.form-kode')
@endsection
