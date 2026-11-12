<form method="GET" action="{{ route('tracking.form') }}" class="rounded-card border border-stone bg-paper p-4 md:p-5">
    @if ($errors->has('kode'))
        <div role="alert" class="mb-4 border border-stone border-l-[3px] border-l-brick px-4 py-3">
            <p class="text-[14px] font-medium leading-[1.5] text-ink">{{ $errors->first('kode') }}</p>
        </div>
    @endif

    <label for="kode" class="label-form">Kode setoran</label>
    <input
        id="kode"
        name="kode"
        type="text"
        autocomplete="off"
        autocapitalize="characters"
        spellcheck="false"
        placeholder="Contoh: ST-20261112-001"
        value="{{ old('kode', $kodeDiminta ?? '') }}"
        class="mt-2 h-12 w-full rounded-control border border-stone bg-paper px-3 font-mono text-[15px] text-ink hover:border-moss focus:border-forest"
    />

    <button
        type="submit"
        class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white transition-colors duration-150 hover:bg-forest-hover active:translate-y-px md:w-auto"
    >
        Lacak setoran
    </button>

    <p class="mt-3 text-[12px] leading-[1.4] text-muted">Kode tercetak di bukti setor yang diberikan petugas saat setoran diterima.</p>
</form>
