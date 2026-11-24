@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 96px;">
    <p class="mono-label text-sm text-muted uppercase mb-6 border-t-2 border-t-primary pt-6">Catatan, {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    
    <h1 class="section-title mb-6">Ajukan setoran</h1>
    <p class="body-text text-muted mb-12">
        Isi jenis dan berat sampah. Pengajuan masuk antrean bank sampah untuk ditimbang ulang.
    </p>

    <div class="card-app">
        <div class="border-t border-gray-200 pt-4 pb-4 mb-6">
            <p class="mono-label text-sm text-muted uppercase mb-1">Pengajuan untuk</p>
            <p class="font-medium">Budi Santoso</p>
        </div>

        <h2 class="card-title mb-8">Jenis dan berat</h2>

        <form method="POST" action="{{ route('warga.setor.store') }}">
            @csrf
            <div class="form-group">
                <label class="label-form">Jenis sampah</label>
                <select id="jenis_sampah" name="jenis_sampah_id" class="input-text">
                    <option value="" disabled selected>Pilih jenis sampah</option>
                    <option value="1">Kertas, Organik</option>
                    <option value="2">Plastik, Anorganik</option>
                    <option value="3">Logam, Anorganik</option>
                </select>
                <p class="mt-1 text-muted text-sm">Pilih jenis untuk melihat harga acuan.</p>
            </div>

            <div class="form-group">
                <label class="label-form">Berat</label>
                <input id="berat" name="berat" type="text" inputmode="decimal" class="input-text text-right font-mono font-medium" placeholder="0">
                <p class="mt-1 text-muted text-sm">Pakai koma untuk desimal, contoh 2,5. Maksimal 500 kg.</p>
            </div>

            <div class="form-group">
                <label class="label-form">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" class="input-text" style="min-height: 88px; resize: vertical;"></textarea>
                <p class="mt-1 text-muted text-sm">Opsional, misal: botol sudah dipilah dan dikeringkan.</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6">
                <p class="mono-label text-sm text-muted uppercase mb-3">Perkiraan nilai setoran</p>
                <p class="mono-lg text-muted text-right mb-1">Rp 0</p>
                <p class="text-muted text-sm text-right" data-rincian-estimasi>Pilih jenis sampah dulu.</p>
                <p class="text-muted text-sm mt-2">Perkiraan dari harga acuan. Nilai akhir mengikuti hasil timbang.</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-amber-100 text-amber-800 px-3 py-2 rounded-full text-sm font-mono uppercase">Menunggu timbang</span>
                    <p class="text-muted text-sm">Status awal setelah pengajuan dikirim.</p>
                </div>

                <button type="submit" class="btn btn-primary w-full mb-4">Kirim pengajuan</button>
                <p class="text-muted text-sm">Bawa sampah ke Bank Sampah Sukamaju untuk ditimbang. Nilai akhir mengikuti hasil timbang.</p>
            </div>
        </form>
    </div>
</div>
@endsection