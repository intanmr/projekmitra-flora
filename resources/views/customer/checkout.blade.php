@extends('layouts.customer')

@section('content')
@php
    $chosenProduct = $selectedProduct;
@endphp

<div class="cf-card cf-card-narrow">
    <div class="cf-header">
        <h1 class="cf-title">
            Form Pembelian
        </h1>

        <p class="cf-subtitle">
            Lengkapi data pesanan Anda untuk membeli produk tanaman hias Mitra Flora.
        </p>
    </div>

    {{-- PRODUK YANG DIPILIH --}}
    <div class="cf-info-box" style="margin-bottom: 24px;">
        <div style="display:flex; gap:18px; align-items:center; flex-wrap:wrap;">
            <div>
                @if($chosenProduct->gambar)
                    <img src="{{ asset('storage/' . $chosenProduct->gambar) }}"
                         alt="{{ $chosenProduct->nama_produk }}"
                         style="width:90px; height:90px; object-fit:cover; border-radius:16px; border:1px solid #bbf7d0;">
                @else
                    <div style="width:90px; height:90px; border-radius:16px; background:#dcfce7; display:flex; align-items:center; justify-content:center; font-size:42px;">
                        🌿
                    </div>
                @endif
            </div>

            <div>
                <p class="cf-info-label">
                    Produk Dipilih
                </p>

                <h3 class="cf-info-value" style="font-size:22px;">
                    {{ $chosenProduct->nama_produk }}
                </h3>

                <p class="cf-product-price" style="margin:6px 0 0;">
                    Rp {{ number_format($chosenProduct->harga, 0, ',', '.') }}
                </p>

                <p class="cf-small-text">
                    Stok tersedia: {{ $chosenProduct->stok }} pcs
                </p>
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ route('customer.orders.store') }}"
          enctype="multipart/form-data"
          data-validate-form
          novalidate>
        @csrf

        {{-- PRODUCT ID DIKUNCI SESUAI TOMBOL BELI --}}
        <input type="hidden"
               name="product_id"
               id="product_id"
               value="{{ $chosenProduct->id }}">

        <div class="cf-form-grid">
            <div class="cf-field">
                <label class="cf-label">
                    Produk *
                </label>

                <input type="text"
                       class="cf-input"
                       value="{{ $chosenProduct->nama_produk }}"
                       readonly>

                @error('product_id')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Harga Satuan
                </label>

                <input type="text"
                       id="harga_satuan_display"
                       class="cf-input"
                       value="Rp {{ number_format($chosenProduct->harga, 0, ',', '.') }}"
                       readonly>
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Nama Customer *
                </label>

                <input type="text"
                       name="nama_customer"
                       value="{{ old('nama_customer', auth()->user()->name ?? '') }}"
                       class="cf-input"
                       placeholder="Masukkan nama customer"
                        required
                        minlength="3"
                        maxlength="100"
                        data-label="Nama customer"
                        data-msg-required="Nama customer wajib diisi."
                        data-msg-minlength="Nama customer minimal 3 karakter."
                        data-msg-maxlength="Nama customer maksimal 100 karakter.">

                @error('nama_customer')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Email *
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email', auth()->user()->email ?? '') }}"
                       class="cf-input"
                       placeholder="contoh@gmail.com"
                        required
                        maxlength="255"
                        data-label="Email Gmail"
                        data-gmail="true"
                        data-msg-required="Email wajib diisi."
                        data-msg-email="Format email belum benar. Contoh: nama@gmail.com."
                        data-msg-gmail="Email pesanan wajib menggunakan @gmail.com."
                       >

                @error('email')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Nomor WhatsApp *
                </label>

                <input type="text"
                       name="whatsapp"
                       value="{{ old('whatsapp') }}"
                       class="cf-input"
                       placeholder="Contoh: 08123456789"
                       required
                       pattern="^08[0-9]{8,13}$"
                       maxlength="15"
                       data-label="Nomor WhatsApp"
                       data-msg-pattern="Nomor WhatsApp harus diawali 08 dan terdiri dari 10-15 digit angka.">

                @error('whatsapp')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Jumlah *
                </label>

                <input type="number"
                       name="jumlah"
                       id="jumlah"
                       min="1"
                       max="{{ $chosenProduct->stok }}"
                       value="{{ old('jumlah', 1) }}"
                       class="cf-input"
                       placeholder="Masukkan jumlah"
                       required
                       data-label="Jumlah pembelian"
                       data-msg-required="Jumlah pembelian wajib diisi."
                       data-msg-min="Jumlah minimal pembelian adalah 1 pcs."
                       data-msg-max="Jumlah pembelian tidak boleh melebihi stok tersedia">

                <p id="stokInfo" class="cf-small-text" style="margin-top:6px;">
                    Stok tersedia: {{ $chosenProduct->stok }} pcs
                </p>

                @error('jumlah')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Metode Pembayaran *
                </label>

                <select name="metode_pembayaran"
                        class="cf-select"
                        required
                        data-label="Metode pembayaran"
                        data-msg-required="Metode pembayaran wajib dipilih.">
                    <option value="">-- Pilih Metode Pembayaran --</option>

                    <option value="Transfer BCA" @selected(old('metode_pembayaran') === 'Transfer BCA')>
                        Transfer BCA (1234567890 a.n. Mitra Flora)
                    </option>

                    <option value="Transfer BNI" @selected(old('metode_pembayaran') === 'Transfer BNI')>
                        Transfer BNI (0987654321 a.n. Mitra Flora)
                    </option>

                    <option value="COD" @selected(old('metode_pembayaran') === 'COD')>
                        COD
                    </option>
                </select>

                @error('metode_pembayaran')
                    <div class="cf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="cf-field">
                <label class="cf-label">
                    Estimasi Total
                </label>

                <input type="text"
                       id="total_harga_display"
                       class="cf-input"
                       value=""
                       readonly>
            </div>
        </div>

        <div class="cf-field">
            <label class="cf-label">
                Alamat Pengiriman *
            </label>

            <textarea name="alamat"
                      class="cf-textarea"
                      placeholder="Masukkan alamat lengkap pengiriman"
                    required
                    minlength="10"
                    data-label="Alamat pengiriman"
                    data-msg-required="Alamat pengiriman wajib diisi.">{{ old('alamat') }}</textarea>

            @error('alamat')
                <div class="cf-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="cf-field">
            <label class="cf-label">
                Catatan Customer
            </label>

            <textarea name="catatan"
                      class="cf-textarea"
                    maxlength="255"
                    data-label="Catatan customer"
                    data-msg-maxlength="Catatan maksimal 255 karakter."
                      placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>

            @error('catatan')
                <div class="cf-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="cf-field">
            <label class="cf-label">
                Bukti Pembayaran *
            </label>

            <input type="file"
                   name="bukti_pembayaran"
                accept="image/jpeg,image/png,image/webp"
                class="cf-file"
                required
                data-label="Bukti pembayaran"
                data-max-size="2"
                data-msg-required="Bukti pembayaran wajib diunggah."
                data-msg-file-type="Bukti pembayaran harus berformat JPG, JPEG, PNG, atau WEBP."
                data-msg-file-size="Ukuran bukti pembayaran maksimal 2MB.">

            <p class="cf-small-text" style="margin-top:6px;">
                Unggah bukti pembayaran dengan format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2MB.
            </p>

            @error('bukti_pembayaran')
                <div class="cf-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="cf-actions">
            <a href="{{ route('customer.catalog') }}"
               class="cf-btn cf-btn-secondary">
                Batal
            </a>

            <button type="submit"
                    class="cf-btn cf-btn-primary">
                Kirim Pesanan
            </button>
        </div>
    </form>
