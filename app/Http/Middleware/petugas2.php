<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class petugas2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (Auth::check() && Auth::user()->role == 3) {
            return $next($request); // Petugas 2 bisa lanjut
        }

        abort(403, 'Unauthorized'); // Blokir akses jika bukan Petugas 2
    }
}
