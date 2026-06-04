<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\FeatureController;
use App\Models\Product;
use App\Models\Order;


//halaman awal akan diarahkan ke halaman login, jika belum login maka akan diarahkan ke halaman login, 
//jika sudah login maka akan diarahkan ke dashboard sesuai dengan role masing-masing (admin atau customer)
Route::get('/', function () {
    return redirect()->route('login');
});


//route dashboard utama untuk mengarahkan user ke dashboard admin atau customer (sesuai dengan role)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware(['auth'])->name('dashboard');


//route untuk admin, hanya bisa diakses oleh user dengan role admin yang sudah login, 
//untuk proteksinya menggunakan middleware 'auth' dan 'admin', dengan prefix 'admin' dan nama route 'admin.'
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
                'total_pendapatan' => Order::where('status', 'selesai')->sum('total_harga'),
                'jumlah_produk_tersedia' => Product::where('stok', '>', 0)->count(),
                'total_pesanan' => Order::count(),
            ];

            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');
        Route::get('/profile', function () {
            return view('admin.profile');
        })->name('profile');
        Route::post('/products/live-search', [AdminProductController::class, 'liveSearch'])
            ->name('products.live-search'); //route live search produk untuk fitur pencarion produk secara AJAX (data ditampilkan tanpa reload halaman)
        Route::resource('products', AdminProductController::class); //route resource untuk produk, dengan method index, create, store, show, edit, update, destroy (menjalankan CRUD produk)
        Route::post('/orders/live-search', [AdminOrderController::class, 'liveSearch'])
            ->name('orders.live-search'); //route live search pesanan untuk mencari data pesanan admin secara AJAX 
        Route::resource('orders', AdminOrderController::class)->only(['index', 'edit', 'update', 'destroy']); //route resource pesanan dibatasi hanya untuk melihat, mengedit status, memperbarui status, dan menghapus pesanan
    });

//route untuk customer, hanya bisa diakses oleh user customer yang sudah login
//middleware customer untuk mencegah user dengan role admin masuk ke halaman customer 
Route::middleware(['auth', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CatalogController::class, 'home'])->name('dashboard');
        Route::get('/katalog', [CatalogController::class, 'catalog'])->name('catalog'); 
        Route::get('/produk/{product}', [CatalogController::class, 'show'])->name('product.detail');
        Route::get('/beli', [CatalogController::class, 'checkout'])->name('checkout');
        Route::post('/pesanan', [CatalogController::class, 'storeOrder'])->name('orders.store');
        Route::get('/riwayat', [CatalogController::class, 'history'])->name('history');
        Route::post('/riwayat/live-search', [CatalogController::class, 'liveSearchHistory'])
            ->name('history.live-search');
        Route::get('/cuaca-surabaya', [FeatureController::class, 'weatherSurabaya'])->name('weather.surabaya');
        Route::post('/live-search-produk', [FeatureController::class, 'liveSearchProducts'])->name('live-search.products');
        Route::get('/preferensi', [FeatureController::class, 'preferences'])->name('preferences');
        Route::post('/preferensi/simpan', [FeatureController::class, 'savePreferences'])->name('preferences.save');
        Route::post('/kunjungan/reset', [FeatureController::class, 'resetVisits'])->name('visits.reset');
        Route::get('/profile', [CustomerProfileController::class, 'show'])
            ->name('profile');

        Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [CustomerProfileController::class, 'updateProfile'])
            ->name('profile.update');

        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])
            ->name('profile.password.update');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';