<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    // auth()->logout();
    // request()->session()->invalidate();
    // request()->session()->regenerateToken();
    // return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Prefix  : /admin
| Middleware: auth (uncomment when auth is ready)
*/
Route::prefix('admin')->name('admin.')->group(function () {
// ->middleware(['auth'])  // ← aktifkan setelah auth siap

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::resource('categories', CategoryController::class)->only(['index']);

    // Produk
    Route::resource('products', ProductController::class)->only(['index']);

    // Order
    Route::resource('orders', OrderController::class)->only(['index']);
});
