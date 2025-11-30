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
        $roleName = Auth::user()->role->name ?? '';

        if ($roleName === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($roleName === 'petugas') {
            return redirect()->route('reports.history');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // PERBAIKAN: Redirect ke route 'login' (yang baru kita ubah namanya)
        return redirect()->route('login');
    }
}
