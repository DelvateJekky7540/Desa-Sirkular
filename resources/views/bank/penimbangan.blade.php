@extends('layouts.app')

@section('title', 'Antrean Penimbangan | DESA-SIRKULAR')
@php
    $antrean = [
        (object)['id' => 1, 'created_at' => now()->subHours(2), 'masyarakat' => (object)['user' => (object)['nama' => 'Budi Santoso']], 'total_berat' => 12.5, 'total_nilai' => 437500, 'detailSampah' => [array('jenisSampah' => array('nama_sampah' => 'Kertas', 'kategori' => 'Organik'))]],
        (object)['id' => 2, 'created_at' => now()->subHours(1), 'masyarakat' => (object)['user' => (object)['nama' => 'Siti Nurhalimah']], 'total_berat' => 8.3, 'total_nilai' => 290500, 'detailSampah' => [array('jenisSampah' => array('nama_sampah' => 'Plastik', 'kategori' => 'Anorganik'))]],
        (object)['id' => 3, 'created_at' => now()->subMinutes(30), 'masyarakat' => (object)['user' => (object)['nama' => 'Ahmad Fauzi']], 'total_berat' => 15.2, 'total_nilai' => 532000, 'detailSampah' => [array('jenisSampah' => array('nama_sampah' => 'Logam', 'kategori' => 'Anorganik'))]],
    ];
    $antreanError = false;
    $ringkasan = [
        'jumlah' => 5,
        'berat' => 35.8,
        'nilai' => 1256000,
        'sudahDitimbangHariIni' => 3
    ];
    $gagalId = null;
@endphp

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    @if (session('status'))
        <div style="background: #D1FAE5; border: 1px solid #34D399; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <p style="color: #065F46;">{{ session('status') }}</p>
        </div>
    @endif

    @if (session('peringatan'))
        <div style="background: #FEF3C7; border: 1px solid #F59E0B; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <p style="color: #92400E;">{{ session('peringatan') }}</p>
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 32px; border-bottom: 1px solid var(--border); padding-bottom: 32px;">
        <div>
            <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 16px;">Antrean penimbangan, Desa Sukamaju</p>
            <h1 class="section-title" style="font-size: 32px; margin-bottom: 16px;">Antrean penimbangan</h1>
            <p style="font-size: 15px; color: var(--muted); line-height: 1.6;">
                Setoran yang menunggu timbang, dari yang paling lama masuk. Pilih satu baris, masukkan angka timbangan, lalu simpan.
            </p>
        </div>
        <p style="font-family: var(--font-mono); font-size: 11px; text-transform: uppercase; color: var(--muted);">
            {{ now()->locale('id')->translatedFormat('l, j F Y') }}
        </p>
    </div>

    @if ($antreanError)
        <div class="card-app" style="margin-bottom: 24px;">
            <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Antrean belum bisa ditampilkan.</p>
            <p style="color: var(--muted);">{{ $antreanError }}</p>
            <a href="{{ route('bank.penimbangan') }}" class="btn btn-primary" style="margin-top: 16px;">Muat ulang halaman</a>
        </div>
    @elseif ($antrean->isEmpty())
        <div class="card-app" style="margin-bottom: 24px;">
            <p style="color: var(--ink); font-weight: 500; margin-bottom: 8px;">Antrean penimbangan kosong.</p>
            <p style="color: var(--muted);">
                Semua setoran sudah ditimbang. Setoran baru muncul di sini setelah dicatat di halaman Catat setoran.
            </p>
            <a href="{{ route('bank.penerimaan') }}" class="btn btn-primary" style="margin-top: 16px;">Catat setoran baru</a>
        </div>
    @else
        <div class="card-app">
          <table class="table-data">
              <thead>
                  <tr>
                      <th style="width: 150px;">Masuk</th>
                      <th style="width: 180px;">Warga</th>
                      <th style="width: 140px;">Jenis</th>
                      <th style="width: 100px; text-align: right;">Berat tercatat</th>
                      <th style="width: 120px; text-align: right;">Nilai perkiraan</th>
                      <th style="width: 100px; text-align: right;">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($antrean as $setoran)
                      <tr>
                          <td style="font-family: var(--font-mono); font-size: 13px; color: var(--muted);">{{ $setoran->created_at->isToday() ? $setoran->created_at->format('H:i') : $setoran->created_at->locale('id')->translatedFormat('j M, H:i') }}</td>
                          <td style="font-weight: 500;">{{ $setoran->masyarakat?->user?->nama ?? 'Warga' }}</td>
                          <td style="color: var(--muted);">{{ $setoran->detailSampah->first()?->jenisSampah?->nama_sampah ?? 'Campuran' }}</td>
                          <td style="text-align: right; font-family: var(--font-mono); font-weight: 500;">{{ number_format($setoran->total_berat, 1) }} kg</td>
                          <td style="text-align: right; font-family: var(--font-mono); font-weight: 500;">Rp {{ number_format($setoran->total_nilai, 0) }}</td>
                          <td style="text-align: right;">
                              <button style="background: var(--primary); color: #FFFFFF; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer;">
                                  Timbang
                              </button>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
        </div>
    @endif
</div>
@endsection