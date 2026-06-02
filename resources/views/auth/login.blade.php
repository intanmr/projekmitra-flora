<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Mitra Flora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-950 via-green-800 to-green-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center text-3xl mb-3">
                🌷
            </div>

            <h1 class="text-3xl font-bold text-green-950">
                Mitra<span class="text-yellow-500 italic">Flora</span>
            </h1>

            <p class="text-slate-500 mt-2">
                Silakan login untuk masuk ke akun Anda.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-green-100 border border-green-300 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-green-950 mb-1">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@gmail.com"
                    class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700"
                    autofocus
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
                >

                @error('password')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-green-700 focus:ring-green-700">
                Ingat saya
            </label>

            <button
                type="submit"
                class="w-full bg-green-900 hover:bg-green-800 text-white font-bold py-3 rounded-xl transition"
            >
                Login
            </button>
        </form>

        <p class="text-center text-sm text-slate-600 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-green-800 font-bold hover:underline">
                Daftar di sini
            </a>
        </p>
    </div>

</body>
</html>