@extends('layouts.admin')

@section('content')
<div class="mf-card mf-card-narrow">
    <div class="mf-header">
        <h1 class="mf-title">
            Edit Status Pesanan
        </h1>

        <p class="mf-subtitle">
            Ubah status pesanan customer dan lihat detail pembelian.
        </p>
    </div>

    <div class="mf-info-box" style="margin-bottom: 24px;">
        <div class="mf-info-grid">
            <div>
                <p class="mf-info-label">Tanggal Pembelian</p>
                <p class="mf-info-value">
                    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                </p>
            </div>

            <div>
                <p class="mf-info-label">Customer</p>
                <p class="mf-info-value">{{ $order->nama_customer }}</p>
            </div>

            <div>
                <p class="mf-info-label">Email</p>
                <p class="mf-info-value">{{ $order->email }}</p>
            </div>

            <div>
                <p class="mf-info-label">WhatsApp</p>
                <p class="mf-info-value">{{ $order->whatsapp ?? '-' }}</p>
            </div>

            <div>
                <p class="mf-info-label">Produk</p>
                <p class="mf-info-value">{{ $order->product_snapshot }}</p>
            </div>

            <div>
                <p class="mf-info-label">Harga Satuan</p>
                <p class="mf-info-value">
                    Rp {{ number_format($order->harga_satuan, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="mf-info-label">Jumlah</p>
                <p class="mf-info-value">{{ $order->jumlah }} pcs</p>
            </div>

            <div>
                <p class="mf-info-label">Total Harga</p>
                <p class="mf-info-value">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="mf-info-label">Metode Pembayaran</p>
                <p class="mf-info-value">{{ $order->metode_pembayaran ?? '-' }}</p>
            </div>

            <div>
                <p class="mf-info-label">Status Saat Ini</p>
                <p class="mf-info-value">
                    @if($order->status === 'diproses')
                        Diproses
                    @elseif($order->status === 'dikirim')
                        Dikirim
                    @elseif($order->status === 'selesai')
                        Selesai
                    @elseif($order->status === 'ditolak')
                        Ditolak
                    @else
                        -
                    @endif
                    
                </p>
            </div>
        </div>

        <div style="margin-top: 18px;">
            <p class="mf-info-label">Alamat Pengiriman</p>
            <p class="mf-info-value">{{ $order->alamat }}</p>
        </div>

        @if($order->catatan)
            <div style="margin-top: 18px;">
                <p class="mf-info-label">Catatan Customer</p>
                <p class="mf-info-value">{{ $order->catatan }}</p>
            </div>
        @endif

        @if($order->bukti_pembayaran)
            <div style="margin-top: 18px;">
                <p class="mf-info-label" style="margin-bottom: 8px;">Bukti Pembayaran</p>

                <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}"
                         class="mf-proof-img"
                         style="width: 150px; height: 150px;"
                         alt="Bukti pembayaran">
                </a>
            </div>
        @else
            <div style="margin-top: 18px;">
                <p class="mf-info-label">Bukti Pembayaran</p>
                <p class="mf-info-value">Belum ada bukti pembayaran.</p>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}"
            data-validate-form
            novalidate>
        @csrf
        @method('PUT')

        <div class="mf-field">
            <label class="mf-label">
                Status Pesanan *
            </label>

            <select name="status" class="mf-select"
                    required
                    data-label="Status pesanan"
                    data-msg-required="Status pesanan wajib dipilih sebelum disimpan.">
                <option value="">-- Pilih Status --</option>

                <option value="diproses" @selected(old('status', $order->status) === 'diproses')>
                    Diproses
                </option>

                <option value="dikirim" @selected(old('status', $order->status) === 'dikirim')>
                    Dikirim
                </option>

                <option value="selesai" @selected(old('status', $order->status) === 'selesai')>
                    Selesai
                </option>
                <option value="ditolak" @selected(old('status', $order->status) === 'ditolak')>
                    Ditolak
                </option>
            </select>

            @error('status')
                <div class="mf-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mf-actions">
            <a href="{{ route('admin.orders.index') }}" class="mf-btn mf-btn-secondary">
                Batal
            </a>

            <button type="submit" class="mf-btn mf-btn-primary">
                Update Status
            </button>
        </div>
    </form>
</div>
@endsection