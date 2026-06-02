@extends('layouts.customer')

@section('content')
<div class="cf-card">
    <div class="cf-header">
        <h1 class="cf-title">
            Riwayat Pesanan
        </h1>

        <p class="cf-subtitle">
            Lihat daftar pesanan, metode pembayaran, dan status pengiriman Anda.
        </p>
    </div>

    <div class="cf-filter-row">
        <input type="text"
               id="customerHistorySearchInput"
               class="cf-input"
               placeholder="Cari nama produk atau metode pembayaran...">

        <select id="customerHistoryStatus" class="cf-select">
            <option value="">Semua Status</option>
            <option value="diproses">Diproses</option>
            <option value="dikirim">Dikirim</option>
            <option value="selesai">Selesai</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <button type="button" id="customerHistoryReset" class="cf-btn cf-btn-secondary">
            Reset
        </button>
    </div>

    <div id="customerHistoryLoading" class="cf-loading cf-hidden">
        Mencari riwayat pesanan...
    </div>

    <div class="cf-table-wrapper">
        <table class="cf-table">
            <thead>
                <tr>
                    <th>Tanggal Pesan</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="customerHistoryTableBody">
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>

                        <td class="cf-total-text">
                            {{ $order->product_snapshot }}
                        </td>

                        <td>{{ $order->jumlah }} pcs</td>

                        <td>{{ $order->metode_pembayaran ?? '-' }}</td>

                        <td class="cf-total-text">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($order->status === 'diproses')
                                <span class="cf-badge cf-badge-warning">
                                    Diproses
                                </span>
                            @elseif($order->status === 'dikirim')
                                <span class="cf-badge cf-badge-blue">
                                    Dikirim
                                </span>
                            @elseif($order->status === 'selesai')
                                <span class="cf-badge cf-badge-green">
                                    Selesai
                                </span>
                            @elseif($order->status === 'ditolak')
                                <span class="cf-badge cf-badge-red">
                                    Ditolak
                                </span>
                            @else
                                <span class="cf-badge cf-badge-gray">
                                    -
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="cf-empty">
                            Belum ada riwayat pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="customerHistoryPagination" class="cf-pagination">
        {{ $orders->links() }}
    </div>
</div>

<script>
    const historySearchInput = document.getElementById('customerHistorySearchInput');
    const historyStatusInput = document.getElementById('customerHistoryStatus');
    const historyResetButton = document.getElementById('customerHistoryReset');
    const historyTableBody = document.getElementById('customerHistoryTableBody');
    const historyLoading = document.getElementById('customerHistoryLoading');
    const historyPagination = document.getElementById('customerHistoryPagination');

    const initialHistoryTableHTML = historyTableBody.innerHTML;
    const initialHistoryPaginationHTML = historyPagination ? historyPagination.innerHTML : '';

    let historySearchTimer = null;

    function getHistoryStatusBadge(status) {
        if (status === 'diproses') {
            return `<span class="cf-badge cf-badge-warning">Diproses</span>`;
        }

        if (status === 'dikirim') {
            return `<span class="cf-badge cf-badge-blue">Dikirim</span>`;
        }

        return `<span class="cf-badge cf-badge-green">Selesai</span>`;
    }

    function renderHistoryRows(orders) {
        if (orders.length === 0) {
            historyTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="cf-empty">
                        Riwayat pesanan tidak ditemukan.
                    </td>
                </tr>
            `;
            return;
        }

        historyTableBody.innerHTML = orders.map(order => {
            return `
                <tr>
                    <td>${order.tanggal_pesan}</td>
                    <td class="cf-total-text">${order.product_snapshot}</td>
                    <td>${order.jumlah} pcs</td>
                    <td>${order.metode_pembayaran ?? '-'}</td>
                    <td class="cf-total-text">${order.total_harga}</td>
                    <td>${getHistoryStatusBadge(order.status)}</td>
                </tr>
            `;
        }).join('');
    }

    async function liveSearchHistory() {
        const keyword = historySearchInput.value.trim();
        const status = historyStatusInput.value;

        if (keyword === '' && status === '') {
            historyTableBody.innerHTML = initialHistoryTableHTML;

            if (historyPagination) {
                historyPagination.innerHTML = initialHistoryPaginationHTML;
                historyPagination.classList.remove('cf-hidden');
            }

            return;
        }

        historyLoading.classList.remove('cf-hidden');

        if (historyPagination) {
            historyPagination.classList.add('cf-hidden');
        }

        try {
            const response = await fetch("{{ route('customer.history.live-search') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    keyword: keyword,
                    status: status,
                }),
            });

            const data = await response.json();

            historyLoading.classList.add('cf-hidden');

            if (data.success) {
                renderHistoryRows(data.orders);
            } else {
                historyTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="cf-empty">
                            Data riwayat gagal dimuat.
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            historyLoading.classList.add('cf-hidden');

            historyTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="cf-empty">
                        Terjadi kesalahan saat mencari riwayat pesanan.
                    </td>
                </tr>
            `;
        }
    }

    historySearchInput.addEventListener('input', function () {
        clearTimeout(historySearchTimer);

        historySearchTimer = setTimeout(() => {
            liveSearchHistory();
        }, 400);
    });

    historyStatusInput.addEventListener('change', function () {
        liveSearchHistory();
    });

    historyResetButton.addEventListener('click', function () {
        historySearchInput.value = '';
        historyStatusInput.value = '';
        liveSearchHistory();
    });
</script>
@endsection