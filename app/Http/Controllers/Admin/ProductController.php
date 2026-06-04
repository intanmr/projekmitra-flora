<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //menampilkan daftar produk untuk admin,
    //data dapat difilter berdasarkan keyword pencarian dan kategori (indoor/outdoor)
    public function index(Request $request)
    {
        $query = Product::query();

        //filter pencarian berdasarkan kode barang/nama produk 
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_produk', 'like', "%{$search}%");
            });
        }

        //filter kategori hanya menerima pilihan indoor dan outdoor
        if ($request->filled('kategori') && in_array($request->kategori, ['indoor', 'outdoor'])) {
            $query->where('kategori', $request->kategori);
        }

        //pagination digunakan agar data produk tidak ditampilkan terlalu banyak dalam satu halaman.
        $products = $query->latest()->paginate(10)->withQueryString();

        //statistik produk untuk dashboard admin 
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

    //untuk menyimpan produk baru ke database, data produk akan divalidasi di sisi server,
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

        // Jika admin mengunggah gambar, file disimpan ke folder storage/app/public/products.
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        // Menambahkan data produk ke dalam database 
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

    //untuk memperbarui data produk yang sudah ada 
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

        //jika gambar baru diunggah, maka gambar lama akan dihapus dari storage dan digantikan dengan gambar baru
        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            //menyimpan gambar baru ke storage dan memperbarui path gambar di database
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($validated);

        //mengembalikan response dengan pesan sukses setelah produk berhasil diperbarui
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    //untuk menghapus data produk 
    public function destroy(Product $product)
    {
        //menghapus gambar produk dari storage
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        //menghapus data produk dari database
        $product->delete();

        //mengembalikan response dengan pesan sukses setelah produk berhasil dihapus
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    
    public function liveSearch(Request $request)
    {
        //validasi input pencarian untuk memastikan data yang diterima sesuai dengan format 
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:100',
            'kategori' => 'nullable|in:indoor,outdoor',
        ]);


        $keyword = $validated['keyword'] ?? '';
        $kategori = $validated['kategori'] ?? '';

        //query untuk mencari produk berdasarkan keyword dan kategori
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
    
                //format data produk untuk ditampilkan di frontend, termasuk format harga dan tanggal masuk
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

        //penerapan AJAX dan JSON untuk menampilkan hasil pencarian tanpa reload halaman 
        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }
}