</div>

<script>
    const jumlahInput = document.getElementById('jumlah');
    const hargaSatuanDisplay = document.getElementById('harga_satuan_display');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const stokInfo = document.getElementById('stokInfo');

    const selectedProduct = {
        id: "{{ $chosenProduct->id }}",
        harga: {{ $chosenProduct->harga }},
        stok: {{ $chosenProduct->stok }},
        nama: @json($chosenProduct->nama_produk),
    };

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(value);
    }

    function updateCheckoutTotal() {
        const jumlah = parseInt(jumlahInput.value || 0);

        if (isNaN(jumlah) || jumlah < 1) {
            totalHargaDisplay.value = formatRupiah(0);
            stokInfo.style.color = '#dc2626';
            stokInfo.style.fontWeight = '800';
            return;
        }

        const total = selectedProduct.harga * jumlah;

        hargaSatuanDisplay.value = formatRupiah(selectedProduct.harga);
        totalHargaDisplay.value = formatRupiah(total);

        if (jumlah > selectedProduct.stok) {
           
            stokInfo.style.color = '#dc2626';
            stokInfo.style.fontWeight = '800';
        } else {
            stokInfo.textContent = 'Stok tersedia: ' + selectedProduct.stok + ' pcs';
            stokInfo.style.color = '';
            stokInfo.style.fontWeight = '';
        }
    }

    if (jumlahInput) {
        jumlahInput.addEventListener('input', updateCheckoutTotal);
    }

    updateCheckoutTotal();
</script>
@endsection