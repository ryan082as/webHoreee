<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Fase 1: Katalog Multi-Toko & Pemesanan
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/toko/{slug}', [StoreController::class, 'show'])->name('store.show');
Route::get('/toko/{slug}/barang/{productSlug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/keranjang/hapus', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/keranjang/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Kerangka Login & Langganan (hanya dipanggil dari Halaman Utama)
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.submit');
Route::get('/langganan', [SubscriptionController::class, 'index'])->name('subscription.index');
Route::post('/langganan', [SubscriptionController::class, 'store'])->name('subscription.submit');
