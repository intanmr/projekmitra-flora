<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    //untuk mengizinkan request login diproses
    public function authorize(): bool
    {
        return true;
    }
    //untuk mengubah email menjadi huruf kecil agar konsisten 
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email ?? ''),
        ]);
    }
    //aturan validasi untuk login
    //email harus diisi, berupa string, format email valid, dan harus berakhiran @gmail.com
    //password harus diisi, berupa string, dan minimal 8 karakter
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'ends_with:@gmail.com'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.ends_with' => 'Email harus menggunakan @gmail.com.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }

    //proses autentikasi login
    //sistem rate limiter, membatasi jumlah percobaan login yang gagal dalam waktu tertentu
    public function authenticate(): void
    {
        //mengecek apakah user terlalu sering mencoba login
        $this->ensureIsNotRateLimited();

        //jika email/password salah dihitung sebagai percobaan gagal 
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }
        //jika login berhasil, reset hitungan percobaan gagal untuk email tersebut
        RateLimiter::clear($this->throttleKey());
    }
    
    //membatasi percobaan login maksimal 5 kali
    //jika melebihi batas akan menunggu beberapa detik 
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}