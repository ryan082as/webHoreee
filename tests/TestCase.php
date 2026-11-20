<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Src\Catalog\InCodeCatalogSource;
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

abstract class TestCase extends BaseTestCase
{
    protected Router $router;

    protected function setUp(): void
    {
        parent::setUp();

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $catalog = new InCodeCatalogSource();
        $this->router = new Router();

        $home = new HomeController($catalog);
        $store = new StoreController($catalog);
        $prod = new ProductController($catalog);
        $cart = new CartController($catalog);
        $auth = new AuthController();
        $sub = new SubscriptionController();
        $api = new ApiController($catalog);

        $this->router->get('/', [$home, 'index']);
        $this->router->get('/toko/{slug}', [$store, 'show']);
        $this->router->get('/toko/{slug}/barang/{productSlug}', [$prod, 'show']);
        $this->router->get('/keranjang', [$cart, 'index']);
        $this->router->post('/keranjang/tambah', [$cart, 'add']);
        $this->router->post('/keranjang/update', [$cart, 'update']);
        $this->router->post('/keranjang/hapus', [$cart, 'remove']);
        $this->router->post('/keranjang/checkout', [$cart, 'checkout']);
        $this->router->get('/login', [$auth, 'index']);
        $this->router->post('/login', [$auth, 'store']);
        $this->router->get('/langganan', [$sub, 'index']);
        $this->router->post('/langganan', [$sub, 'store']);
        $this->router->get('/api/stores', [$api, 'stores']);
        $this->router->get('/api/stores/{slug}', [$api, 'storeDetail']);
        $this->router->get('/api/stores/{slug}/barang/{productSlug}', [$api, 'productDetail']);
    }

    public function get(string $uri): TestResponse
    {
        $request = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => $uri]);
        $response = $this->router->dispatch($request);
        return new TestResponse($this, $response);
    }

    public function post(string $uri, array $data = []): TestResponse
    {
        $request = new Request([], $data, ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => $uri]);
        $response = $this->router->dispatch($request);
        return new TestResponse($this, $response);
    }

    public function getJson(string $uri): TestResponse
    {
        return $this->get($uri);
    }
}
