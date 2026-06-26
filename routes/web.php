<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class, 'index'])->name('/');

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductsController::class, 'show'])->name('product.show');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::post('/payment/callback', [CheckoutController::class, 'callback'])->name('payment.callback');

// RajaOngkir Shipping API (public, tidak butuh auth)
Route::prefix('shipping')->middleware(['auth'])->name('shipping.')->group(function () {
    Route::get('/provinces', [ShippingController::class, 'provinces'])->name('provinces');
    Route::get('/cities', [ShippingController::class, 'cities'])->name('cities');
    Route::post('/cost', [ShippingController::class, 'cost'])->name('cost');
});

Route::middleware('auth')->group(function () {
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Category
    Route::resource('categories', CategoryController::class)->names('categories');

    // Product
    Route::resource('products', ProductController::class)->names('products');
    Route::delete('product-images/{id}', [ProductController::class, 'destroyImage'])->name('product-images.destroy');

    // Order
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // User
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
});
