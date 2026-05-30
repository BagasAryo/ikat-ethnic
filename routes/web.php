<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('pages.home');
})->name('/');

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductsController::class, 'show'])->name('product.show');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::post('/payment/callback', [CheckoutController::class, 'callback'])->name('payment.callback');

Route::middleware('auth')->group(function () {
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/user/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/user/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/user/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::get('/user/orders', [UserController::class, 'myOrders'])->name('orders');
    Route::get('/user/orders/{id}', [UserController::class, 'showOrder'])->name('orders.show');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    // Category
    Route::resource('categories', CategoryController::class)->names('categories');

    // Product
    Route::resource('products', ProductController::class)->names('products');

    // Order
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
