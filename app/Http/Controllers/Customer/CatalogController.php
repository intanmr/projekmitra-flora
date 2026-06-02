<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class CatalogController extends Controller
{
public function home()
{
    $products = Product::latest()->take(3)->get();

    $visitCount = session('customer_visit_count', 0) + 1;

    $currentTime = Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s');

    if (! session()->has('customer_first_visit_at')) {
        session([
            'customer_first_visit_at' => $currentTime,
        ]);
    }

    session([
        'customer_visit_count' => $visitCount,
        'customer_last_visit_at' => $currentTime,
    ]);

    $visitStats = [
        'count' => session('customer_visit_count'),
        'first_visit' => session('customer_first_visit_at'),
        'last_visit' => session('customer_last_visit_at'),
    ];

    return view('customer.home', compact('products', 'visitStats'));
}

    public function catalog(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_produk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && in_array($request->kategori, ['indoor', 'outdoor'])) {
            $query->where('kategori', $request->kategori);
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        return view('customer.catalog', compact('products'));
    }

    public function checkout(Request $request)
    {
        if (! $request->filled('product_id')) {
            return redirect()
                ->route('customer.catalog')
                ->with('success', 'Silakan pilih produk terlebih dahulu sebelum melakukan checkout.');
        }

        $selectedProduct = Product::where('id', $request->product_id)
            ->where('stok', '>', 0)
            ->first();

        if (! $selectedProduct) {
            return redirect()
                ->route('customer.catalog')
                ->with('success', 'Produk tidak tersedia atau stok produk sudah habis.');
        }

        return view('customer.checkout', compact('selectedProduct'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|integer|min:1',
            'nama_customer' => 'required|string|max:100',
            'alamat' => 'required|string',
            'email' => 'required|email|ends_with:@gmail.com',
            'whatsapp' => 'nullable|string|max:20',
            'metode_pembayaran' => 'required|string|max:50',
            'catatan' => 'nullable|string',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'product_id.required' => 'Produk tidak boleh kosong.',
            'product_id.exists' => 'Produk tidak valid.',
            'jumlah.required' => 'Jumlah tidak boleh kosong.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'nama_customer.required' => 'Nama lengkap tidak boleh kosong.',
            'alamat.required' => 'Alamat pengiriman tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.ends_with' => 'Email harus menggunakan @gmail.com.',
            'metode_pembayaran.required' => 'Metode pembayaran tidak boleh kosong.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image' => 'File bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berformat jpg, jpeg, png, atau webp.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
        ]);

        $proofPath = $request->file('bukti_pembayaran')
            ->store('payment_proofs', 'public');

        DB::transaction(function () use ($validated, $proofPath) {
            $product = Product::where('id', $validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['jumlah'] > $product->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah pembelian melebihi stok yang tersedia.',
                ]);
            }

            $total = $product->harga * $validated['jumlah'];

            Order::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'product_snapshot' => $product->nama_produk,
                'harga_satuan' => $product->harga,
                'jumlah' => $validated['jumlah'],
                'total_harga' => $total,
                'nama_customer' => $validated['nama_customer'],
                'alamat' => $validated['alamat'],
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan'] ?? null,
                'bukti_pembayaran' => $proofPath,
                'status' => 'diproses',
            ]);

            $product->decrement('stok', $validated['jumlah']);
        });

        return redirect()
            ->route('customer.history')
            ->with('success', 'Pesanan berhasil dikirim. Status pesanan Anda: diproses.');
    }

    public function history(Request $request)
    {
        $query = Order::where('user_id', auth()->id())->latest();

        if ($request->filled('status') && in_array($request->status, ['diproses', 'dikirim', 'selesai', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('customer.history', compact('orders'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('kategori', $product->kategori)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(3)
            ->get();

        return view('customer.detail', compact('product', 'relatedProducts'));
    }

    public function liveSearchHistory(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:100',
            'status' => 'nullable|in:diproses,dikirim,selesai,ditolak',
        ]);

        $keyword = $validated['keyword'] ?? '';
        $status = $validated['status'] ?? '';

        $orders = Order::query()
            ->when(Schema::hasColumn('orders', 'user_id'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when(! Schema::hasColumn('orders', 'user_id'), function ($query) {
                $query->where('email', auth()->user()->email);
            })
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('product_snapshot', 'like', "%{$keyword}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%");
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
                    'tanggal_pesan' => $order->created_at->format('d/m/Y H:i'),
                    'product_snapshot' => $order->product_snapshot,
                    'jumlah' => $order->jumlah,
                    'metode_pembayaran' => $order->metode_pembayaran ?? '-',
                    'total_harga' => 'Rp ' . number_format($order->total_harga, 0, ',', '.'),
                    'status' => $order->status,
                ];
            });

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}