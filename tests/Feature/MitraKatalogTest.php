<?php

namespace Tests\Feature;

use App\Models\BankSampah;
use App\Models\JenisSampah;
use App\Models\Masyarakat;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MitraKatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_katalog_hanya_menampilkan_setoran_berstatus_ditimbang_dan_mengagregasi_berat(): void
    {
        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);
        $this->buatSetoran('Pak Budi Santoso', 8, Setoran::STATUS_DITIMBANG);
        $this->buatSetoran('Bu Ani Lestari', 30, Setoran::STATUS_MENUNGGU);
        $this->buatSetoran('Bu Ratna Dewi', 7, Setoran::STATUS_DIKONFIRMASI);

        $this->get(route('mitra.katalog'))
            ->assertOk()
            ->assertSee('Katalog bahan baku')
            ->assertSee('Botol PET')
            ->assertSee('20,5 kg')
            ->assertSee('2 setoran')
            ->assertDontSee('30 kg')
            ->assertDontSee('7 kg');
    }

    public function test_katalog_memisahkan_stok_per_bank_sampah(): void
    {
        $bankLain = BankSampah::query()->create([
            'kode_bank' => 'BS-002',
            'nama_bank' => 'Bank Sampah Tunas Hijau',
            'penanggung_jawab' => 'Bu Ratna Sari',
            'no_hp' => '0812-3456-7892',
            'alamat' => 'Dusun Krajan',
            'jam_operasional' => 'Selasa dan Kamis, 08.00-12.00',
            'status' => BankSampah::STATUS_AKTIF,
        ]);

        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);
        $this->buatSetoran('Pak Budi Santoso', 8, Setoran::STATUS_DITIMBANG, $bankLain);

        $this->get(route('mitra.katalog'))
            ->assertOk()
            ->assertSee('Bank Sampah Sumber Rejeki')
            ->assertSee('12,5 kg')
            ->assertSee('Bank Sampah Tunas Hijau')
            ->assertSee('8 kg')
            ->assertSee('Total 20,5 kg');
    }

    public function test_filter_jenis_sampah_hanya_menampilkan_jenis_terpilih(): void
    {
        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);
        $kertas = $this->buatSetoran('Pak Budi Santoso', 40, Setoran::STATUS_DITIMBANG, null, 'Kertas Karton', 'Kertas');

        $this->get(route('mitra.katalog', ['jenis_sampah_id' => $kertas->detailSampah()->first()->jenis_sampah_id]))
            ->assertOk()
            ->assertSee('Kertas Karton')
            ->assertSee('40 kg')
            ->assertDontSee('12,5 kg');
    }

    public function test_filter_berat_minimum_hanya_menampilkan_stok_di_atas_batas(): void
    {
        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);
        $this->buatSetoran('Pak Budi Santoso', 40, Setoran::STATUS_DITIMBANG, null, 'Kertas Karton', 'Kertas');

        $this->get(route('mitra.katalog', ['berat_min' => '20']))
            ->assertOk()
            ->assertSee('Kertas Karton')
            ->assertSee('40 kg')
            ->assertDontSee('12,5 kg');
    }

    public function test_isian_filter_tidak_valid_menampilkan_pesan_indonesia(): void
    {
        $this->from(route('mitra.katalog'))
            ->get(route('mitra.katalog', ['berat_min' => 'banyak']))
            ->assertSessionHasErrors(['berat_min']);

        $this->followingRedirects()
            ->from(route('mitra.katalog'))
            ->get(route('mitra.katalog', ['berat_min' => 'banyak']))
            ->assertSee('Berat minimum harus berupa angka, contoh 50.');
    }

    public function test_halaman_menampilkan_empty_state_saat_belum_ada_setoran_ditimbang(): void
    {
        $this->buatSetoran('Bu Ani Lestari', 30, Setoran::STATUS_MENUNGGU);

        $this->get(route('mitra.katalog'))
            ->assertOk()
            ->assertSee('Belum ada bahan baku berstatus sudah ditimbang.');
    }

    public function test_filter_tanpa_hasil_menampilkan_pesan_dan_tautan_reset(): void
    {
        $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);

        $jenis = $this->buatSetoran('Pak Budi Santoso', 40, Setoran::STATUS_DITIMBANG, null, 'Kertas Karton', 'Kertas');
        $jenisId = $jenis->detailSampah()->first()->jenis_sampah_id;

        $this->get(route('mitra.katalog', ['jenis_sampah_id' => $jenisId, 'berat_min' => '99']))
            ->assertOk()
            ->assertSee('Tidak ada bahan baku yang cocok dengan filter ini.')
            ->assertSee('Belum ada stok Kertas Karton dengan berat di atas 99 kg.')
            ->assertSee('Reset filter');
    }

    public function test_tombol_ajukan_mengarah_ke_halaman_penawaran(): void
    {
        $setoran = $this->buatSetoran('Bu Siti Rahayu', 12.5, Setoran::STATUS_DITIMBANG);
        $detail = $setoran->detailSampah()->firstOrFail();
        $tautan = route('mitra.penawaran', ['jenis' => $detail->jenis_sampah_id, 'bank' => $setoran->bank_sampah_id]);

        $this->get(route('mitra.katalog'))
            ->assertOk()
            ->assertSee('mitra/penawaran', false)
            ->assertSee('Ajukan');

        $this->get($tautan)
            ->assertOk()
            ->assertSee('Formulir penawaran belum dibuka.')
            ->assertSee('Pilihanmu tercatat: Botol PET di Bank Sampah Sumber Rejeki.')
            ->assertSee('Kembali ke katalog');
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

    private function buatSetoran(string $nama, float $berat, string $status, ?BankSampah $bank = null, string $jenisNama = 'Botol PET', string $kategori = 'Plastik'): Setoran
    {
        $bank ??= $this->buatBank();

        $user = User::factory()->create(['nama' => $nama]);

        $masyarakat = Masyarakat::query()->create([
            'user_id' => $user->id,
            'kode_nasabah' => 'NS-'.str_pad((string) (Masyarakat::query()->count() + 1), 3, '0', STR_PAD_LEFT),
        ]);

        $jenis = JenisSampah::query()->firstOrCreate(
            ['nama_sampah' => $jenisNama],
            [
                'kategori' => $kategori,
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
            'catatan' => $jenisNama.' sudah dipilah.',
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
