<?php

declare(strict_types=1);

/**
 * Native PHP Test Runner (Tanpa Framework)
 * Menjalankan Unit Test OOP dan Feature Route/Integration Test.
 */

require_once __DIR__ . '/../src/bootstrap.php';

use Src\Catalog\InCodeCatalogSource;
use Src\Contracts\CatalogSourceInterface;
use Src\Controllers\ApiController;
use Src\Controllers\AuthController;
use Src\Controllers\CartController;
use Src\Controllers\HomeController;
use Src\Controllers\ProductController;
use Src\Controllers\StoreController;
use Src\Controllers\SubscriptionController;
use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Router;
use Src\Core\Session;
use Src\Entities\CatalogEntity;
use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;
use Src\Entities\UserEntity;

class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $errors = [];

    public function assert(bool $condition, string $message): void
    {
        if ($condition) {
            $this->passed++;
            echo "  \033[32m✔\033[0m {$message}\n";
        } else {
            $this->failed++;
            $this->errors[] = $message;
            echo "  \033[31m✖\033[0m {$message}\n";
        }
    }

    public function summary(): void
    {
        echo "\n------------------------------------------------------\n";
        echo "Hasil Pengujian: {$this->passed} Passed, {$this->failed} Failed\n";
        if ($this->failed > 0) {
            echo "\033[31mGagal pada:\033[0m\n";
            foreach ($this->errors as $err) {
                echo " - {$err}\n";
            }
            exit(1);
        } else {
            echo "\033[32mSemua pengujian 100% LULUS (Passed)!\033[0m\n";
        }
        echo "------------------------------------------------------\n";
    }
}

$t = new TestRunner();

echo "======================================================\n";
echo "1. UNIT TEST: PENERAPAN OOP (RUBRIK PENILAIAN 40 POIN)\n";
echo "======================================================\n";

$source = new InCodeCatalogSource();
$t->assert($source instanceof CatalogSourceInterface, 'InCodeCatalogSource mengimplementasikan CatalogSourceInterface');

$store = $source->findStoreBySlug('toko-a');
$t->assert($store instanceof StoreEntity, 'Store adalah instance dari StoreEntity');
$t->assert($store instanceof CatalogEntity, 'StoreEntity mewarisi (Inheritance) CatalogEntity');
$t->assert($store->toSummary() === 'Toko A (Kategori A)', 'Polymorphism toSummary() StoreEntity mengembalikan ringkasan yang sesuai');
$t->assert($store->getCleanWhatsappDigits() === '6285704244288', 'Normalisasi nomor WhatsApp toko bekerja dengan benar');

$product = $store->findProductBySlug('barang-contoh-1');
$t->assert($product instanceof ProductEntity, 'Product adalah instance dari ProductEntity');
$t->assert($product instanceof CatalogEntity, 'ProductEntity mewarisi (Inheritance) CatalogEntity');
$t->assert($product->toSummary() === 'Barang Contoh 1 - Rp 50.000', 'Polymorphism toSummary() ProductEntity bekerja dengan benar');
$t->assert($product->getFormattedPrice() === 'Rp 50.000', 'Enkapsulasi harga terformat pada ProductEntity');

// Test User / Member Entity sesuai soal UTS
$user = new UserEntity(1, 'budi-santoso', 'Budi Santoso', 'budi@example.com', 'Member', '08123456789');
$t->assert($user instanceof UserEntity && $user instanceof CatalogEntity, 'UserEntity mewarisi CatalogEntity (Penanganan User/Member)');
$t->assert($user->isMember() === true, 'UserEntity mengenali status keanggotaan Member');
$t->assert($user->toSummary() === 'Budi Santoso [Member] (budi@example.com)', 'Polymorphism toSummary() pada UserEntity bekerja');


echo "\n======================================================\n";
echo "2. FEATURE TEST: RUTE WEB, SEMANTIK & INTEGRASI KERANJANG\n";
echo "======================================================\n";

// Helper membuat router aplikasi
function createAppRouter(CatalogSourceInterface $catalog): Router
{
    $router = new Router();
    $home = new HomeController($catalog);
    $store = new StoreController($catalog);
    $prod = new ProductController($catalog);
    $cart = new CartController($catalog);
    $auth = new AuthController();
    $sub = new SubscriptionController();
    $api = new ApiController($catalog);

    $router->get('/', [$home, 'index']);
    $router->get('/toko/{slug}', [$store, 'show']);
    $router->get('/toko/{slug}/barang/{productSlug}', [$prod, 'show']);
    $router->get('/keranjang', [$cart, 'index']);
    $router->post('/keranjang/tambah', [$cart, 'add']);
    $router->post('/keranjang/update', [$cart, 'update']);
    $router->post('/keranjang/hapus', [$cart, 'remove']);
    $router->post('/keranjang/checkout', [$cart, 'checkout']);
    $router->get('/login', [$auth, 'index']);
    $router->post('/login', [$auth, 'store']);
    $router->get('/langganan', [$sub, 'index']);
    $router->post('/langganan', [$sub, 'store']);
    $router->get('/api/stores', [$api, 'stores']);
    $router->get('/api/stores/{slug}', [$api, 'storeDetail']);
    $router->get('/api/stores/{slug}/barang/{productSlug}', [$api, 'productDetail']);

    return $router;
}

