<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPetaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('admin.peta'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_admin_melihat_peta_dengan_data_pin_bank_berkoordinat(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $bank = $this->buatBank(['latitude' => -7.123456, 'longitude' => 110.987654]);

        $this->actingAs($admin)
            ->get(route('admin.peta'))
            ->assertOk()
            ->assertSee('Peta bank sampah')
            ->assertSee('Bank sampah terdaftar')
            ->assertSee('Sudah berkoordinat, tampil di peta')
            ->assertSee('Bank Sampah Sumber Rejeki')
            ->assertSee('-7.123456')
            ->assertSee('?bank='.$bank->id, false);
    }

    public function test_bank_tanpa_koordinat_masuk_daftar_bukan_pin(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->buatBank(['latitude' => -7.123456, 'longitude' => 110.987654]);
        $this->buatBank([
            'kode_bank' => 'BS-002',
            'nama_bank' => 'Bank Sampah Tunas Hijau',
            'alamat' => 'Dusun Krajan',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.peta'))
            ->assertOk()
            ->assertSee('Belum berkoordinat')
            ->assertSee('Bank Sampah Tunas Hijau')
            ->assertSee('Koordinat belum diisi')
            ->assertSee('Dusun Krajan');

        // Pin di JSON hanya memuat bank yang berkoordinat: satu objek pin, bukan dua.
        $this->assertSame(1, substr_count((string) $response->getContent(), '"nama":'));
    }

    public function test_halaman_tanpa_bank_sampah_menampilkan_empty_state(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('admin.peta'))
            ->assertOk()
            ->assertSee('Belum ada bank sampah terdaftar.');
    }

    private function buatBank(array $data = []): BankSampah
    {
        return BankSampah::query()->firstOrCreate(
            ['kode_bank' => $data['kode_bank'] ?? 'BS-001'],
            [
                'nama_bank' => $data['nama_bank'] ?? 'Bank Sampah Sumber Rejeki',
                'penanggung_jawab' => 'Pak Hendra Wijaya',
                'no_hp' => '0812-3456-7891',
                'alamat' => $data['alamat'] ?? 'Balai Desa Sukamaju',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'jam_operasional' => 'Senin sampai Sabtu, 07.00-15.00',
                'status' => BankSampah::STATUS_AKTIF,
            ],
        );
    }
}
