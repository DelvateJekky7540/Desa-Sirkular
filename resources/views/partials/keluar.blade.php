@auth
    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
        @csrf
        <button
            type="submit"
            class="inline-flex h-11 items-center justify-center rounded-control border border-stone bg-paper px-4 text-[13px] font-medium leading-none text-muted hover:border-moss hover:text-ink active:translate-y-px"
        >
            Keluar
        </button>
    </form>
@endauth
