<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaEstimasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_halaman_estimasi_menampilkan_harga_dari_database(): void
    {
        $this->buatBank();
        $this->buatJenis('Botol PET', 'Plastik', 4500);
        $this->buatJenis('Kardus', 'Kertas', 2500);

        $this->get(route('warga.estimasi'))
            ->assertOk()
            ->assertSee('Estimasi nilai sampah')
            ->assertSee('Botol PET')
            ->assertSee('Plastik')
            ->assertSee('Rp 4.500')
            ->assertSee('Bank Sampah Sumber Rejeki')
            ->assertSee('Ajukan Setoran')
            ->assertSee('data-estimasi-warga', false)
            ->assertSee('aria-live="polite"', false);
    }

    public function test_halaman_estimasi_menampilkan_empty_state_saat_harga_kosong(): void
    {
        $this->get(route('warga.estimasi'))
            ->assertOk()
            ->assertSee('Harga acuan belum tersedia')
            ->assertDontSee('Ajukan Setoran')
            ->assertDontSee('data-estimasi-warga', false);
    }

    public function test_jenis_sampah_nonaktif_tidak_tampil(): void
    {
        $this->buatJenis('Botol PET', 'Plastik', 4500);
        $this->buatJenis('Kardus', 'Kertas', 2500, JenisSampah::STATUS_NONAKTIF);

        $this->get(route('warga.estimasi'))
            ->assertOk()
            ->assertSee('Botol PET')
            ->assertDontSee('Kardus');
    }

    public function test_navigasi_warga_mengarah_ke_halaman_yang_ada(): void
    {
        $this->get(route('warga.beranda'))
            ->assertOk()
            ->assertSee('Beranda warga');

        $this->get(route('warga.setor'))
            ->assertOk()
            ->assertSee('Ajukan setoran');

        $this->get(route('warga.riwayat'))
            ->assertOk()
            ->assertSee('Riwayat setoran');

        $this->get(route('warga.edukasi'))
            ->assertOk()
            ->assertSee('Edukasi sampah');
    }

    public function test_alamat_warga_diarahkan_ke_beranda(): void
    {
        $this->get('/warga')
            ->assertRedirect(route('warga.beranda'));
    }

    private function buatBank(): BankSampah
    {
        return BankSampah::query()->create([
            'kode_bank' => 'BS-001',
            'nama_bank' => 'Bank Sampah Sumber Rejeki',
            'penanggung_jawab' => 'Pak Hendra Wijaya',
            'no_hp' => '0812-3456-7891',
            'alamat' => 'Balai Desa Sukamaju',
            'jam_operasional' => 'Senin sampai Sabtu, 07.00-15.00',
            'status' => BankSampah::STATUS_AKTIF,
        ]);
    }

    private function buatJenis(
        string $nama,
        string $kategori,
        int $harga,
        string $status = JenisSampah::STATUS_AKTIF,
    ): JenisSampah {
        return JenisSampah::query()->create([
            'nama_sampah' => $nama,
            'kategori' => $kategori,
            'harga_per_kg' => $harga,
            'satuan' => 'kg',
            'status' => $status,
        ]);
    }
}