$router = createAppRouter($source);

// Reset session sebelum test
$_SESSION = [];

// A. Test Halaman Utama (/)
$reqHome = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/']);
$resHome = $router->dispatch($reqHome);
$contentHome = $resHome->getContent();

$t->assert($resHome->getStatusCode() === 200, 'GET / mengembalikan HTTP 200 OK');
$t->assert(str_contains($contentHome, '<main'), 'Halaman memuat elemen semantik <main>');
$t->assert(str_contains($contentHome, '<nav class="main-nav" aria-label="Navigasi Utama">'), 'Halaman utama memuat navigasi utama');
$t->assert(str_contains($contentHome, 'Katalog Multi-Toko'), 'Halaman memuat judul aplikasi Katalog Multi-Toko');
$t->assert(str_contains($contentHome, 'Langganan') && str_contains($contentHome, 'Login'), 'Tombol Langganan dan Login tampil di Halaman Utama');
$t->assert(str_contains($contentHome, 'Toko A') && str_contains($contentHome, 'Toko B'), 'Menampilkan daftar Toko A dan Toko B');
$t->assert(str_contains($contentHome, 'floating-cart'), 'Tombol melayang keranjang belanja hadir');

// B. Test Halaman Toko (/toko/toko-a)
$reqStoreA = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/toko/toko-a']);
$resStoreA = $router->dispatch($reqStoreA);
$contentStoreA = $resStoreA->getContent();

$t->assert($resStoreA->getStatusCode() === 200, 'GET /toko/toko-a mengembalikan HTTP 200 OK');
$t->assert(str_contains($contentStoreA, 'Barang Contoh 1'), 'Halaman Toko A memuat produk Barang Contoh 1');
$t->assert(str_contains($contentStoreA, '+62 857-0424-4288'), 'Halaman Toko A memuat nomor WhatsApp admin toko');
$t->assert(str_contains($contentStoreA, 'Rp 50.000'), 'Halaman Toko A memuat harga produk Rp 50.000');
$t->assert(str_contains($contentStoreA, 'Tambah ke Keranjang'), 'Tersedia tombol Tambah ke Keranjang');
$t->assert(str_contains($contentStoreA, 'Detail'), 'Tersedia tombol Detail');
$t->assert(!str_contains($contentStoreA, '>Langganan<') && !str_contains($contentStoreA, '>Login<'), 'Tombol Langganan & Login TIDAK muncul di Halaman Toko');

// Test Toko B
$reqStoreB = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/toko/toko-b']);
$resStoreB = $router->dispatch($reqStoreB);
$contentStoreB = $resStoreB->getContent();
$t->assert($resStoreB->getStatusCode() === 200 && str_contains($contentStoreB, '+62 895-8107-11221'), 'GET /toko/toko-b memuat no WA Toko B');

// C. Test Halaman Detail Produk (/toko/toko-a/barang/barang-contoh-1)
$reqProd = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/toko/toko-a/barang/barang-contoh-1']);
$resProd = $router->dispatch($reqProd);
$contentProd = $resProd->getContent();

$t->assert($resProd->getStatusCode() === 200, 'GET detail produk mengembalikan HTTP 200 OK');
$t->assert(str_contains($contentProd, 'List Detail Barang'), 'Detail produk memuat List Detail Barang');
$t->assert(str_contains($contentProd, 'data-toggle-specs="product-specs-body"'), 'Tersedia interaktivitas JS toggle spesifikasi');
$t->assert(str_contains($contentProd, 'Katun'), 'Memuat spesifikasi bahan katun');
$t->assert(!str_contains($contentProd, 'Tambah ke Keranjang'), 'Halaman detail tidak menampilkan tombol Tambah ke Keranjang');
$t->assert(!str_contains($contentProd, '>Langganan<') && !str_contains($contentProd, '>Login<'), 'Halaman detail tidak menampilkan tombol Langganan & Login');

