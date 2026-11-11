<?php

namespace Tests\Feature;

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
        User::factory()->create(['name' => 'Bu Siti Rahayu']);

        $this->get(route('bank.penerimaan'))
            ->assertOk()
            ->assertSee('Catat setoran sampah')
            ->assertSee('Belum ada setoran hari ini.')
            ->assertSee('Rp 0');
    }

    public function test_ringkasan_hanya_menghitung_setoran_hari_ini(): void
    {
        $warga = User::factory()->create(['name' => 'Pak Budi Santoso']);

        foreach ([[now()->toDateString(), 10, 25000], [now()->subDay()->toDateString(), 5, 12500]] as [$tanggal, $berat, $nilai]) {
            $setoran = Setoran::query()->create([
                'user_id' => $warga->id,
                'tanggal' => $tanggal,
                'total_berat' => $berat,
                'total_nilai' => $nilai,
                'status' => Setoran::STATUS_MENUNGGU,
            ]);

            $setoran->detailSampah()->create([
                'jenis_sampah' => 'Kardus',
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
        $warga = User::factory()->create(['name' => 'Bu Siti Rahayu']);

        $this->post(route('bank.penerimaan.store'), [
            'user_id' => $warga->id,
            'jenis_sampah' => 'Botol PET',
            'berat' => '12,5',
            'harga_per_kg' => '4.500',
            'catatan' => 'Botol sudah dipilah.',
        ])
            ->assertRedirect(route('bank.penerimaan'))
            ->assertSessionHas('status');

        $setoran = Setoran::query()->firstOrFail();

        $this->assertSame($warga->id, $setoran->user_id);
        $this->assertSame(Setoran::STATUS_MENUNGGU, $setoran->status);
        $this->assertSame('12.50', $setoran->total_berat);
        $this->assertSame('56250.00', $setoran->total_nilai);
        $this->assertSame('Botol PET', $setoran->detailSampah->first()->jenis_sampah);
    }

    public function test_isian_tidak_valid_ditolak_dengan_pesan_indonesia(): void
    {
        $this->from(route('bank.penerimaan'))
            ->post(route('bank.penerimaan.store'), [
                'user_id' => null,
                'jenis_sampah' => null,
                'berat' => null,
                'harga_per_kg' => null,
            ])
            ->assertRedirect(route('bank.penerimaan'))
            ->assertSessionHasErrors(['user_id', 'jenis_sampah', 'berat', 'harga_per_kg']);

        $this->assertDatabaseCount('setoran', 0);

        $this->followingRedirects()
            ->from(route('bank.penerimaan'))
            ->post(route('bank.penerimaan.store'), [
                'user_id' => null,
                'jenis_sampah' => null,
                'berat' => null,
                'harga_per_kg' => null,
            ])
            ->assertSee('Berat sampah belum diisi. Isi dulu sebelum menyimpan.')
            ->assertSee('Setoran belum tersimpan. Periksa isian berikut.');
    }
}
