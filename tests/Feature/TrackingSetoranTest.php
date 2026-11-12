<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingSetoranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_form_lacak_tampil_tanpa_login(): void
    {
        $this->get(route('tracking.form'))
            ->assertOk()
            ->assertSee('Lacak setoran sampah')
            ->assertSee('Kode setoran')
            ->assertSee('Lacak setoran');
    }

    public function test_form_dengan_kode_valid_redirect_ke_halaman_hasil(): void
    {
        $this->get(route('tracking.form', ['kode' => 'st-20261112-001']))
            ->assertRedirect(route('tracking.show', ['kode_setoran' => 'ST-20261112-001']));
    }

    public function test_form_menolak_kode_dengan_format_salah(): void
    {
        $this->get(route('tracking.form', ['kode' => '12345']))
            ->assertOk()
            ->assertSee('Format kode setoran belum tepat')
            ->assertSee('ST-20261112-001');
    }

    public function test_show_menampilkan_tahapan_setoran_yang_sudah_ditimbang(): void
    {
        $bank = $this->buatBank();
        $warga = $this->buatWarga();
        $jenis = $this->buatJenis();

        $setoran = $this->buatSetoran($bank, $warga, 'ST-20260101-001', Setoran::STATUS_DITIMBANG, 14.8, 66600);
        $this->buatDetail($setoran, $jenis, 14.8, 4500);

        $this->get(route('tracking.show', ['kode_setoran' => 'ST-20260101-001']))
            ->assertOk()
            ->assertSee('Perjalanan setoran sampah')
            ->assertSee('ST-20260101-001')
            ->assertSee('Sudah ditimbang')
            ->assertSee('Disetor oleh warga')
            ->assertSee('Bu Siti Rahayu')
            ->assertSee('Ditimbang oleh bank sampah')
            ->assertSee('Bank Sampah Sumber Rejeki')
            ->assertSee('14,8 kg')
            ->assertSee('Menunggu konfirmasi bank sampah')
            ->assertSee('Dijual ke mitra')
            ->assertSee('Menunggu mitra daur ulang');
    }

    public function test_show_setoran_menunggu_hanya_menampilkan_perkiraan(): void
    {
        $bank = $this->buatBank();
        $warga = $this->buatWarga();
        $jenis = $this->buatJenis();

        $setoran = $this->buatSetoran($bank, $warga, 'ST-20260101-002', Setoran::STATUS_MENUNGGU, 12.5, 56250);
        $this->buatDetail($setoran, $jenis, 12.5, 4500);

        $this->get(route('tracking.show', ['kode_setoran' => 'ST-20260101-002']))
            ->assertOk()
            ->assertSee('Menunggu timbang')
            ->assertSee('Perkiraan setoran')
            ->assertSee('12,5 kg')
            ->assertSee('Rp 56.250')
            ->assertSee('Menunggu penimbangan oleh petugas bank sampah')
            ->assertDontSee('Berat final</dt>', false);
    }

    public function test_show_setoran_dikonfirmasi_menampilkan_tahap_tabungan(): void
    {
        $bank = $this->buatBank();
        $warga = $this->buatWarga();
        $jenis = $this->buatJenis();

        $setoran = $this->buatSetoran($bank, $warga, 'ST-20260101-003', Setoran::STATUS_DITIMBANG, 7.5, 33750);
        $this->buatDetail($setoran, $jenis, 7.5, 4500);
        $this->assertTrue($setoran->konfirmasi());

        $this->get(route('tracking.show', ['kode_setoran' => 'ST-20260101-003']))
            ->assertOk()
            ->assertSee('Sudah dikonfirmasi')
            ->assertSee('Dikonfirmasi masuk tabungan')
            ->assertSee('Nilai masuk saldo')
            ->assertSee('Rp 33.750');
    }

    public function test_show_setoran_dibatalkan_berhenti_di_tahap_akhir(): void
    {
        $bank = $this->buatBank();
        $warga = $this->buatWarga();
        $jenis = $this->buatJenis();

        $setoran = $this->buatSetoran($bank, $warga, 'ST-20260101-004', Setoran::STATUS_DIBATALKAN, 5, 22500, 'Dibatalkan, plastik bercampur sisa makanan.');
        $this->buatDetail($setoran, $jenis, 5, 4500);

        $this->get(route('tracking.show', ['kode_setoran' => 'ST-20260101-004']))
            ->assertOk()
            ->assertSee('Dibatalkan')
            ->assertSee('Dibatalkan, plastik bercampur sisa makanan.')
            ->assertDontSee('Dijual ke mitra')
            ->assertDontSee('Ditimbang oleh bank sampah');
    }

    public function test_show_kode_tidak_dikenal_menampilkan_halaman_404_rapi(): void
    {
        $this->get('/tracking/ST-19990101-999')
            ->assertNotFound()
            ->assertSee('Kode setoran tidak ditemukan')
            ->assertSee('ST-19990101-999')
            ->assertSee('Kode setoran')
            ->assertSee('Lacak setoran');
    }

    private function buatBank(): BankSampah
    {
        return BankSampah::query()->firstOrCreate(
            ['kode_bank' => 'BS-001'],
            [
                'nama_bank' => 'Bank Sampah Sumber Rejeki',
                'penanggung_jawab' => 'Pak Hendra Wijaya',
                'no_hp' => '0812-3456-7891',
                'alamat' => 'Balai Desa Sukamaju',
                'jam_operasional' => 'Senin sampai Sabtu, 07.00-15.00',
                'status' => BankSampah::STATUS_AKTIF,
            ],
        );
    }

    private function buatWarga(): Masyarakat
    {
        $user = User::factory()->create(['nama' => 'Bu Siti Rahayu']);

        return Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-001',
        ]);
    }

    private function buatJenis(): JenisSampah
    {
        return JenisSampah::query()->create([
            'nama_sampah' => 'Botol PET',
            'kategori' => 'Plastik',
            'harga_per_kg' => 4500,
            'satuan' => 'kg',
            'status' => JenisSampah::STATUS_AKTIF,
        ]);
    }

    private function buatSetoran(BankSampah $bank, Masyarakat $warga, string $kode, string $status, float $berat, int $nilai, ?string $catatan = null): Setoran
    {
        return Setoran::query()->create([
            'kode_setoran' => $kode,
            'masyarakat_id' => $warga->id,
            'bank_sampah_id' => $bank->id,
            'petugas_id' => null,
            'tanggal_setoran' => now()->toDateString(),
            'total_berat' => $berat,
            'total_nilai' => $nilai,
            'status' => $status,
            'catatan' => $catatan,
        ]);
    }

    private function buatDetail(Setoran $setoran, JenisSampah $jenis, float $berat, int $hargaPerKg): void
    {
        $setoran->detailSampah()->create([
            'jenis_sampah_id' => $jenis->id,
            'berat' => $berat,
            'harga_per_kg' => $hargaPerKg,
            'subtotal' => $berat * $hargaPerKg,
        ]);
    }
}