// D. Test Alur Keranjang & WhatsApp Checkout
$reqAddToCart = new Request([], ['product_id' => '101', 'quantity' => '2'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/tambah']);
$resAddToCart = $router->dispatch($reqAddToCart);
$t->assert($resAddToCart->getStatusCode() === 302, 'POST /keranjang/tambah meredirect pengguna');

$reqCartView = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/keranjang']);
$resCartView = $router->dispatch($reqCartView);
$contentCart = $resCartView->getContent();
$t->assert($resCartView->getStatusCode() === 200, 'GET /keranjang mengembalikan HTTP 200 OK');
$t->assert(str_contains($contentCart, 'Barang Contoh 1'), 'Keranjang memuat nama barang');
$t->assert(str_contains($contentCart, 'Rp 100.000'), 'Subtotal item dihitung dengan benar (2 x 50.000 = Rp 100.000)');
$t->assert(str_contains($contentCart, 'Bayar'), 'Tersedia tombol Bayar per toko');
$t->assert(str_contains($contentCart, 'data-confirm-remove'), 'Form hapus item memiliki attribute interaktif data-confirm-remove');
$t->assert(!str_contains($contentCart, 'Total Keseluruhan'), 'Tidak memuat kotak Total Keseluruhan (sesuai PRD per toko)');

// Update jumlah keranjang
$reqUpdateCart = new Request([], ['product_id' => '101', 'quantity' => '3'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/update']);
$router->dispatch($reqUpdateCart);
$resCartUpdated = $router->dispatch($reqCartView);
$t->assert(str_contains($resCartUpdated->getContent(), 'Rp 150.000'), 'Update kuantitas menjadi 3 mengupdate total menjadi Rp 150.000');

// Checkout WhatsApp Toko A
$reqCheckoutA = new Request([], ['store_id' => '1'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/checkout']);
$resCheckoutA = $router->dispatch($reqCheckoutA);
$locA = $resCheckoutA->getHeaders()['Location'] ?? '';
$t->assert(str_starts_with($locA, 'https://wa.me/6285704244288?text='), 'Checkout Toko A meredirect ke WhatsApp admin Toko A');

// Tambah produk Toko B & Checkout Toko B
$router->dispatch(new Request([], ['product_id' => '201', 'quantity' => '1'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/tambah']));
$resCheckoutB = $router->dispatch(new Request([], ['store_id' => '2'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/checkout']));
$locB = $resCheckoutB->getHeaders()['Location'] ?? '';
$t->assert(str_starts_with($locB, 'https://wa.me/62895810711221?text='), 'Checkout Toko B meredirect ke WhatsApp admin Toko B');

// Hapus item dari keranjang
$router->dispatch(new Request([], ['product_id' => '201'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/hapus']));
$router->dispatch(new Request([], ['product_id' => '101'], ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/keranjang/hapus']));
$resCartEmpty = $router->dispatch($reqCartView);
$t->assert(str_contains($resCartEmpty->getContent(), 'Keranjang masih kosong.'), 'Setelah semua barang dihapus, muncul keterangan Keranjang masih kosong.');

// E. Test Placeholder Login & Langganan
$reqLogin = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/login']);
$resLogin = $router->dispatch($reqLogin);
$t->assert($resLogin->getStatusCode() === 200 && str_contains($resLogin->getContent(), 'Masih dalam pengembangan'), 'GET /login berstatus Masih dalam pengembangan');

$reqLangganan = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/langganan']);
$resLangganan = $router->dispatch($reqLangganan);
$t->assert($resLangganan->getStatusCode() === 200 && str_contains($resLangganan->getContent(), 'Masih dalam pengembangan'), 'GET /langganan berstatus Masih dalam pengembangan');

// F. Test REST API JSON Endpoints
$resApiStores = $router->dispatch(new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/api/stores']));
$t->assert($resApiStores->getStatusCode() === 200, 'GET /api/stores mengembalikan HTTP 200');
$t->assert(str_contains($resApiStores->getContent(), '"slug": "toko-a"'), 'API stores memuat toko-a dalam JSON');

$resApiStoreDetail = $router->dispatch(new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/api/stores/toko-a']));
$t->assert($resApiStoreDetail->getStatusCode() === 200, 'GET /api/stores/toko-a mengembalikan HTTP 200');
$t->assert(str_contains($resApiStoreDetail->getContent(), '"slug": "barang-contoh-1"'), 'API detail toko memuat barang-contoh-1');

$resApiProdDetail = $router->dispatch(new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/api/stores/toko-a/barang/barang-contoh-1']));
$t->assert($resApiProdDetail->getStatusCode() === 200, 'GET /api/stores/toko-a/barang/barang-contoh-1 mengembalikan HTTP 200');
$t->assert(str_contains($resApiProdDetail->getContent(), '"price": 50000'), 'API produk memuat harga 50000');

$resApiNotFound = $router->dispatch(new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/api/stores/tidak-ada']));
$t->assert($resApiNotFound->getStatusCode() === 404, 'GET API yang tidak ada mengembalikan 404');

$t->summary();
