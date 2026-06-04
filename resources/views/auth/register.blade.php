<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Mitra Flora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-950 via-green-800 to-green-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 my-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center text-3xl mb-3">
                🌱
            </div>

            <h1 class="text-3xl font-bold text-green-950">
                Buat Akun
            </h1>

            <p class="text-slate-500 mt-2">
                Daftar sebagai customer Mitra Flora.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5"       data-validate-form
      novalidate>
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-green-950 mb-1">
                    Nama Lengkap
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700"
                    required
                    minlength="3"
                    maxlength="255"
                    data-label="Nama lengkap"
                    data-msg-required="Nama lengkap wajib diisi."
                    data-msg-minlength="Nama minimal 3 karakter agar data akun jelas."
                    autocomplete="name"

                    autofocus
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-green-950 mb-1">
                    Email Gmail
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@gmail.com"
                    class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700"
                    required
                    maxlength="255"
                    data-label="Email Gmail"
                    data-gmail="true"
                    data-msg-required="Email wajib diisi."
                    data-msg-email="Format email belum benar. Contoh: nama@gmail.com."
                    data-msg-gmail="Registrasi hanya menerima email @gmail.com."
                    autocomplete="username"
                >

                @error('email')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-green-950 mb-1">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700"
                    required
                    minlength="8"
                    data-label="Password"
                    data-msg-required="Password wajib diisi."
                    data-msg-minlength="Password minimal 8 karakter."
                    autocomplete="new-password"
                >

                @error('password')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-green-950 mb-1">
                    Konfirmasi Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700"
                    required
                    minlength="8"
                    data-label="Konfirmasi password"
                    data-match="#password"
                    data-msg-required="Konfirmasi password wajib diisi."
                    data-msg-minlength="Konfirmasi password minimal 8 karakter."
                    data-msg-match="Konfirmasi password harus sama dengan password."
                    autocomplete="new-password"
                >

                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-green-900 hover:bg-green-800 text-white font-bold py-3 rounded-xl transition"
            >
                Register
            </button>
        </form>

        <p class="text-center text-sm text-slate-600 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-800 font-bold hover:underline">
                Login di sini
            </a>
        </p>
    </div>

</body>
</html>