<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        //autentikasi dilakukan melalui LoginRequest yang sudah divalidasi, 
        //jika validasi berhasil maka proses login akan dilanjutkan
        $request->authenticate();

        //regenerasi session untuk mencegah session fixation, 
        //yaitu serangan yang memanfaatkan session yang sudah ada untuk mendapatkan akses tidak sah
        $request->session()->regenerate();

        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //logout user menggunakan guard 'web' 
        Auth::guard('web')->logout();

        //menghapus seluruh data session aktif
        $request->session()->invalidate();

        //memuat ulang CSRF token setelah logout 
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}