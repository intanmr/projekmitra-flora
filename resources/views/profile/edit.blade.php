@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-3xl shadow p-8 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-green-950">
                    Edit Profil Admin
                </h1>
                <p class="text-slate-600 mt-1">
                    Perbarui informasi akun admin Mitra Flora.
                </p>
            </div>

            <a href="{{ route('admin.profile') }}"
               class="bg-slate-100 text-slate-700 px-5 py-3 rounded-xl font-bold hover:bg-slate-200 text-center">
                Kembali ke Profil
            </a>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="mb-5 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl">
            Profil berhasil diperbarui.
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="mb-5 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl">
            Password berhasil diperbarui.
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">

        {{-- FORM EDIT NAMA DAN EMAIL --}}
        <div class="bg-white rounded-3xl shadow p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-green-950">
                    Informasi Profil
                </h2>
                <p class="text-slate-600 mt-1">
                    Ubah nama dan email akun admin.
                </p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-5">
                    <label for="name" class="block font-bold text-green-950 mb-1">
                        Nama Admin *
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name', auth()->user()->name) }}"
                           placeholder="Masukkan nama admin"
                           class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700 @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block font-bold text-green-950 mb-1">
                        Email Gmail *
                    </label>

                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email', auth()->user()->email) }}"
                           placeholder="contoh@gmail.com"
                           class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700 @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-green-950 mb-1">
                        Role
                    </label>

                    <input type="text"
                           value="{{ ucfirst(auth()->user()->role) }}"
                           readonly
                           class="w-full rounded-xl border-slate-300 bg-green-50 text-green-950 font-bold">

                    <p class="text-sm text-slate-500 mt-1">
                        Role tidak dapat diubah dari halaman profil.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-green-900 text-white py-3 rounded-xl font-bold hover:bg-green-800">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        {{-- FORM UBAH PASSWORD --}}
        <div class="bg-white rounded-3xl shadow p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-green-950">
                    Ubah Password
                </h2>
                <p class="text-slate-600 mt-1">
                    Gunakan password baru minimal 8 karakter.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label for="current_password" class="block font-bold text-green-950 mb-1">
                        Password Saat Ini *
                    </label>

                    <input id="current_password"
                           type="password"
                           name="current_password"
                           placeholder="Masukkan password saat ini"
                           class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700 @error('current_password', 'updatePassword') border-red-500 @enderror">

                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block font-bold text-green-950 mb-1">
                        Password Baru *
                    </label>

                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Minimal 8 karakter"
                           class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700 @error('password', 'updatePassword') border-red-500 @enderror">

                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block font-bold text-green-950 mb-1">
                        Konfirmasi Password Baru *
                    </label>

                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           placeholder="Ulangi password baru"
                           class="w-full rounded-xl border-slate-300 focus:border-green-700 focus:ring-green-700 @error('password_confirmation', 'updatePassword') border-red-500 @enderror">

                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-yellow-400 text-green-950 py-3 rounded-xl font-bold hover:bg-yellow-300">
                    Update Password
                </button>
            </form>
        </div>

    </div>
</div>
@endsection