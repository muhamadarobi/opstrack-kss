<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek status aktif/nonaktif
            if ($user->status !== 'aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.',
                ]);
            }

            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();
        // Menggunakan null coalescing operator untuk mencegah error jika relasi role tidak ditemukan
        $roleName = $user->role->name ?? '';

        if ($roleName === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($roleName === 'petugas') {
            // MODIFIKASI:
            // Mengarahkan petugas ke halaman index laporan dengan parameter group.
            // Contoh URL nanti: /reports?group=a

            // Pastikan Anda memiliki route bernama 'reports.index' (atau sesuaikan dengan route dashboard petugas Anda)
            // Parameter 'group' ini opsional untuk UX, tapi filtering data yang AMAN
            // harus tetap dilakukan di Controller tujuan menggunakan: ->where('group_name', auth()->user()->group)

            return redirect()->route('reports.index', ['group' => $user->group]);
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
