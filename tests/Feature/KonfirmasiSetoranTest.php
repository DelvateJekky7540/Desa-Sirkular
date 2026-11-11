<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\MutasiSaldo;
use App\Models\Saldo;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KonfirmasiSetoranTest extends TestCase
{
    use RefreshDatabase;

    public function test_konfirmasi_menambah_saldo_dan_membuat_satu_mutasi(): void
    {
        $user = User::factory()->create(['nama' => 'Bu Siti Rahayu']);

        $masyarakat = Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-001',
        ]);

        $saldo = Saldo::query()->create([
            'masyarakat_id' => $masyarakat->id,
            'jumlah_saldo' => 0,
        ]);

        $bank = BankSampah::query()->create([
            'kode_bank' => 'BS-001',
            'nama_bank' => 'Bank Sampah Sumber Rejeki',
            'penanggung_jawab' => 'Pak Hendra Wijaya',
            'no_hp' => '0812-3456-7891',
            'alamat' => 'Balai Desa Sukamaju',
            'jam_operasional' => 'Senin sampai Sabtu, 07.00-15.00',
            'status' => BankSampah::STATUS_AKTIF,
        ]);

        $jenis = JenisSampah::query()->create([
            'nama_sampah' => 'Botol PET',
            'kategori' => 'Plastik',
            'harga_per_kg' => 4500,
            'satuan' => 'kg',
            'status' => JenisSampah::STATUS_AKTIF,
        ]);

        $setoran = Setoran::query()->create([
            'kode_setoran' => 'ST-20260101-001',
            'masyarakat_id' => $masyarakat->id,
            'bank_sampah_id' => $bank->id,
            'tanggal_setoran' => now()->toDateString(),
            'total_berat' => 12.5,
            'total_nilai' => 56250,
            'status' => Setoran::STATUS_DITIMBANG,
        ]);

        $setoran->detailSampah()->create([
            'jenis_sampah_id' => $jenis->id,
            'berat' => 12.5,
            'harga_per_kg' => 4500,
            'subtotal' => 56250,
        ]);

        $this->assertTrue($setoran->konfirmasi());
        $this->assertSame(Setoran::STATUS_DIKONFIRMASI, $setoran->refresh()->status);
        $this->assertSame('56250.00', $saldo->refresh()->jumlah_saldo);
        $this->assertDatabaseCount('mutasi_saldo', 1);

        $mutasi = MutasiSaldo::query()->firstOrFail();
        $this->assertSame($setoran->id, $mutasi->setoran_id);
        $this->assertSame($saldo->id, $mutasi->saldo_id);
        $this->assertSame(MutasiSaldo::TIPE_MASUK, $mutasi->tipe);
        $this->assertSame('56250.00', $mutasi->nominal);

        $this->assertFalse($setoran->konfirmasi());
        $this->assertSame('56250.00', $saldo->refresh()->jumlah_saldo);
        $this->assertDatabaseCount('mutasi_saldo', 1);
    }
}
