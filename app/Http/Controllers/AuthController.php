<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Peran demo yang boleh dipakai lewat tombol masuk cepat,
     * dari slug di URL ke kode role di database.
     *
     * @var array<string, string>
     */
    private const SLUG_KE_ROLE = [
        'admin' => User::ROLE_ADMIN,
        'bank' => User::ROLE_BANK,
        'warga' => User::ROLE_MASYARAKAT,
        'mitra' => User::ROLE_MITRA,
    ];

    public function form(Request $request): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email belum diisi. Isi email akunmu dulu.',
            'email.email' => 'Format email belum benar. Contoh yang benar: nama@desa.id.',
            'password.required' => 'Kata sandi belum diisi. Isi dulu sebelum masuk.',
        ]);

        if (! Auth::attempt($data)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi belum cocok. Periksa lagi isianmu.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->tujuanDashboard($request->user()));
    }

    public function demo(Request $request, string $peran): RedirectResponse
    {
        $role = self::SLUG_KE_ROLE[$peran] ?? null;
        abort_unless($role !== null, 404);

        $user = User::query()->where('role', $role)->orderBy('id')->first();

        if ($user === null) {
            return back()->withErrors(['demo' => 'Akun demo '.$peran.' belum ada. Jalankan php artisan db:seed dulu.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($this->tujuanDashboard($user));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    /**
     * Halaman pertama per pengguna setelah masuk.
     * Petugas ditaruh di antrean penimbangan karena tugas utamanya menimbang setoran.
     */
    private function tujuanDashboard(User $user): string
    {
        return match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_BANK => route('bank.penerimaan'),
            User::ROLE_MASYARAKAT => route('warga.beranda'),
            User::ROLE_MITRA => route('mitra.katalog'),
            User::ROLE_PETUGAS => route('bank.penimbangan'),
            default => route('landing'),
        };
    }
}
