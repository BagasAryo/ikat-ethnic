<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

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
    Route::resource('categories', CategoryController::class)->names('categories');

    // Produk
    Route::resource('products', ProductController::class)->names('products');

    // Order
    Route::resource('orders', OrderController::class)->names('orders');
});
