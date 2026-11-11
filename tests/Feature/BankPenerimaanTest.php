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

class BankPenerimaanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_halaman_menampilkan_empty_state_saat_belum_ada_setoran(): void
    {
        $this->buatBank();
        $this->buatWarga();

        $this->get(route('bank.penerimaan'))
            ->assertOk()
            ->assertSee('Catat setoran sampah')
            ->assertSee('Belum ada setoran hari ini.')
            ->assertSee('Rp 0');
    }

    public function test_ringkasan_hanya_menghitung_setoran_hari_ini(): void
    {
        $warga = $this->buatWarga('Pak Budi Santoso');
        $jenis = $this->buatJenis();
        $bank = $this->buatBank();

        foreach ([[now()->toDateString(), 10, 25000], [now()->subDay()->toDateString(), 5, 12500]] as $indeks => [$tanggal, $berat, $nilai]) {
            $setoran = Setoran::query()->create([
                'kode_setoran' => 'ST-'.($indeks + 1).'-UJI',
                'masyarakat_id' => $warga->id,
                'bank_sampah_id' => $bank->id,
                'tanggal_setoran' => $tanggal,
                'total_berat' => $berat,
                'total_nilai' => $nilai,
                'status' => Setoran::STATUS_MENUNGGU,
            ]);

            $setoran->detailSampah()->create([
                'jenis_sampah_id' => $jenis->id,
                'berat' => $berat,
                'harga_per_kg' => 2500,
                'subtotal' => $nilai,
            ]);
        }

        $this->get(route('bank.penerimaan'))
            ->assertOk()
            ->assertSee('1 setoran')
            ->assertSee('10 kg')
            ->assertSee('Rp 25.000')
            ->assertDontSee('Rp 37.500');
    }

    public function test_setoran_tersimpan_dengan_status_menunggu_timbang(): void
    {
        $warga = $this->buatWarga('Bu Siti Rahayu');
        $jenis = $this->buatJenis();
        $bank = $this->buatBank();

        $this->post(route('bank.penerimaan.store'), [
            'masyarakat_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat' => '12,5',
            'harga_per_kg' => '4.500',
            'catatan' => 'Botol sudah dipilah.',
        ])
            ->assertRedirect(route('bank.penerimaan'))
            ->assertSessionHas('status');

        $setoran = Setoran::query()->firstOrFail();
        $detail = DetailSetoran::query()->firstOrFail();

        $this->assertSame($warga->id, $setoran->masyarakat_id);
        $this->assertSame($bank->id, $setoran->bank_sampah_id);
        $this->assertNull($setoran->petugas_id);
        $this->assertSame(Setoran::STATUS_MENUNGGU, $setoran->status);
        $this->assertSame('12.50', $setoran->total_berat);
        $this->assertSame('56250.00', $setoran->total_nilai);
        $this->assertMatchesRegularExpression('/^ST-\d{8}-001$/', $setoran->kode_setoran);

        $this->assertSame($jenis->id, $detail->jenis_sampah_id);
        $this->assertSame('12.50', $detail->berat);
        $this->assertSame('4500.00', $detail->harga_per_kg);
        $this->assertSame('56250.00', $detail->subtotal);
    }

    public function test_isian_tidak_valid_ditolak_dengan_pesan_indonesia(): void
    {
        $this->buatBank();
        $this->buatWarga();

        $this->from(route('bank.penerimaan'))
            ->post(route('bank.penerimaan.store'), [
                'masyarakat_id' => null,
                'jenis_sampah_id' => null,
                'berat' => null,
                'harga_per_kg' => null,
            ])
            ->assertRedirect(route('bank.penerimaan'))
            ->assertSessionHasErrors(['masyarakat_id', 'jenis_sampah_id', 'berat', 'harga_per_kg']);

        $this->assertDatabaseCount('setoran', 0);

        $this->followingRedirects()
            ->from(route('bank.penerimaan'))
            ->post(route('bank.penerimaan.store'), [
                'masyarakat_id' => null,
                'jenis_sampah_id' => null,
                'berat' => null,
                'harga_per_kg' => null,
            ])
            ->assertSee('Berat sampah belum diisi. Isi dulu sebelum menyimpan.')
            ->assertSee('Setoran belum tersimpan. Periksa isian berikut.');
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

    private function buatWarga(string $nama = 'Bu Siti Rahayu'): Masyarakat
    {
        $user = User::factory()->create(['nama' => $nama]);

        return Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-'.str_pad((string) (Masyarakat::query()->count() + 1), 3, '0', STR_PAD_LEFT),
        ]);
    }

    private function buatJenis(): JenisSampah
    {
        return JenisSampah::query()->firstOrCreate(
            ['nama_sampah' => 'Botol PET'],
            [
                'kategori' => 'Plastik',
                'harga_per_kg' => 4500,
                'satuan' => 'kg',
                'status' => JenisSampah::STATUS_AKTIF,
            ],
        );
    }
}
