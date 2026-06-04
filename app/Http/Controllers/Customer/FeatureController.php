<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Throwable;


class FeatureController extends Controller
{
    public function weatherSurabaya()
    {
        try {
            $response = Http::timeout(10)->get('https://wttr.in/Surabaya', [
                'format' => 'j1',
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data cuaca belum dapat diambil.',
                ], 500);
            }

            $data = $response->json();

            $current = $data['current_condition'][0] ?? [];
            $nearest = $data['nearest_area'][0] ?? [];

            return response()->json([
                'success' => true,
                'city' => $nearest['areaName'][0]['value'] ?? 'Surabaya',
                'temperature' => $current['temp_C'] ?? '-',
                'condition' => $current['weatherDesc'][0]['value'] ?? '-',
                'description' => 'Kelembapan ' . ($current['humidity'] ?? '-') . '%, angin ' . ($current['windspeedKmph'] ?? '-') . ' km/jam.',
                'taken_at' => now()->format('d/m/Y H:i:s'),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data cuaca. Periksa koneksi internet Anda.',
            ], 500);
        }
    }

    public function liveSearchProducts(Request $request)
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
                $query->where('kategori', 'like', "%{$kategori}%");
            })
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'kode_barang' => $product->kode_barang,
                    'nama_produk' => $product->nama_produk,
                    'kategori' => ucfirst($product->kategori),
                    'harga' => 'Rp ' . number_format($product->harga, 0, ',', '.'),
                    'stok' => $product->stok,
                    'status' => $product->status,
                    
                                    'gambar' => $product->gambar
                    ? asset('storage/' . $product->gambar)
                    : null,
                'detail_url' => route('customer.product.detail', $product),
                'checkout_url' => route('customer.checkout', ['product_id' => $product->id]),
                ];
            });
        return response()->json([
            'success' => true,
            'products' => $products,
        ]); 
    }
    public function preferences(Request $request)
    {
        return view('customer.preferences', [
            'theme' => $request->cookie('theme_preference', 'system'),
            'fontSize' => $request->cookie('font_size_preference', 'normal'),
        ]);
    }

    public function savePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,system',
            'font_size' => 'required|in:normal,large',
        ]);

        return response()
            ->json([
                'success' => true,
                'message' => 'Preferensi tampilan berhasil disimpan.',
                'theme' => $validated['theme'],
                'font_size' => $validated['font_size'],
                'cookie_lama_theme' => $request->cookie('theme_preference'),
                'cookie_lama_font_size' => $request->cookie('font_size_preference'),
            ])
            ->cookie('theme_preference', $validated['theme'], 60 * 24 * 30, '/', null, null, false)
            ->cookie('font_size_preference', $validated['font_size'], 60 * 24 * 30, '/', null, null, false);
    }

    public function resetVisits(Request $request)
    {
        $request->session()->forget([
            'customer_visit_count',
            'customer_first_visit_at',
            'customer_last_visit_at',
        ]);

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Data kunjungan berhasil direset.');
    }
}
