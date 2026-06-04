<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //jika user belum login, akan diarahkan ke halam login
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        //mengecek role user, karena halaman admin hanya bisa diakses akun dengan role admin 
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }
        //jika user sudah login dan rolenya admin, request boleh dilanjutkan ke halaman admin
        return $next($request);
    }
}