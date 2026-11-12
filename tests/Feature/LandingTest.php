<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_landing_menampilkan_hero_dan_tautan_masuk(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Sampah desa, rantai yang tidak putus.')
            ->assertSee('Masuk ke sistem')
            ->assertSee('Lima peran, satu rantai')
            ->assertSee('Enam fitur unggulan')
            ->assertSee('Dari rumah warga ke pabrik');
    }

    public function test_landing_menampilkan_angka_dari_data_demo(): void
    {
        $bank = $this->buatBank();
        $warga = $this->buatWarga();

        $this->buatSetoran($bank, $warga, Setoran::STATUS_DITIMBANG, 10, 25000);
        $this->buatSetoran($bank, $warga, Setoran::STATUS_DIKONFIRMASI, 5, 12500);
        $this->buatSetoran($bank, $warga, Setoran::STATUS_MENUNGGU, 7, 0);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('15 kg')
            ->assertSee('Rp 37.500')
            ->assertDontSee('22 kg')
            ->assertDontSee('Rp 44.500');
    }

    public function test_landing_tetap_tampil_saat_tabel_setoran_kosong(): void
    {
        $this->buatBank();

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('0 kg')
            ->assertSee('Rp 0');
    }

    public function test_landing_menampilkan_tautan_lacak_sampah(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(route('tracking.form'))
            ->assertSee('Lacak sampah');
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

    private function buatSetoran(BankSampah $bank, Masyarakat $warga, string $status, float $berat, int $nilai): Setoran
    {
        return Setoran::query()->create([
            'kode_setoran' => 'ST-'.uniqid().'-UJI',
            'masyarakat_id' => $warga->id,
            'bank_sampah_id' => $bank->id,
            'tanggal_setoran' => now()->toDateString(),
            'total_berat' => $berat,
            'total_nilai' => $nilai,
            'status' => $status,
        ]);
    }
}
