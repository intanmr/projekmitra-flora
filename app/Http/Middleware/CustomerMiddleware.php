<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //jika user belum login akan diarahkan ke halaman login
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        //mengecek role user, karena halaman customer hanya boleh diakses akun dengan role customer
        if (auth()->user()->role !== 'customer') {
            abort(403, 'Akses hanya untuk customer.');
        }
        //jika user sudah login dan rolenya customer, request boleh dilanjutkan ke halaman customer
        return $next($request);
    }
}