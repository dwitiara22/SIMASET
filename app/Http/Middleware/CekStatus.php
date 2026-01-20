<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['msg' => 'Silakan login terlebih dahulu.']);
        }

        // 2. Ambil data user yang login
        $user = Auth::user();

        // 3. Cek apakah role user ada di dalam daftar role yang diizinkan
        // Kita gunakan in_array karena kita bisa mengirim lebih dari satu role
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak memiliki akses, arahkan ke dashboard dengan pesan error
        return redirect()->route('dashboard')->withErrors(['msg' => 'Anda tidak memiliki akses ke halaman tersebut.']);
    }
}
