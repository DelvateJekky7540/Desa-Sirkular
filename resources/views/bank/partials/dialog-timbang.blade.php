@use('App\Support\Angka')

@php
    $detail = $setoran->detailSampah->first();
    $jenis = $detail?->jenis_sampah ?? 'Campuran';
    $beratTercatat = (float) $setoran->total_berat;
    $hargaAwal = (float) ($detail?->harga_per_kg ?? 0);
    $gagal = $gagalId !== null && (int) $gagalId === $setoran->id;
    $nilaiBerat = $gagal ? old('berat', Angka::desimal($beratTercatat)) : Angka::desimal($beratTercatat);
    $nilaiHarga = $gagal ? old('harga_per_kg', number_format($hargaAwal, 0, ',', '.')) : number_format($hargaAwal, 0, ',', '.');
@endphp

<dialog
    id="dialog-timbang-{{ $setoran->id }}"
    aria-labelledby="judul-timbang-{{ $setoran->id }}"
    class="m-auto max-h-[calc(100dvh-2rem)] w-[min(560px,calc(100vw-2rem))] overflow-y-auto rounded-card border border-stone bg-paper p-0 text-ink shadow-[0_4px_12px_rgba(27,33,28,0.06)] backdrop:bg-ink/40 max-sm:mb-0 max-sm:mt-auto max-sm:w-full max-sm:max-w-none max-sm:rounded-b-none"
>
    <div class="border-b border-stone p-4 md:p-5">
        <h2 id="judul-timbang-{{ $setoran->id }}" class="font-display text-[20px] font-medium leading-[1.3] text-ink md:text-[24px]">
            Timbang setoran
        </h2>
        <p class="mt-1 text-[15px] font-medium leading-[1.5] text-ink">{{ $setoran->user?->name ?? 'Warga' }}</p>
        <p class="mt-0.5 text-[13px] leading-[1.4] text-muted">
            {{ $jenis }}, masuk {{ $setoran->created_at->locale('id')->translatedFormat('j F Y, H.i') }}
        </p>

        @if ($setoran->catatan)
            <div class="mt-3 border border-stone bg-bone px-3 py-2">
                <p class="text-[12px] font-medium leading-[1.4] text-muted">Catatan warga</p>
                <p class="mt-0.5 text-[13px] leading-[1.5] text-ink">{{ $setoran->catatan }}</p>
            </div>
        @endif
    </div>

    <form
        method="POST"
        action="{{ route('bank.penimbangan.store', $setoran) }}"
        data-form-timbang
        data-berat-tercatat="{{ $beratTercatat }}"
    >
        @csrf

        <div class="p-4 md:p-5">
            @if ($gagal && $errors->any())
                <div role="alert" class="mb-4 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
                    <p class="text-[14px] font-medium leading-[1.5] text-ink">Hasil timbang belum tersimpan. Periksa isian berikut.</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="berat-{{ $setoran->id }}" class="block text-[13px] font-medium leading-[1.4] text-ink">Berat aktual</label>
                <div class="relative mt-2">
                    <input
                        id="berat-{{ $setoran->id }}"
                        name="berat"
                        type="text"
                        inputmode="decimal"
                        autocomplete="off"
                        required
                        value="{{ $nilaiBerat }}"
                        data-berat
                        @if ($gagal) aria-invalid="true" aria-describedby="error-berat-{{ $setoran->id }}" @else aria-describedby="hint-berat-{{ $setoran->id }}" @endif
                        class="h-16 w-full rounded-control border bg-paper pr-12 text-center font-mono text-[32px] font-medium leading-[1.1] text-ink @if ($gagal) border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @endif"
                    >
                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 font-mono text-[14px] text-muted" aria-hidden="true">kg</span>
                </div>
                @if ($gagal)
                    <p id="error-berat-{{ $setoran->id }}" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $errors->first('berat') }}</p>
                @else
                    <p id="hint-berat-{{ $setoran->id }}" class="mt-1 text-[12px] leading-[1.4] text-muted">Angka dari timbangan, pakai koma untuk desimal.</p>
                @endif

                <p class="mt-2 text-[13px] leading-[1.4] text-muted">
                    Berat tercatat <span class="font-mono text-ink">{{ Angka::kg($beratTercatat) }}</span>.
                </p>
                <p data-selisih aria-live="polite" class="mt-1 text-[12px] leading-[1.4] text-muted">Sama dengan berat tercatat.</p>
            </div>

            <div class="mt-3">
                <label for="harga-{{ $setoran->id }}" class="block text-[13px] font-medium leading-[1.4] text-ink">Harga per kilogram</label>
                <div class="relative mt-2">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] text-muted" aria-hidden="true">Rp</span>
                    <input
                        id="harga-{{ $setoran->id }}"
                        name="harga_per_kg"
                        type="text"
                        inputmode="decimal"
                        autocomplete="off"
                        required
                        value="{{ $nilaiHarga }}"
                        data-harga
                        @if ($gagal) aria-invalid="true" aria-describedby="error-harga-{{ $setoran->id }}" @else aria-describedby="hint-harga-{{ $setoran->id }}" @endif
                        class="h-11 w-full rounded-control border bg-paper pl-10 pr-12 font-mono text-[15px] text-ink @if ($gagal) border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @endif"
                    >
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[13px] text-muted" aria-hidden="true">/kg</span>
                </div>
                @if ($gagal)
                    <p id="error-harga-{{ $setoran->id }}" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $errors->first('harga_per_kg') }}</p>
                @else
                    <p id="hint-harga-{{ $setoran->id }}" class="mt-1 text-[12px] leading-[1.4] text-muted">Harga acuan bank sampah, masih bisa diubah.</p>
                @endif
            </div>

            <div class="mt-4 border-t border-dashed border-stone pt-3">
                <div class="flex items-baseline justify-between gap-3">
                    <p class="text-[13px] leading-[1.4] text-muted">Nilai setoran</p>
                    <p data-total aria-live="polite" class="font-mono text-[28px] font-medium leading-none text-ochre">
                        {{ Angka::rupiah($beratTercatat * $hargaAwal) }}
                    </p>
                </div>
                <p class="mt-2 text-[12px] leading-[1.4] text-muted">Berat dan nilai setoran diganti dengan hasil timbang ini.</p>
            </div>
        </div>

        <div class="grid gap-3 border-t border-stone p-4 sm:grid-cols-2 md:p-5 max-sm:pb-[calc(1rem+env(safe-area-inset-bottom))]">
            <button
                type="button"
                data-tutup-timbang
                class="inline-flex h-11 items-center justify-center rounded-control border border-stone bg-paper px-6 text-[15px] font-medium text-ink hover:bg-bone active:translate-y-px"
            >
                Batal
            </button>
            <button
                type="submit"
                data-simpan
                class="inline-flex h-12 items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px disabled:bg-forest/60"
            >
                <span data-simpan-label>Simpan hasil timbang</span>
            </button>
        </div>
    </form>
</dialog>
