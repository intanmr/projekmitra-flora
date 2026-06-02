<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
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

        $products = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'indoor' => Product::where('kategori', 'indoor')->count(),
            'outdoor' => Product::where('kategori', 'outdoor')->count(),
            'total_stok' => Product::sum('stok'),
            'hampir_habis' => Product::where('stok', '<=', 3)->where('stok', '>', 0)->count(),
            'total_inventaris' => Product::selectRaw('COALESCE(SUM(harga * stok), 0) as total')
                ->value('total'),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:products,kode_barang',
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'required|in:indoor,outdoor',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_masuk' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang tidak boleh kosong.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_produk.required' => 'Nama produk tidak boleh kosong.',
            'kategori.required' => 'Kategori tidak boleh kosong.',
            'harga.required' => 'Harga tidak boleh kosong.',
            'harga.integer' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok tidak boleh kosong.',
            'stok.integer' => 'Stok harus berupa angka.',
            'tanggal_masuk.date' => 'Tanggal masuk tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:products,kode_barang,' . $product->id,
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'required|in:indoor,outdoor',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_masuk' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang tidak boleh kosong.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_produk.required' => 'Nama produk tidak boleh kosong.',
            'kategori.required' => 'Kategori tidak boleh kosong.',
            'harga.required' => 'Harga tidak boleh kosong.',
            'harga.integer' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok tidak boleh kosong.',
            'stok.integer' => 'Stok harus berupa angka.',
            'tanggal_masuk.date' => 'Tanggal masuk tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);
        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    public function liveSearch(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:100',
            'kategori' => 'nullable|in:indoor,outdoor',
        ]);

        $keyword = $validated['keyword'] ?? '';
        $kategori = $validated['kategori'] ?? '';

        $products = Product::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('kode_barang', 'like', "%{$keyword}%")
                        ->orWhere('nama_produk', 'like', "%{$keyword}%");
                });
            })
            ->when($kategori !== '', function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($product) {
                $status = $product->status;

                return [
                    'id' => $product->id,
                    'kode_barang' => $product->kode_barang,
                    'nama_produk' => $product->nama_produk,
                    'kategori' => ucfirst($product->kategori),
                    'harga' => 'Rp ' . number_format($product->harga, 0, ',', '.'),
                    'stok' => $product->stok,
                    'tanggal_masuk' => $product->tanggal_masuk
                        ? \Carbon\Carbon::parse($product->tanggal_masuk)->format('Y-m-d')
                        : '-',
                    'status' => $status,
                    'gambar' => $product->gambar
                        ? asset('storage/' . $product->gambar)
                        : null,
                    'edit_url' => route('admin.products.edit', $product),
                    'delete_url' => route('admin.products.destroy', $product),
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }
}