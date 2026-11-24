@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 96px;">
    <p class="mono-label text-sm text-muted uppercase mb-6 border-t-2 border-t-primary pt-6">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    
    <h1 class="section-title mb-6">Estimasi nilai sampah</h1>
    <p class="body-text text-muted mb-12">
        Isi jenis dan berat sampah. Perkiraan nilai muncul dari harga acuan bank sampah, sebelum setoran ditimbang ulang.
    </p>

    <div class="card-app mb-12">
        <h2 class="card-title mb-8">Jenis dan berat</h2>

        <div class="form-group">
            <label class="label-form">Jenis sampah</label>
            <select id="jenis_sampah" class="input-text">
                <option value="" selected>Pilih jenis sampah</option>
                <option value="1">Kertas Koran/Kardus</option>
                <option value="2">Botol Plastik PET</option>
                <option value="3">Kaleng Aluminium</option>
            </select>
            <p class="mt-1 text-muted text-sm">Pilih jenis untuk melihat harga acuan.</p>
        </div>

        <div class="form-group">
            <label class="label-form">Berat</label>
            <div>
                <input id="berat" type="text" inputmode="decimal" placeholder="0" class="input-text text-right font-mono font-medium">
            </div>
            <p class="mt-1 text-muted text-sm">Pakai koma untuk desimal, contoh 2,5.</p>
        </div>

        <div class="border-t border-gray-200 pt-6 mt-6">
            <p class="mono-label text-sm text-muted uppercase mb-3">Perkiraan nilai setoran</p>
            <p class="mono-lg text-muted text-right mb-1">Rp 0</p>
            <p class="text-muted text-sm text-right">Pilih jenis sampah dulu.</p>
        </div>

        <a href="{{ route('warga.setor') }}" class="btn btn-primary w-full mb-4">Ajukan Setoran</a>
        <p class="text-muted text-sm">Nilai akhir mengikuti hasil penimbangan di bank sampah.</p>
    </div>

    <!-- Harga Acuan -->
    <div class="card-app">
        <h2 class="card-title mb-6">Harga acuan hari ini</h2>
        <table class="table-data">
            <thead>
                <tr>
                    <th>Jenis sampah</th>
                    <th class="text-right">Harga per kg</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="font-medium">Kertas Koran/Kardus</span>
                        <span class="text-muted text-sm">Organik/Kertas</span>
                    </td>
                    <td class="text-right font-mono font-medium">Rp 3.500 /kg</td>
                </tr>
                <tr>
                    <td>
                        <span class="font-medium">Botol Plastik PET</span>
                        <span class="text-muted text-sm">Anorganik</span>
                    </td>
                    <td class="text-right font-mono font-medium">Rp 5.000 /kg</td>
                </tr>
                <tr>
                    <td>
                        <span class="font-medium">Kaleng Aluminium</span>
                        <span class="text-muted text-sm">Anorganik</span>
                    </td>
                    <td class="text-right font-mono font-medium">Rp 15.000 /kg</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection