<?php

namespace Tests\Feature;

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
        $this->buatSetoran('Pak Budi Santoso', 8, Setoran::STATUS_SUDAH_DITIMBANG);

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

    public function test_halaman_menampilkan_empty_state_saat_antrean_kosong(): void
    {
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
        $detail = $setoran->detailSampah()->firstOrFail();

        $this->assertSame(Setoran::STATUS_SUDAH_DITIMBANG, $setoran->status);
        $this->assertSame('12.80', $setoran->total_berat);
        $this->assertSame('57600.00', $setoran->total_nilai);
        $this->assertSame('12.80', $detail->berat);
        $this->assertSame('4500.00', $detail->harga_per_kg);
        $this->assertSame('57600.00', $detail->subtotal);
        $this->assertSame('Botol PET', $detail->jenis_sampah);
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
        $setoran = $this->buatSetoran('Pak Budi Santoso', 15, Setoran::STATUS_SUDAH_DITIMBANG);

        $this->post(route('bank.penimbangan.store', $setoran), [
            'berat' => '20',
            'harga_per_kg' => '3.600',
        ])
            ->assertRedirect(route('bank.penimbangan'))
            ->assertSessionHas('peringatan', 'Setoran Pak Budi Santoso sudah ditimbang sebelumnya. Antrean sudah diperbarui.');

        $this->assertSame('15.00', $setoran->refresh()->total_berat);
        $this->assertSame(Setoran::STATUS_SUDAH_DITIMBANG, $setoran->status);
    }

    private function buatSetoran(string $nama, float $berat, string $status): Setoran
    {
        $warga = User::factory()->create(['name' => $nama]);
        $harga = 4500;

        $setoran = Setoran::query()->create([
            'user_id' => $warga->id,
            'tanggal' => now()->toDateString(),
            'total_berat' => $berat,
            'total_nilai' => $berat * $harga,
            'status' => $status,
            'catatan' => 'Botol sudah dipilah.',
        ]);

        $setoran->detailSampah()->create([
            'jenis_sampah' => 'Botol PET',
            'berat' => $berat,
            'harga_per_kg' => $harga,
            'subtotal' => $berat * $harga,
        ]);

        return $setoran;
    }
}
