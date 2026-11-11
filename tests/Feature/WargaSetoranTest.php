<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\DetailSetoran;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaSetoranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_halaman_pengajuan_menampilkan_form_dari_database(): void
    {
        $this->buatBank();
        $this->buatJenis();
        $this->buatWarga();

        $this->get(route('warga.setor'))
            ->assertOk()
            ->assertSee('Ajukan setoran')
            ->assertSee('Bu Siti Rahayu')
            ->assertSee('Botol PET')
            ->assertSee('Kirim pengajuan')
            ->assertSee('Menunggu timbang')
            ->assertSee('data-pengajuan-form', false)
            ->assertSee('data-estimasi-warga', false)
            ->assertSee('data-estimasi-angka', false)
            ->assertSee('aria-live="polite"', false);
    }

    public function test_halaman_menampilkan_empty_state_saat_data_warga_tidak_ada(): void
    {
        $this->buatBank();
        $this->buatJenis();

        $this->get(route('warga.setor'))
            ->assertOk()
            ->assertSee('Data warga belum tersedia.')
            ->assertDontSee('data-pengajuan-form', false);
    }

    public function test_jenis_sampah_nonaktif_tidak_tampil_dan_harga_dianggap_kosong(): void
    {
        $this->buatBank();
        $this->buatWarga();
        $this->buatJenis('Kardus', 2500, JenisSampah::STATUS_NONAKTIF);

        $this->get(route('warga.setor'))
            ->assertOk()
            ->assertSee('Harga acuan belum tersedia.')
            ->assertDontSee('Kardus')
            ->assertDontSee('data-pengajuan-form', false);
    }

    public function test_pengajuan_tersimpan_untuk_bu_siti_dengan_status_menunggu_dan_harga_server(): void
    {
        $warga = $this->buatWarga();
        $jenis = $this->buatJenis();
        $bank = $this->buatBank();

        $response = $this->post(route('warga.setor.store'), [
            'jenis_sampah_id' => $jenis->id,
            'berat' => '12,5',
            'harga_per_kg' => '1',
            'catatan' => 'Botol sudah dipilah.',
        ]);

        $response
            ->assertRedirect(route('warga.beranda'))
            ->assertSessionHas('status');

        $status = (string) session('status');

        $this->assertStringContainsString('tercatat', $status);
        $this->assertStringContainsString('Menunggu timbang', $status);
        $this->assertStringContainsString('Bank Sampah Sumber Rejeki', $status);
        $this->assertStringContainsString('Rp 56.250', $status);

        $setoran = Setoran::query()->firstOrFail();
        $detail = DetailSetoran::query()->firstOrFail();

        $this->assertSame($warga->id, $setoran->masyarakat_id);
        $this->assertSame($bank->id, $setoran->bank_sampah_id);
        $this->assertNull($setoran->petugas_id);
        $this->assertSame(Setoran::STATUS_MENUNGGU, $setoran->status);
        $this->assertSame('12.50', $setoran->total_berat);
        $this->assertSame('56250.00', $setoran->total_nilai);
        $this->assertSame('Botol sudah dipilah.', $setoran->catatan);
        $this->assertMatchesRegularExpression('/^ST-\d{8}-001$/', $setoran->kode_setoran);

        $this->assertSame($jenis->id, $detail->jenis_sampah_id);
        $this->assertSame('12.50', $detail->berat);
        $this->assertSame('4500.00', $detail->harga_per_kg);
        $this->assertSame('56250.00', $detail->subtotal);
    }

    public function test_masyarakat_yang_login_dipakai_sebagai_pemilik_pengajuan(): void
    {
        $this->buatWarga();
        $budi = $this->buatWarga('Pak Budi Santoso', 'budi');
        $jenis = $this->buatJenis();
        $this->buatBank();

        $this->actingAs($budi->user)
            ->post(route('warga.setor.store'), [
                'jenis_sampah_id' => $jenis->id,
                'berat' => '2',
            ])
            ->assertRedirect(route('warga.beranda'));

        $this->assertSame($budi->id, Setoran::query()->firstOrFail()->masyarakat_id);
    }

    public function test_isian_tidak_valid_ditolak_dengan_pesan_indonesia(): void
    {
        $this->buatBank();
        $this->buatWarga();
        $this->buatJenis();

        $this->from(route('warga.setor'))
            ->post(route('warga.setor.store'), [
                'jenis_sampah_id' => null,
                'berat' => null,
                'catatan' => str_repeat('a', 501),
            ])
            ->assertRedirect(route('warga.setor'))
            ->assertSessionHasErrors(['jenis_sampah_id', 'berat', 'catatan']);

        $this->assertDatabaseCount('setoran', 0);

        $this->followingRedirects()
            ->from(route('warga.setor'))
            ->post(route('warga.setor.store'), [
                'jenis_sampah_id' => null,
                'berat' => null,
                'catatan' => str_repeat('a', 501),
            ])
            ->assertSee('Pengajuan belum terkirim. Periksa isian berikut.')
            ->assertSee('Pilih jenis sampah dulu.')
            ->assertSee('Berat sampah belum diisi. Isi dulu sebelum mengirim.')
            ->assertSee('Catatan maksimal 500 karakter.');
    }

    public function test_berat_di_atas_500_kg_ditolak(): void
    {
        $this->buatBank();
        $this->buatWarga();
        $jenis = $this->buatJenis();

        $this->from(route('warga.setor'))
            ->post(route('warga.setor.store'), [
                'jenis_sampah_id' => $jenis->id,
                'berat' => '500,1',
            ])
            ->assertRedirect(route('warga.setor'))
            ->assertSessionHasErrors('berat');

        $this->assertDatabaseCount('setoran', 0);
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

    private function buatWarga(string $nama = 'Bu Siti Rahayu', string $username = 'siti'): Masyarakat
    {
        $user = User::factory()->create([
            'nama' => $nama,
            'username' => $username,
        ]);

        return Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-'.str_pad((string) (Masyarakat::query()->count() + 1), 3, '0', STR_PAD_LEFT),
        ]);
    }

    private function buatJenis(
        string $nama = 'Botol PET',
        int $harga = 4500,
        string $status = JenisSampah::STATUS_AKTIF,
    ): JenisSampah {
        return JenisSampah::query()->create([
            'nama_sampah' => $nama,
            'kategori' => 'Plastik',
            'harga_per_kg' => $harga,
            'satuan' => 'kg',
            'status' => $status,
        ]);
    }
}
