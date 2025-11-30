<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan user sudah login (cek ganda selain middleware auth)
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil user saat ini
        $user = Auth::user();

        // 3. Cek apakah role user sesuai dengan parameter yang diminta route
        // Asumsi: Relasi $user->role->name sudah ada (admin/petugas)
        if ($user->role->name !== $role) {
            // Jika role tidak cocok, kembalikan error 403 (Forbidden)
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
