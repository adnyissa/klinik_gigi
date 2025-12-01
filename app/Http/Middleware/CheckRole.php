<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[] ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Cek apakah role user ada di dalam daftar yang dibolehkan?
        // $roles adalah array dari parameter route (misal: ['admin'] atau ['dokter'])
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // 3. Jika role tidak cocok, redirect ke halaman default atau error
        // Opsional: Anda bisa buat halaman 403 Forbidden
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}