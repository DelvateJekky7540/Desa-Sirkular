@extends('layouts.publik')

@section('title', 'Lacak setoran sampah, Desa Sirkular')
@section('description', 'Masukkan kode setoran untuk melihat perjalanan sampah dari warga, bank sampah, sampai tabungan di Desa Sukamaju.')

@section('content')
    <div class="border-b border-stone pb-6 md:pb-8">
        <h1 class="font-display text-[26px] font-medium leading-[1.25] text-forest md:text-[32px] md:leading-[1.2]">Lacak setoran sampah</h1>
        <p class="mt-3 max-w-[58ch] text-[15px] leading-[1.6] text-muted">
            Masukkan kode setoran untuk melihat perjalanan sampahnya: dari warga, penimbangan di bank sampah, sampai masuk tabungan.
        </p>
    </div>

    @include('tracking.partials.form-kode')
@endsection
