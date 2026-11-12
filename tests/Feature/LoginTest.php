<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_halaman_login_menampilkan_form_dan_tombol_demo(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke sistem')
            ->assertSee('Email')
            ->assertSee('Kata sandi')
            ->assertSee('Admin Desa')
            ->assertSee('Bank Sampah')
            ->assertSee('Warga')
            ->assertSee('Mitra');
    }

    public function test_login_manual_admin_diarahkan_ke_laporan_desa(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->post(route('login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_manual_gagal_menampilkan_pesan_konkret(): void
    {
        User::factory()->create(['email' => 'admin@desasirkular.test']);

        $this->followingRedirects()
            ->from(route('login'))
            ->post(route('login.attempt'), [
                'email' => 'admin@desasirkular.test',
                'password' => 'bukan-kata-sandi',
            ])
            ->assertSee('Email atau kata sandi belum cocok. Periksa lagi isianmu.')
            ->assertSee('Masuk belum berhasil. Periksa isian berikut.');

        $this->assertGuest();
    }

    public function test_login_tanpa_kata_sandi_ditolak_dengan_pesan_indonesia(): void
    {
        $this->followingRedirects()
            ->from(route('login'))
            ->post(route('login.attempt'), [
                'email' => 'admin@desasirkular.test',
                'password' => '',
            ])
            ->assertSee('Kata sandi belum diisi. Isi dulu sebelum masuk.');

        $this->assertGuest();
    }

    public function test_masuk_cepat_mengarahkan_ke_halaman_peran_masing_masing(): void
    {
        $akunPeran = [
            'admin' => [User::ROLE_ADMIN, route('admin.dashboard')],
            'bank' => [User::ROLE_BANK, route('bank.penerimaan')],
            'warga' => [User::ROLE_MASYARAKAT, route('warga.beranda')],
            'mitra' => [User::ROLE_MITRA, route('mitra.katalog')],
        ];

        foreach ($akunPeran as $slug => [$role, $tujuan]) {
            $user = User::factory()->create(['role' => $role]);

            $this->post(route('login.demo', $slug))
                ->assertRedirect($tujuan);

            $this->assertAuthenticatedAs($user);
            $this->post(route('logout'));
            $this->assertGuest();
        }
    }

    public function test_masuk_cepat_tanpa_akun_menampilkan_pesan_konkret(): void
    {
        $this->from(route('login'))
            ->post(route('login.demo', 'bank'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['demo']);
    }

    public function test_keluar_mengembalikan_ke_halaman_utama(): void
    {
        $warga = User::factory()->create(['role' => User::ROLE_MASYARAKAT]);

        $this->actingAs($warga)
            ->post(route('logout'))
            ->assertRedirect(route('landing'));

        $this->assertGuest();
    }
}
