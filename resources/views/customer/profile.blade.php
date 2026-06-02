@extends('layouts.customer')

@section('content')
<div class="cf-card" style="margin-bottom: 24px;">
    <div class="cf-profile-top">
        <div class="cf-profile-identity">
            <div class="cf-profile-avatar">
                👤
            </div>

            <div>
                <h1 class="cf-title">
                    Profil Customer
                </h1>

                <p class="cf-subtitle">
                    Data akun customer yang sedang login.
                </p>
            </div>
        </div>

        <span class="cf-badge cf-badge-warning">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>
</div>

<div class="cf-card">
    <div class="cf-header">
        <h2 class="cf-title" style="font-size: 26px;">
            Informasi Akun
        </h2>

        <p class="cf-subtitle">
            Informasi akun customer Mitra Flora.
        </p>
    </div>

    <div class="cf-profile-grid">
        <div>
            <p class="cf-info-label">Nama Customer</p>
            <div class="cf-readonly-box">
                {{ auth()->user()->name }}
            </div>
        </div>

        <div>
            <p class="cf-info-label">Email</p>
            <div class="cf-readonly-box">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div>
            <p class="cf-info-label">Role</p>
            <div class="cf-readonly-box">
                {{ ucfirst(auth()->user()->role) }}
            </div>
        </div>

        <div>
            <p class="cf-info-label">Tanggal Akun Dibuat</p>
            <div class="cf-readonly-box">
                {{ auth()->user()->created_at?->format('d F Y, H:i') ?? '-' }}
            </div>
        </div>

        <div>
            <p class="cf-info-label">Terakhir Diperbarui</p>
            <div class="cf-readonly-box">
                {{ auth()->user()->updated_at?->format('d F Y, H:i') ?? '-' }}
            </div>
        </div>
    </div>

    <div class="cf-actions">
        <a href="{{ route('customer.dashboard') }}"
           class="cf-btn cf-btn-secondary">
            Kembali ke Beranda
        </a>

        <a href="{{ route('customer.profile.edit') }}"
           class="cf-btn cf-btn-primary">
            Edit Profil
        </a>
    </div>
</div>
@endsection