<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['product', 'user'])->latest();

        if ($request->filled('status') && in_array($request->status, ['diproses', 'dikirim', 'selesai', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('product_snapshot', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $stats = [
            'diproses' => Order::where('status', 'diproses')->count(),
            'dikirim' => Order::where('status', 'dikirim')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
            'ditolak' => Order::where('status', 'ditolak')->count(),
            'total' => Order::count(),
            'total_pendapatan' => Order::where('status', 'selesai')->sum('total_harga'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai,ditolak',
        ], [
            'status.required' => 'Status pesanan tidak boleh kosong.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        if ($order->bukti_pembayaran) {
            Storage::disk('public')->delete($order->bukti_pembayaran);
        }

        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
    public function liveSearch(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:100',
            'status' => 'nullable|in:diproses,dikirim,selesai,ditolak',
        ]);

        $keyword = $validated['keyword'] ?? '';
        $status = $validated['status'] ?? '';

        $orders = Order::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_customer', 'like', "%{$keyword}%")
                        ->orWhere('product_snapshot', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('whatsapp', 'like', "%{$keyword}%")
                        ->orWhere('metode_pembayaran', 'like', "%{$keyword}%");
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tanggal_pesanan' => $order->created_at
                    ? $order->created_at->format('d/m/Y H:i')
                    : '-',
                    'nama_customer' => $order->nama_customer,
                    'email' => $order->email,
                    'whatsapp' => $order->whatsapp ?? '-',
                    'product_snapshot' => $order->product_snapshot,
                    'harga_satuan' => 'Rp ' . number_format($order->harga_satuan, 0, ',', '.'),
                    'jumlah' => $order->jumlah,
                    'total_harga' => 'Rp ' . number_format($order->total_harga, 0, ',', '.'),
                    'metode_pembayaran' => $order->metode_pembayaran,
                    'status' => $order->status,
                    'bukti_pembayaran' => $order->bukti_pembayaran
                        ? asset('storage/' . $order->bukti_pembayaran)
                        : null,
                    'edit_url' => route('admin.orders.edit', $order),
                    'delete_url' => route('admin.orders.destroy', $order),
                ];
            });

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}