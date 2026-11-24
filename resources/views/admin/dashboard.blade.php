@extends('layouts.app')

@section('title', 'Laporan Desa | DESA-SIRKULAR')
@section('description', 'Rekap sampah dan nilai ekonomi dari seluruh bank sampah di Desa Sukamaju.')

@use('App\Support\Angka')
@use('App\Models\Setoran')

@php
    $chipStatus = [
        Setoran::STATUS_MENUNGGU => 'bg-mustard/15',
        Setoran::STATUS_DITIMBANG => 'bg-moss/15',
        Setoran::STATUS_DIKONFIRMASI => 'bg-berhasil/15',
        Setoran::STATUS_DIBATALKAN => 'bg-brick/15',
    ];
@endphp

@section('content')
<div class="container">
  <!-- Header -->
  <div class="flex justify-between items-start mb-12 pb-8 border-b border-gray-200">
    <div>
      <p class="mono-label text-sm text-muted uppercase mb-2">Monitoring bank sampah, Desa Sukamaju</p>
      <h1 class="section-title">Laporan desa</h1>
      <p class="body-text text-muted">
        Rekap pencatatan sampah dari seluruh bank sampah di Desa Sukamaju, tanpa perlu membuka satu per satu.
      </p>
    </div>
    <p class="mono-label text-sm text-muted uppercase">
      {{ now()->locale('id')->translatedFormat('l, j F Y') }}
    </p>
  </div>

  @if ($dataError)
    <div class="bg-red-50 border border-red-300 p-6 rounded-lg mb-8">
      <p style="color: #B91C1C; font-weight: 500; margin-bottom: 8px;">Laporan belum bisa ditampilkan.</p>
      <p style="color: var(--muted); margin-bottom: 16px;">{{ $dataError }}</p>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Muat ulang halaman</a>
    </div>
  @else
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 pb-8 border-b border-gray-200">
      <div class="card-app">
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Total sampah tercatat</p>
        <p class="mono-lg" style="font-size: 32px;">{{ Angka::kg($ringkasan['berat']) }}</p>
      </div>
      <div class="card-app">
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Sudah dikonfirmasi, masuk tabungan</p>
        <p class="mono-lg" style="font-size: 32px;">{{ Angka::kg($ringkasan['beratKonfirmasi']) }}</p>
      </div>
      <div class="card-app">
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Nilai ekonomi setoran</p>
        <p class="mono-lg" style="font-size: 32px; color: var(--accent);">{{ Angka::rupiah($ringkasan['nilai']) }}</p>
      </div>
    </div>

    <p style="font-size: 12px; color: var(--muted); margin-bottom: 32px;">
      Angka daur ulang belum dihitung: penjualan stok ke mitra daur ulang belum dicatat di sistem.
    </p>

    <!-- Setoran Terbaru -->
    <div>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <h2 class="section-title" style="font-size: 24px; margin-bottom: 0;">Setoran terbaru</h2>
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted);">10 setoran terakhir</p>
      </div>

      @if ($setoranTerbaru->isEmpty())
        <div class="card-app">
          <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Belum ada setoran tercatat.</p>
          <p style="color: var(--muted);">Tabel ini terisi setelah warga menyetor sampah dan petugas menimbangnya di bank sampah.</p>
        </div>
      @else
        <div class="card-app">
          <table class="table-data">
            <thead>
              <tr>
                <th style="width: 120px;">Tanggal</th>
                <th style="width: 150px;">Warga</th>
                <th style="width: 180px;">Bank sampah</th>
                <th style="width: 100px; text-align: right;">Berat</th>
                <th style="width: 120px; text-align: right;">Nilai</th>
                <th style="width: 120px;">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($setoranTerbaru as $setoran)
                <tr>
                  <td>{{ $setoran->tanggal_setoran->locale('id')->translatedFormat('j M Y') }}</td>
                  <td>{{ $setoran->masyarakat?->user?->nama ?? 'Warga' }}</td>
                  <td>{{ $setoran->bankSampah?->nama_bank ?? 'Bank sampah' }}</td>
                  <td style="text-align: right; font-family: var(--font-mono); font-weight: 500;">{{ Angka::kg($setoran->total_berat) }}</td>
                  <td style="text-align: right; font-family: var(--font-mono); font-weight: 500;">{{ Angka::rupiah($setoran->total_nilai) }}</td>
                  <td>
                    <span style="display: inline-flex; align-items: center; background: var(--border); padding: 6px 12px; border-radius: 4px; font-size: 11px; text-transform: uppercase; color: var(--ink); font-family: var(--font-mono);">
                      {{ $setoran->labelStatus() }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  @endif
</div>
@endsection