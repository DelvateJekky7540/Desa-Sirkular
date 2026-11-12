<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_admin_melihat_ringkasan_dan_setoran_terbaru(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $bank = $this->buatBank();
        $siti = $this->buatWarga('Bu Siti Rahayu');
        $budi = $this->buatWarga('Pak Budi Santoso');

        $this->buatSetoran($bank, $siti, Setoran::STATUS_DITIMBANG, 10, 25000);
        $this->buatSetoran($bank, $budi, Setoran::STATUS_DIKONFIRMASI, 5, 12500);
        $this->buatSetoran($bank, $siti, Setoran::STATUS_MENUNGGU, 7, 0);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Laporan desa')
            ->assertSee('Total sampah tercatat')
            ->assertSee('15 kg')
            ->assertSee('5 kg')
            ->assertSee('Rp 37.500')
            ->assertSee('Bu Siti Rahayu')
            ->assertSee('Pak Budi Santoso')
            ->assertSee('Sudah dikonfirmasi')
            ->assertSee('Menunggu timbang');
    }

    public function test_halaman_tanpa_setoran_menampilkan_empty_state(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->buatBank();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Belum ada setoran tercatat.')
            ->assertSee('0 kg');
    }

    public function test_setoran_dibatalkan_tidak_masuk_ringkasan(): void
    {
        // Setoran dibatalkan tidak dihitung di ringkasan.
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $bank = $this->buatBank();
        $warga = $this->buatWarga('Bu Siti Rahayu');

        $this->buatSetoran($bank, $warga, Setoran::STATUS_DIBATALKAN, 30, 90000);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('0 kg')
            ->assertSee('Rp 0')
            ->assertDontSee('30 kg');
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

    private function buatWarga(string $nama): Masyarakat
    {
        $user = User::factory()->create(['nama' => $nama]);

        return Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-'.str_pad((string) (Masyarakat::query()->count() + 1), 3, '0', STR_PAD_LEFT),
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
