@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Kiri: Foto Pedesaan -->
    <div class="hidden lg:flex lg:w-1/2 relative">
        <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1200" 
             class="absolute inset-0 w-full h-full object-cover" 
             alt="Pedesaan Hijau">
        <div class="absolute inset-0 bg-primary/45 flex items-center justify-center p-16">
            <blockquote class="text-white text-center font-display text-3xl md:text-4xl font-medium">
                "Sampah desa, rantai yang tidak putus."
            </blockquote>
        </div>
    </div>

    <!-- Kanan: Form Login -->
    <div class="w-full lg:w-1/2 bg-base flex items-center justify-center p-8 lg:p-16">
        <div class="w-full max-w-md">
            <a href="{{ route('landing') }}" class="logo mb-10 inline-block">DESA-SIRKULAR</a>
            
            <h1 class="section-title mb-3">Masuk ke sistem</h1>
            <p class="text-muted mb-8">Pakai akun yang sudah diberikan pengelola desa.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 p-4 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="label-form">Email</label>
                    <input id="email" name="email" type="email" required class="input-text" placeholder="alamat@email.com">
                </div>
                
                <div class="form-group">
                    <label for="password" class="label-form">Kata sandi</label>
                    <input id="password" name="password" type="password" required class="input-text" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary w-full mt-4">Masuk</button>
            </form>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="mono-label text-sm text-muted uppercase mb-4">Masuk cepat, akun demo</p>
                <div class="grid grid-cols-2 gap-3">
                    @foreach (['admin' => 'Admin Desa', 'bank' => 'Bank Sampah', 'warga' => 'Warga', 'mitra' => 'Mitra'] as $peran => $label)
                        <form method="POST" action="{{ route('login.demo', $peran) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline w-full text-sm py-2">{{ $label }}</button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection