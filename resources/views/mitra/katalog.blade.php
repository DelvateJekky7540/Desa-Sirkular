@extends('layouts.app')

@section('title', 'Katalog Bahan Baku | DESA-SIRKULAR')
@php
    $daftarJenis = [
        (object)['id' => 1, 'nama_sampah' => 'Kertas', 'kategori' => 'Organik'],
        (object)['id' => 2, 'nama_sampah' => 'Plastik', 'kategori' => 'Anorganik'],
        (object)['id' => 3, 'nama_sampah' => 'Logam', 'kategori' => 'Anorganik'],
    ];
    $katalogGrup = [
        'Kertas Recycle' => [
            (object)['nama_bank' => 'Bank Sampah Sukamaju', 'jenis_sampah_id' => 1, 'bank_sampah_id' => 1, 'total_berat' => 120, 'jumlah_setoran' => 5],
            (object)['nama_bank' => 'Bank Sampah Harapan', 'jenis_sampah_id' => 1, 'bank_sampah_id' => 2, 'total_berat' => 80, 'jumlah_setoran' => 3],
        ],
        'Plastik Premium' => [
            (object)['nama_bank' => 'Bank Sampah Sukamaju', 'jenis_sampah_id' => 2, 'bank_sampah_id' => 1, 'total_berat' => 200, 'jumlah_setoran' => 8],
        ],
    ];
    $ringkasan = [
        'jenis' => 'Semua jenis',
        'berat' => 400,
        'bank' => '2 bank',
    ];
    $filterJenis = null;
    $filterBerat = null;
    $adaFilter = false;
    $katalogError = false;
    $namaJenisTerpilih = null;
    $namaBankTerpilih = null;
@endphp

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    @if ($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FCA5A5; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <p style="color: #B91C1C; font-weight: 500; margin-bottom: 8px;">Filter belum bisa diterapkan.</p>
            @foreach ($errors->all() as $error)
                <p style="color: #B91C1C; font-size: 14px;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div style="border-top: 2px solid var(--primary); border-bottom: 1px solid var(--border); padding: 32px 0; margin-bottom: 32px;">
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 16px;">Data per {{ now()->locale('id')->translatedFormat('j F Y') }}</p>
        <h1 class="section-title" style="font-size: 32px; margin-bottom: 16px;">Katalog bahan baku</h1>
        <p style="font-size: 15px; color: var(--muted); line-height: 1.6;">
            Bahan baku dari setoran yang sudah ditimbang di bank sampah, siap diajukan.
        </p>
    </div>

    @if (! $katalogError)
        <div style="display: flex; flex-wrap: wrap; gap: 32px; margin-bottom: 32px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
            <div>
                <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Jenis sampah</p>
                <p style="font-family: var(--font-mono); font-size: 20px; font-weight: 500; color: var(--ink);">{{ $ringkasan['jenis'] }}</p>
            </div>
            <div>
                <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Berat tersedia</p>
                <p style="font-family: var(--font-mono); font-size: 20px; font-weight: 500; color: var(--ink);">400 kg</p>
            </div>
            <div>
                <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Bank sampah</p>
                <p style="font-family: var(--font-mono); font-size: 20px; font-weight: 500; color: var(--ink);">{{ $ringkasan['bank'] }}</p>
            </div>
        </div>
    @endif

    <form method="GET" action="{{ route('mitra.katalog') }}" style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
        <div style="flex: 1; min-width: 240px;">
            <label for="jenis_sampah_id" class="label-form">Jenis sampah</label>
            <select id="jenis_sampah_id" name="jenis_sampah_id" class="input-text">
                <option value="">Semua jenis</option>
                @foreach ($daftarJenis as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->nama_sampah }}, {{ $jenis->kategori }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 160px;">
            <label for="berat_min" class="label-form">Berat minimum</label>
            <input id="berat_min" name="berat_min" type="text" class="input-text" placeholder="Contoh: 50">
        </div>

        <div style="display: flex; align-items: flex-end; gap: 12px;">
            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="{{ route('mitra.katalog') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>

    @if ($katalogError)
        <div class="card-app" style="margin-bottom: 24px;">
            <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Katalog belum bisa ditampilkan.</p>
            <p style="color: var(--muted);">{{ $katalogError }}</p>
            <a href="{{ route('mitra.katalog') }}" class="btn btn-primary" style="margin-top: 16px;">Muat ulang halaman</a>
        </div>
    @elseif ($katalogGrup->isEmpty() && $adaFilter)
        <div class="card-app" style="margin-bottom: 24px;">
            <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Tidak ada bahan baku yang cocok dengan filter ini.</p>
            <p style="color: var(--muted);">
                Belum ada stok jenis manapun di bank sampah terpilih. Tampilkan semua bank untuk melihat bahan baku lain.
            </p>
            <a href="{{ route('mitra.katalog') }}" class="btn btn-primary" style="margin-top: 16px;">Reset filter</a>
        </div>
    @elseif ($katalogGrup->isEmpty())
        <div class="card-app" style="margin-bottom: 24px;">
            <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Belum ada bahan baku yang tersedia.</p>
            <p style="color: var(--muted);">
                Data muncul setelah petugas bank sampah menimbang setoran dan menyimpan hasil timbangnya.
            </p>
        </div>
    @else
        <div class="card-app">
          <table class="table-data">
              <thead>
                  <tr>
                      <th style="width: 200px;">Bank sampah</th>
                      <th style="text-align: right; width: 120px;">Berat tersedia</th>
                      <th style="text-align: right; width: 100px;">Setoran</th>
                      <th style="text-align: right; width: 120px;">Aksi</th>
                  </tr>
              </thead>
              @foreach ($katalogGrup as $namaJenis => $barisJenis)
                  <tbody>
                      <tr>
                          <th colspan="4" style="background: #F9FAFB; text-align: left; padding: 12px 24px; font-family: var(--font-display); font-weight: 600; font-size: 16px; color: var(--primary);">
                              <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span>{{ $namaJenis }}</span>
                                <span style="font-family: var(--font-mono); font-size: 12px; color: var(--muted);">Total 200 kg</span>
                              </div>
                          </th>
                      </tr>
                      @foreach ($barisJenis as $baris)
                          <tr>
                              <td>{{ $baris->nama_bank }}</td>
                              <td style="text-align: right; font-family: var(--font-mono); font-weight: 500;">{{ $baris->total_berat }} kg</td>
                              <td style="text-align: right; color: var(--muted); font-size: 13px;">{{ $baris->jumlah_setoran }} setoran</td>
                              <td style="text-align: right;">
                                  <a href="{{ route('mitra.penawaran', ['jenis' => $baris->jenis_sampah_id, 'bank' => $baris->bank_sampah_id]) }}" class="btn btn-outline" style="font-size: 13px; padding: 8px 16px;">Ajukan</a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              @endforeach
          </table>
        </div>
    @endif
</div>
@endsection