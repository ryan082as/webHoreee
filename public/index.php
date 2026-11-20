<?php

declare(strict_types=1);

/**
 * Front Controller Utama (PHP 8.x OOP Native - Tanpa Framework).
 * Memenuhi kriteria UTS Pemrograman Web UMM:
 * Arsitektur modular, bersih, tanpa framework (Laravel, dsb).
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Src\Catalog\InCodeCatalogSource;
use Src\Controllers\ApiController;
use Src\Controllers\AuthController;
use Src\Controllers\CartController;
use Src\Controllers\HomeController;
use Src\Controllers\ProductController;
use Src\Controllers\StoreController;
use Src\Controllers\SubscriptionController;
use Src\Core\Request;
use Src\Core\Router;

// Inisialisasi Sumber Data Katalog
$catalog = new InCodeCatalogSource();

// Inisialisasi Controllers
$homeController = new HomeController($catalog);
$storeController = new StoreController($catalog);
$productController = new ProductController($catalog);
$cartController = new CartController($catalog);
$authController = new AuthController();
$subscriptionController = new SubscriptionController();
$apiController = new ApiController($catalog);

// Inisialisasi Router
$router = new Router();

// 1. Rute Halaman Web
$router->get('/', [$homeController, 'index']);
$router->get('/toko/{slug}', [$storeController, 'show']);
$router->get('/toko/{slug}/barang/{productSlug}', [$productController, 'show']);

// 2. Rute Keranjang & Pemesanan (WhatsApp Checkout)
$router->get('/keranjang', [$cartController, 'index']);
$router->post('/keranjang/tambah', [$cartController, 'add']);
$router->post('/keranjang/update', [$cartController, 'update']);
$router->post('/keranjang/hapus', [$cartController, 'remove']);
$router->post('/keranjang/checkout', [$cartController, 'checkout']);

// 3. Rute Placeholder Fitur (Login & Langganan)
$router->get('/login', [$authController, 'index']);
$router->post('/login', [$authController, 'store']);
$router->get('/langganan', [$subscriptionController, 'index']);
$router->post('/langganan', [$subscriptionController, 'store']);

// 4. Rute REST API JSON
$router->get('/api/stores', [$apiController, 'stores']);
$router->get('/api/stores/{slug}', [$apiController, 'storeDetail']);
$router->get('/api/stores/{slug}/barang/{productSlug}', [$apiController, 'productDetail']);

// Eksekusi Request & Kirimkan Response
$request = Request::capture();
$response = $router->dispatch($request);
$response->send();
