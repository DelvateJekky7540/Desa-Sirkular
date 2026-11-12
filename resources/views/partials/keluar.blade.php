@auth
    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
        @csrf
        <button
            type="submit"
            class="inline-flex h-11 items-center justify-center rounded-control border border-stone bg-paper px-4 text-[13px] font-medium leading-none text-muted transition-colors duration-150 hover:border-forest hover:bg-forest hover:text-bone active:translate-y-px"
        >
            Keluar
        </button>
    </form>
@endauth
