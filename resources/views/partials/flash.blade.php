@if (session('status'))
    <div role="status" aria-atomic="true" class="mb-6 border border-stone border-l-[3px] border-l-berhasil bg-paper px-4 py-3">
        <p class="text-[14px] leading-[1.6] text-ink">{{ session('status') }}</p>
    </div>
@endif

@if (session('peringatan'))
    <div role="alert" aria-atomic="true" class="mb-6 border border-stone border-l-[3px] border-l-mustard bg-paper px-4 py-3">
        <p class="text-[14px] leading-[1.6] text-ink">{{ session('peringatan') }}</p>
    </div>
@endif
