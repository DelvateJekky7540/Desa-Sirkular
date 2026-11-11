<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankPenimbanganTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_halaman_menampilkan_antrean_dan_mengabaikan_setoran_yang_sudah_ditimban(): void
    {
        $menunggu = $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_MENUNGGU);
        $this->buatSetoran('Pak Budi Santoso', 8, Setoran::STATUS_DITIMBANG);

        $this->get(route('bank.penimbangan'))
            ->assertOk()
            ->assertSee('Antrean penimbangan')
            ->assertSee('Bu Siti Rahayu')
            ->assertSee('Botol PET')
            ->assertSee('12,5 kg')
            ->assertSee('data-buka-timbang="dialog-timbang-'.$menunggu->id.'"', false)
            ->assertDontSee('Pak Budi Santoso')
            ->assertDontSee('Antrean penimbangan kosong.');
    }

    public function test_antrean_hanya_menampilkan_setoran_bank_sampah_yang_sedang_dibuka(): void
    {
        $this->buatBank();

        $bankLain = BankSampah::query()->create([
            'kode_bank' => 'BS-002',
            'nama_bank' => 'Bank Sampah Tunas Hijau',
            'penanggung_jawab' => 'Bu Ratna Sari',
            'no_hp' => '0812-3456-7892',
            'alamat' => 'Dusun Krajan',
            'jam_operasional' => 'Selasa dan Kamis, 08.00-12.00',
            'status' => BankSampah::STATUS_AKTIF,
        ]);

        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_MENUNGGU);
        $this->buatSetoran('Pak Budi Santoso', 8, Setoran::STATUS_MENUNGGU, $bankLain);

        $this->get(route('bank.penimbangan'))
            ->assertOk()
            ->assertSee('Bu Siti Rahayu')
            ->assertDontSee('Pak Budi Santoso');
    }

    public function test_halaman_menampilkan_empty_state_saat_antrean_kosong(): void
    {
        $this->buatBank();

        $this->get(route('bank.penimbangan'))
            ->assertOk()
            ->assertSee('Antrean penimbangan kosong.')
            ->assertSee('Catat setoran baru');
    }

    public function test_hasil_timbang_menimpa_berat_dan_nilai_lalu_mengubah_status(): void
    {
        $setoran = $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_MENUNGGU);

        $this->post(route('bank.penimbangan.store', $setoran), [
            'berat' => '12,8',
            'harga_per_kg' => '4.500',
        ])
            ->assertRedirect(route('bank.penimbangan'))
            ->assertSessionHas('status', 'Setoran Bu Siti Rahayu sudah ditimbang: 12,8 kg Botol PET senilai Rp 57.600.');

        $setoran->refresh();
        $detail = $setoran->detailSampah()->with('jenisSampah')->firstOrFail();

        $this->assertSame(Setoran::STATUS_DITIMBANG, $setoran->status);
        $this->assertSame('12.80', $setoran->total_berat);
        $this->assertSame('57600.00', $setoran->total_nilai);
        $this->assertSame('12.80', $detail->berat);
        $this->assertSame('4500.00', $detail->harga_per_kg);
        $this->assertSame('57600.00', $detail->subtotal);
        $this->assertSame('Botol PET', $detail->jenisSampah?->nama_sampah);
    }

    public function test_isian_tidak_valid_membuka_dialog_yang_gagal_dengan_pesan_indonesia(): void
    {
        $setoran = $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_MENUNGGU);

        $this->from(route('bank.penimbangan'))
            ->post(route('bank.penimbangan.store', $setoran), [
                'berat' => '',
                'harga_per_kg' => '4.500',
            ])
            ->assertRedirect(route('bank.penimbangan'))
            ->assertSessionHasErrors(['berat'])
            ->assertSessionHas('penimbangan_id', $setoran->id);

        $this->assertSame(Setoran::STATUS_MENUNGGU, $setoran->refresh()->status);

        $this->followingRedirects()
            ->from(route('bank.penimbangan'))
            ->post(route('bank.penimbangan.store', $setoran), [
                'berat' => '',
                'harga_per_kg' => '4.500',
            ])
            ->assertSee('Berat aktual belum diisi. Isi dulu sebelum menyimpan.')
            ->assertSee('data-buka-otomatis="dialog-timbang-'.$setoran->id.'"', false);
    }

    public function test_setoran_yang_sudah_ditimban_tidak_bisa_ditimban_lagi(): void
    {
        $setoran = $this->buatSetoran('Pak Budi Santoso', 15, Setoran::STATUS_DITIMBANG);

        $this->post(route('bank.penimbangan.store', $setoran), [
            'berat' => '20',
            'harga_per_kg' => '3.600',
        ])
            ->assertRedirect(route('bank.penimbangan'))
            ->assertSessionHas('peringatan', 'Setoran Pak Budi Santoso sudah ditimbang sebelumnya. Antrean sudah diperbarui.');

        $this->assertSame('15.00', $setoran->refresh()->total_berat);
        $this->assertSame(Setoran::STATUS_DITIMBANG, $setoran->status);
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

    private function buatSetoran(string $nama, float $berat, string $status, ?BankSampah $bank = null): Setoran
    {
        $bank ??= $this->buatBank();

        $user = User::factory()->create(['nama' => $nama]);

        $masyarakat = Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-'.str_pad((string) (Masyarakat::query()->count() + 1), 3, '0', STR_PAD_LEFT),
        ]);

        $jenis = JenisSampah::query()->firstOrCreate(
            ['nama_sampah' => 'Botol PET'],
            [
                'kategori' => 'Plastik',
                'harga_per_kg' => 4500,
                'satuan' => 'kg',
                'status' => JenisSampah::STATUS_AKTIF,
            ],
        );

        $setoran = Setoran::query()->create([
            'kode_setoran' => 'ST-UJI-'.uniqid(),
            'masyarakat_id' => $masyarakat->id,
            'bank_sampah_id' => $bank->id,
            'tanggal_setoran' => now()->toDateString(),
            'total_berat' => $berat,
            'total_nilai' => $berat * 4500,
            'status' => $status,
            'catatan' => 'Botol sudah dipilah.',
        ]);

        $setoran->detailSampah()->create([
            'jenis_sampah_id' => $jenis->id,
            'berat' => $berat,
            'harga_per_kg' => 4500,
            'subtotal' => $berat * 4500,
        ]);

        return $setoran;
    }
}
