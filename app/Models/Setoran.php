<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Setoran extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DITIMBANG = 'ditimbang';

    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * Label status untuk tampilan, disimpan sebagai kode di database.
     *
     * @var array<string, string>
     */
    public const LABEL_STATUS = [
        self::STATUS_MENUNGGU => 'Menunggu timbang',
        self::STATUS_DITIMBANG => 'Sudah ditimbang',
        self::STATUS_DIKONFIRMASI => 'Sudah dikonfirmasi',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    protected $table = 'setoran';

    protected $fillable = [
        'kode_setoran',
        'masyarakat_id',
        'bank_sampah_id',
        'petugas_id',
        'tanggal_setoran',
        'total_berat',
        'total_nilai',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_setoran' => 'date',
            'total_berat' => 'decimal:2',
            'total_nilai' => 'decimal:2',
        ];
    }

    public function masyarakat(): BelongsTo
    {
        return $this->belongsTo(Masyarakat::class);
    }

    public function bankSampah(): BelongsTo
    {
        return $this->belongsTo(BankSampah::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function detailSampah(): HasMany
    {
        return $this->hasMany(DetailSetoran::class);
    }

    public function mutasiSaldo(): HasOne
    {
        return $this->hasOne(MutasiSaldo::class);
    }

    public function labelStatus(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }

    /**
     * Kode setoran berurutan per hari, contoh ST-20261111-001.
     */
    public static function kodeBerikutnya(?CarbonInterface $tanggal = null): string
    {
        $tanggal ??= now();
        $awalan = 'ST-'.$tanggal->format('Ymd').'-';

        $terakhir = static::query()
            ->where('kode_setoran', 'like', $awalan.'%')
            ->orderByDesc('kode_setoran')
            ->value('kode_setoran');

        $urutan = $terakhir === null ? 1 : (int) substr($terakhir, -3) + 1;

        return $awalan.str_pad((string) $urutan, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Konfirmasi setoran dan tambahkan nilainya ke saldo, sekali saja.
     */
    public function konfirmasi(): bool
    {
        if ($this->status !== self::STATUS_DITIMBANG || $this->mutasiSaldo()->exists()) {
            return false;
        }

        return DB::transaction(function (): bool {
            $this->forceFill(['status' => self::STATUS_DIKONFIRMASI])->save();

            $saldo = $this->masyarakat->saldo()->firstOrCreate([], ['jumlah_saldo' => 0]);
            $saldo->increment('jumlah_saldo', (float) $this->total_nilai);

            $this->mutasiSaldo()->create([
                'saldo_id' => $saldo->id,
                'tipe' => MutasiSaldo::TIPE_MASUK,
                'nominal' => $this->total_nilai,
                'keterangan' => 'Setoran '.$this->kode_setoran.' dikonfirmasi',
            ]);

            return true;
        });
    }
}
