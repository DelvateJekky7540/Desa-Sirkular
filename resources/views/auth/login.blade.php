<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk, Desa Sirkular</title>
    <meta name="description" content="Masuk ke sistem Desa Sirkular dengan akun yang diberikan pengelola desa.">
    {!! Vite::fonts() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-control focus:bg-paper focus:px-4 focus:py-3 focus:text-[14px] focus:font-medium focus:text-forest">
        Lewati ke isi halaman
    </a>

    <main id="konten" class="mx-auto flex w-full max-w-[420px] flex-1 flex-col justify-center px-4 py-10">
        <p class="text-center font-display text-[20px] font-medium leading-none text-forest">
            <a href="{{ route('landing') }}" class="inline-flex min-h-11 items-center">Desa Sirkular</a>
        </p>

        <div class="mt-6 rounded-card border border-stone bg-paper p-5">
            <h1 class="font-display text-[24px] font-medium leading-[1.25] text-ink md:text-[26px]">Masuk ke sistem</h1>
            <p class="mt-2 text-[15px] leading-[1.6] text-muted">Pakai akun yang sudah diberikan pengelola desa.</p>

            @if (auth()->check())
                <p class="mt-3 border border-stone border-l-[3px] border-l-moss bg-bone px-4 py-3 text-[13px] leading-[1.5] text-ink">
                    Kamu sedang masuk sebagai {{ auth()->user()->nama }}. Tombol demo di bawah akan menggantikan akun yang aktif.
                </p>
            @endif

            @if ($errors->any())
                <div role="alert" class="mt-4 border border-stone border-l-[3px] border-l-brick bg-bone px-4 py-3">
                    <p class="text-[14px] font-medium leading-[1.5] text-ink">Masuk belum berhasil. Periksa isian berikut.</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4 text-[13px] leading-[1.5] text-muted">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-4 space-y-3">
                @csrf

                <div>
                    <label for="email" class="block text-[13px] font-medium leading-[1.4] text-ink">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="username"
                        required
                        value="{{ old('email') }}"
                        @error('email') aria-invalid="true" aria-describedby="error-email" @enderror
                        class="mt-2 h-11 w-full rounded-control border bg-paper px-3 text-[15px] text-ink @error('email') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                    >
                    @error('email')
                        <p id="error-email" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-medium leading-[1.4] text-ink">Kata sandi</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        @error('password') aria-invalid="true" aria-describedby="error-password" @enderror
                        class="mt-2 h-11 w-full rounded-control border bg-paper px-3 text-[15px] text-ink @error('password') border-brick hover:border-brick focus:border-brick @else border-stone hover:border-moss focus:border-forest @enderror"
                    >
                    @error('password')
                        <p id="error-password" class="mt-1 text-[12px] leading-[1.4] text-brick">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="inline-flex h-12 w-full items-center justify-center rounded-control bg-forest px-6 text-[15px] font-medium text-white hover:bg-forest-hover active:translate-y-px"
                    >
                        Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-stone pt-5">
                <p class="text-[13px] font-medium leading-[1.4] text-ink">Masuk cepat, akun demo</p>
                <p class="mt-1 text-[12px] leading-[1.4] text-muted">Sekali klik langsung masuk ke halaman peran terkait, tanpa mengisi kata sandi.</p>

                <div class="mt-3 grid grid-cols-2 gap-2">
                    @foreach ([['peran' => 'admin', 'label' => 'Admin Desa'], ['peran' => 'bank', 'label' => 'Bank Sampah'], ['peran' => 'warga', 'label' => 'Warga'], ['peran' => 'mitra', 'label' => 'Mitra']] as $demo)
                        <form method="POST" action="{{ route('login.demo', $demo['peran']) }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex h-11 w-full items-center justify-center rounded-control border border-stone bg-paper px-3 text-[14px] font-medium leading-none text-ink hover:border-moss hover:bg-[#F0EDE4] active:translate-y-px"
                            >
                                {{ $demo['label'] }}
                            </button>
                        </form>
                    @endforeach
                </div>

                <p class="mt-3 text-[12px] leading-[1.4] text-muted">
                    Mau masuk manual sebagai Petugas? Kata sandi semua akun demo: password.
                </p>
            </div>
        </div>

        <p class="mt-4 text-center text-[13px] leading-[1.4] text-muted">
            <a href="{{ route('landing') }}" class="font-medium text-forest underline underline-offset-4 hover:decoration-2">Kembali ke halaman utama</a>
        </p>
    </main>
</body>
</html>
