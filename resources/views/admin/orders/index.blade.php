@extends('layouts.admin')

@section('content')
<div class="mf-stats-grid">
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon blue">📋</div>
        <p class="mf-stat-label">Total Pesanan</p>
        <h2 class="mf-stat-number blue">{{ $stats['total'] }}</h2>
        <p class="mf-stat-sub">Seluruh pesanan masuk</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill blue" style="width: 100%"></div>
        </div>
        <span class="mf-stat-badge blue">Semua</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon amber">⏳</div>
        <p class="mf-stat-label">Diproses</p>
        <h2 class="mf-stat-number yellow">{{ $stats['diproses'] }}</h2>
        <p class="mf-stat-sub">Menunggu konfirmasi</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill amber"
                 style="width: {{ $stats['total'] > 0 ? round($stats['diproses'] / $stats['total'] * 100) : 0 }}%">
            </div>
        </div>
        <span class="mf-stat-badge amber">Diproses</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon blue">🚚</div>
        <p class="mf-stat-label">Dikirim</p>
        <h2 class="mf-stat-number blue">{{ $stats['dikirim'] }}</h2>
        <p class="mf-stat-sub">Dalam pengiriman</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill blue"
                 style="width: {{ $stats['total'] > 0 ? round($stats['dikirim'] / $stats['total'] * 100) : 0 }}%">
            </div>
        </div>
        <span class="mf-stat-badge blue">Dikirim</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon green">✅</div>
        <p class="mf-stat-label">Selesai</p>
        <h2 class="mf-stat-number green">{{ $stats['selesai'] }}</h2>
        <p class="mf-stat-sub">Pesanan berhasil</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill green"
                 style="width: {{ $stats['total'] > 0 ? round($stats['selesai'] / $stats['total'] * 100) : 0 }}%">
            </div>
        </div>
        <span class="mf-stat-badge green">Selesai</span>
    </div>
 
    <div class="mf-stat-card">
        <div class="mf-stat-icon green">💰</div>
        <p class="mf-stat-label">Pendapatan Total</p>
        <h2 class="mf-stat-number green" style="font-size:22px; letter-spacing:-.5px">
            Rp {{ number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') }}
        </h2>
        <p class="mf-stat-sub">Dari pesanan selesai</p>
        <div class="mf-stat-bar">
            <div class="mf-stat-bar-fill green" style="width: 70%"></div>
        </div>
        <span class="mf-stat-badge green">Pendapatan</span>
    </div>
 
</div>

<div class="mf-card">
    <div class="mf-header">
        <h1 class="mf-title">
            Data Pesanan
        </h1>

        <p class="mf-subtitle">
            Kelola pesanan customer dan ubah status transaksi.
        </p>
    </div>

    <div class="mf-filter-row">
        <input type="text"
               id="adminOrderSearchInput"
               class="mf-input"
               placeholder="Cari customer, produk, email, atau WhatsApp...">

        <select id="adminOrderStatus" class="mf-select">
            <option value="">Semua Status</option>
            <option value="diproses">Diproses</option>
            <option value="dikirim">Dikirim</option>
            <option value="selesai">Selesai</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <button type="button" id="adminOrderReset" class="mf-btn mf-btn-secondary">
            Reset
        </button>
    </div>

    <div id="adminOrderLoading" class="mf-loading mf-hidden">
        Mencari pesanan...
    </div>

    <div class="mf-table-wrapper">
        <table class="mf-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="adminOrderTableBody">
                @forelse($orders as $order)
                    <tr>
                        <td>
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            <p class="mf-customer-name">{{ $order->nama_customer }}</p>
                            <p class="mf-small-text">{{ $order->email }}</p>
                            <p class="mf-small-text">{{ $order->whatsapp ?? '-' }}</p>
                        </td>

                        <td>
                            <p class="mf-product-name">{{ $order->product_snapshot }}</p>
                            <p class="mf-small-text">
                                Rp {{ number_format($order->harga_satuan, 0, ',', '.') }}
                            </p>
                        </td>

                        <td>{{ $order->jumlah }} pcs</td>

                        <td class="mf-total-text">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>

                        <td>{{ $order->metode_pembayaran ?? '-' }}</td>

                        <td>
                            @if($order->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}"
                                         class="mf-proof-img"
                                         alt="Bukti pembayaran">
                                </a>
                            @else
                                <span class="mf-small-text">-</span>
                            @endif
                        </td>

                        <td>
                            @if($order->status === 'diproses')
                                <span class="mf-badge mf-badge-warning">Diproses</span>
                            @elseif($order->status === 'dikirim')
                                <span class="mf-badge mf-badge-blue">Dikirim</span>
                            @elseif($order->status === 'selesai')
                                <span class="mf-badge mf-badge-green">Selesai</span>
                            @elseif($order->status === 'ditolak')
                                <span class="mf-badge mf-badge-red">Ditolak</span>
                            @else
                                <span class="mf-badge mf-badge-gray">-</span>
                            @endif
                        </td>

                        <td>
                            <div class="mf-action-group">
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                   class="mf-icon-btn mf-icon-edit"
                                   title="Edit status">
                                    ✏️
                                </a>

                                <button type="button"
                                        class="mf-icon-btn mf-icon-delete"
                                        title="Hapus pesanan"
                                        onclick="openDeleteModal('{{ route('admin.orders.destroy', $order) }}')">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="mf-empty">
                            Belum ada pesanan. Pesanan akan muncul setelah customer melakukan pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="adminOrderPagination" class="mf-pagination">
        {{ $orders->links() }}
    </div>
</div>

<div id="deleteModal" class="mf-modal">
    <div class="mf-modal-box">
        <div class="mf-modal-icon">🗑️</div>

        <h2 class="mf-modal-title">Konfirmasi Hapus</h2>

        <p class="mf-modal-text">
            Yakin ingin menghapus pesanan ini?
        </p>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="mf-modal-actions">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="mf-btn mf-btn-secondary">
                    Batal
                </button>

                <button type="submit" class="mf-btn mf-btn-danger">
                    Yakin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');

        form.action = actionUrl;
        modal.classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    const orderSearchInput = document.getElementById('adminOrderSearchInput');
    const orderStatusInput = document.getElementById('adminOrderStatus');
    const orderResetButton = document.getElementById('adminOrderReset');
    const orderTableBody = document.getElementById('adminOrderTableBody');
    const orderLoading = document.getElementById('adminOrderLoading');
    const orderPagination = document.getElementById('adminOrderPagination');

    const initialOrderTableHTML = orderTableBody.innerHTML;
    const initialOrderPaginationHTML = orderPagination ? orderPagination.innerHTML : '';

    let orderSearchTimer = null;

    function getOrderStatusBadge(status) {
        if (status === 'diproses') {
            return `<span class="mf-badge mf-badge-warning">Diproses</span>`;
        }

        if (status === 'dikirim') {
            return `<span class="mf-badge mf-badge-blue">Dikirim</span>`;
        }

        if (status === 'selesai') {
            return `<span class="mf-badge mf-badge-green">Selesai</span>`;
        }

        if (status === 'ditolak') {
            return `<span class="mf-badge mf-badge-red">Ditolak</span>`;
        }

        return `<span class="mf-badge mf-badge-gray">-</span>`;
    }

    function getPaymentProof(order) {
        if (order.bukti_pembayaran) {
            return `
                <a href="${order.bukti_pembayaran}" target="_blank">
                    <img src="${order.bukti_pembayaran}"
                         class="mf-proof-img"
                         alt="Bukti pembayaran">
                </a>
            `;
        }

        return `<span class="mf-small-text">-</span>`;
    }

    function renderOrderRows(orders) {
        if (orders.length === 0) {
            orderTableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="mf-empty">
                        Pesanan tidak ditemukan.
                    </td>
                </tr>
            `;
            return;
        }

        orderTableBody.innerHTML = orders.map(order => {
            return `
                <tr>
                    <td>
                        ${order.tanggal_pesanan}
                    </td>
                    <td>
                        <p class="mf-customer-name">${order.nama_customer}</p>
                        <p class="mf-small-text">${order.email}</p>
                        <p class="mf-small-text">${order.whatsapp}</p>
                    </td>

                    <td>
                        <p class="mf-product-name">${order.product_snapshot}</p>
                        <p class="mf-small-text">${order.harga_satuan}</p>
                    </td>

                    <td>${order.jumlah} pcs</td>

                    <td class="mf-total-text">${order.total_harga}</td>

                    <td>${order.metode_pembayaran ?? '-'}</td>

                    <td>${getPaymentProof(order)}</td>

                    <td>${getOrderStatusBadge(order.status)}</td>

                    <td>
                        <div class="mf-action-group">
                            <a href="${order.edit_url}"
                               class="mf-icon-btn mf-icon-edit"
                               title="Edit status">
                                ✏️
                            </a>

                            <button type="button"
                                    class="mf-icon-btn mf-icon-delete"
                                    title="Hapus pesanan"
                                    onclick="openDeleteModal('${order.delete_url}')">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function liveSearchOrders() {
        const keyword = orderSearchInput.value.trim();
        const status = orderStatusInput.value;

        if (keyword === '' && status === '') {
            orderTableBody.innerHTML = initialOrderTableHTML;

            if (orderPagination) {
                orderPagination.innerHTML = initialOrderPaginationHTML;
                orderPagination.classList.remove('mf-hidden');
            }

            return;
        }

        orderLoading.classList.remove('mf-hidden');

        if (orderPagination) {
            orderPagination.classList.add('mf-hidden');
        }

        try {
            const response = await fetch("{{ route('admin.orders.live-search') }}", {
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

            orderLoading.classList.add('mf-hidden');

            if (data.success) {
                renderOrderRows(data.orders);
            } else {
                orderTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="mf-empty">
                            Data pesanan gagal dimuat.
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            orderLoading.classList.add('mf-hidden');

            orderTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="mf-empty">
                        Terjadi kesalahan saat mencari pesanan.
                    </td>
                </tr>
            `;
        }
    }

    orderSearchInput.addEventListener('input', function () {
        clearTimeout(orderSearchTimer);

        orderSearchTimer = setTimeout(() => {
            liveSearchOrders();
        }, 400);
    });

    orderStatusInput.addEventListener('change', function () {
        liveSearchOrders();
    });

    orderResetButton.addEventListener('click', function () {
        orderSearchInput.value = '';
        orderStatusInput.value = '';
        liveSearchOrders();
    });
</script>
@endsection