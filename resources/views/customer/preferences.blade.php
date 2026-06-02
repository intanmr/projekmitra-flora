@extends('layouts.customer')

@section('content')
<div class="cf-card cf-card-narrow">
    <div class="cf-header">
        <h1 class="cf-title">
            Preferensi Tampilan
        </h1>

        <p class="cf-subtitle">
            Atur tema tampilan Mitra Flora sesuai kenyamanan Anda.
        </p>
    </div>

    <div id="preferenceMessage" class="cf-hidden"></div>

    <form id="preferenceForm">
        @csrf

        <div class="cf-field">
            <label class="cf-label">
                Pilihan Tema
            </label>

            <select name="theme" id="theme" class="cf-select">
                <option value="light" @selected($theme === 'light')>
                    Light
                </option>
                <option value="dark" @selected($theme === 'dark')>
                    Dark
                </option>
                <option value="system" @selected($theme === 'system')>
                    System
                </option>
            </select>
        </div>

        <div class="cf-field">
            <label class="cf-label">
                Ukuran Font
            </label>

            <select name="font_size" id="font_size" class="cf-select">
                <option value="normal" @selected($fontSize === 'normal')>
                    Normal
                </option>
                <option value="large" @selected($fontSize === 'large')>
                    Besar
                </option>
            </select>
        </div>

        <div class="cf-actions">
            <a href="{{ route('customer.dashboard') }}" class="cf-btn cf-btn-secondary">
                Kembali
            </a>

            <button type="submit" class="cf-btn cf-btn-primary">
                Simpan Preferensi
            </button>
        </div>
    </form>
</div>

<script>
    const preferenceForm = document.getElementById('preferenceForm');
    const preferenceMessage = document.getElementById('preferenceMessage');

    preferenceForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        preferenceMessage.className = 'cf-hidden';

        const response = await fetch("{{ route('customer.preferences.save') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                theme: document.getElementById('theme').value,
                font_size: document.getElementById('font_size').value,
            }),
        });

        const data = await response.json();

        if (data.success) {
            setCookie('theme_preference', data.theme, 30);
            setCookie('font_size_preference', data.font_size, 30);

            applyTheme(data.theme);
            applyFontSize(data.font_size);

            preferenceMessage.textContent = data.message;
            preferenceMessage.className = 'cf-message cf-message-success';
        } else {
            preferenceMessage.textContent = 'Preferensi gagal disimpan.';
            preferenceMessage.className = 'cf-message cf-message-error';
        }
    });
</script>
@endsection