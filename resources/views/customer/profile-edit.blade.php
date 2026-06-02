@extends('layouts.customer')

@section('content')
<div class="cf-card" style="margin-bottom: 24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;">
        <div>
            <h1 class="cf-title">
                Edit Profil Customer
            </h1>

            <p class="cf-subtitle">
                Perbarui informasi akun customer Mitra Flora.
            </p>
        </div>

        <a href="{{ route('customer.profile') }}"
           class="cf-btn cf-btn-secondary">
            Kembali ke Profil
        </a>
    </div>
</div>

<div class="customer-profile-edit-grid">
    <div class="cf-card">
        <div class="cf-header">
            <h2 class="cf-title" style="font-size: 26px;">
                Informasi Profil
            </h2>

            <p class="cf-subtitle">
                Ubah nama dan email akun customer.
            </p>
        </div>

        @if(session('profile_success'))
            <div class="cf-message cf-message-success">
                {{ session('profile_success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="cf-field">
                <label for="name" class="cf-label">
                    Nama Customer *
                </label>

                <input id="name"
                       name="name"
                       type="text"
                       value="{{ old('name', auth()->user()->name) }}"
                       class="cf-input"
                       required
                       autofocus
                       autocomplete="name">

                @error('name')
                    <div class="cf-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="cf-field">
                <label for="email" class="cf-label">
                    Email *
                </label>

                <input id="email"
                       name="email"
                       type="email"
                       value="{{ old('email', auth()->user()->email) }}"
                       class="cf-input"
                       required
                       autocomplete="username">

                @error('email')
                    <div class="cf-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Role
                </label>

                <input type="text"
                       value="{{ ucfirst(auth()->user()->role) }}"
                       class="cf-input"
                       readonly>

                <p class="cf-subtitle" style="font-size: 13px; margin-top: 6px;">
                    Role tidak dapat diubah dari halaman profil.
                </p>
            </div>

            <div class="cf-actions">
                <button type="submit"
                        class="cf-btn cf-btn-primary">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <div class="cf-card">
        <div class="cf-header">
            <h2 class="cf-title" style="font-size: 26px;">
                Ubah Password
            </h2>

            <p class="cf-subtitle">
                Gunakan password baru minimal 8 karakter.
            </p>
        </div>

        @if(session('password_success'))
            <div class="cf-message cf-message-success">
                {{ session('password_success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.password.update') }}">
            @csrf
            @method('PUT')

            <div class="cf-field">
                <label for="current_password" class="cf-label">
                    Password Saat Ini *
                </label>

                <input id="current_password"
                       name="current_password"
                       type="password"
                       class="cf-input"
                       placeholder="Masukkan password saat ini"
                       autocomplete="current-password">

                @error('current_password')
                    <div class="cf-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="cf-field">
                <label for="password" class="cf-label">
                    Password Baru *
                </label>

                <input id="password"
                       name="password"
                       type="password"
                       class="cf-input"
                       placeholder="Minimal 8 karakter"
                       autocomplete="new-password">

                @error('password')
                    <div class="cf-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="cf-field">
                <label for="password_confirmation" class="cf-label">
                    Konfirmasi Password Baru *
                </label>

                <input id="password_confirmation"
                       name="password_confirmation"
                       type="password"
                       class="cf-input"
                       placeholder="Ulangi password baru"
                       autocomplete="new-password">

                @error('password_confirmation')
                    <div class="cf-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="cf-actions">
                <button type="submit"
                        class="cf-btn cf-btn-primary">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .customer-profile-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    @media (max-width: 900px) {
        .customer-profile-edit-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection