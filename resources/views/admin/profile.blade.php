@extends('layouts.admin')

@section('content')
<div class="mf-card" style="margin-bottom: 24px;">
    <div class="mf-profile-top">
        <div class="mf-profile-identity">
            <div class="mf-profile-avatar">
                👤
            </div>

            <div>
                <h1 class="mf-title">
                    Profil Admin
                </h1>

                <p class="mf-subtitle">
                    Data akun admin yang sedang login.
                </p>
            </div>
        </div>

        <span class="mf-badge mf-badge-warning">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>
</div>

<div class="mf-card">
    <div class="mf-header">
        <h2 class="mf-title" style="font-size: 26px;">
            Informasi Akun
        </h2>
    </div>

    <div class="mf-profile-grid">
        <div>
            <p class="mf-small-text">Nama Admin</p>
            <div class="mf-readonly-box">
                {{ auth()->user()->name }}
            </div>
        </div>

        <div>
            <p class="mf-small-text">Email</p>
            <div class="mf-readonly-box">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div>
            <p class="mf-small-text">Role</p>
            <div class="mf-readonly-box">
                {{ ucfirst(auth()->user()->role) }}
            </div>
        </div>

        <div>
            <p class="mf-small-text">Tanggal Akun Dibuat</p>
            <div class="mf-readonly-box">
                {{ auth()->user()->created_at?->format('d F Y, H:i') ?? '-' }}
            </div>
        </div>

        <div>
            <p class="mf-small-text">Terakhir Diperbarui</p>
            <div class="mf-readonly-box">
                {{ auth()->user()->updated_at?->format('d F Y, H:i') ?? '-' }}
            </div>
        </div>
    </div>

    <div class="mf-actions">
        <a href="{{ route('admin.dashboard') }}" class="mf-btn mf-btn-secondary">
            Kembali ke Dashboard
        </a>

        <a href="{{ route('profile.edit') }}" class="mf-btn mf-btn-yellow">
            Edit Profil
        </a>
    </div>
</div>
@endsection