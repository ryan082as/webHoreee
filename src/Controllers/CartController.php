<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Contracts\CatalogSourceInterface;
use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Session;

class CartController
{
    public function __construct(
        private CatalogSourceInterface $catalog
    ) {
    }

    public function index(Request $request): Response
    {
        $cart = Session::get('cart', []);

        return Response::view('cart', [
            'cart' => $cart,
            'title' => 'Keranjang & Pemesanan',
        ]);
    }

    public function add(Request $request): Response
    {
        $productId = $request->integer('product_id');
        $qty = max(1, $request->integer('quantity', 1));

        if ($productId <= 0) {
            Session::flash('error', 'Produk tidak valid.');
            return Response::redirect('/keranjang');
        }

        $product = $this->catalog->findProductById($productId);
        if ($product === null) {
            return new Response('Produk tidak ditemukan', 404);
        }

        $store = $this->catalog->findStoreById($product->getStoreId());
        if ($store === null) {
            return new Response('Toko tidak ditemukan', 404);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'store_id' => $store->getId(),
                'store_name' => $store->getName(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $qty,
            ];
        }

        Session::set('cart', $cart);
        Session::flash('notice', 'Barang ditambahkan ke keranjang.');

        $referer = $_SERVER['HTTP_REFERER'] ?? '/toko/' . $store->getSlug();
        return Response::redirect($referer);
    }

    public function update(Request $request): Response
    {
        $productId = $request->integer('product_id');
        $qty = $request->integer('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            if ($qty <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $qty;
            }
            Session::set('cart', $cart);
        }

        return Response::redirect('/keranjang');
    }

    public function remove(Request $request): Response
    {
        $productId = $request->integer('product_id');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::set('cart', $cart);
        }

        return Response::redirect('/keranjang');
    }

    public function checkout(Request $request): Response
    {
        $storeId = $request->integer('store_id');
        $store = $this->catalog->findStoreById($storeId);

        if ($store === null) {
            return new Response('Toko tidak ditemukan', 404);
        }

        $cart = Session::get('cart', []);
        $items = array_filter($cart, fn (array $item) => (int) ($item['store_id'] ?? 0) === $store->getId());

        if (empty($items)) {
            return Response::redirect('/keranjang');
        }

        $lines = ["Halo admin *{$store->getName()}*, saya ingin memesan:", ''];
        $total = 0;

        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $lines[] = "- {$item['name']} ({$item['quantity']}x) = Rp " . number_format($subtotal, 0, ',', '.');
        }

        $lines[] = '';
        $lines[] = 'Total: Rp ' . number_format($total, 0, ',', '.');

        $name = $request->string('name');
        $whatsapp = $request->string('whatsapp');
        $address = $request->string('address');

        if (!empty($name)) {
            $lines[] = "Nama: {$name}";
        }
        if (!empty($whatsapp)) {
            $lines[] = "No. WA: {$whatsapp}";
        }
        if (!empty($address)) {
            $lines[] = "Alamat/Catatan: {$address}";
        }

        $cleanWa = $store->getCleanWhatsappDigits();
        $message = rawurlencode(implode("\n", $lines));
        $waUrl = "https://wa.me/{$cleanWa}?text={$message}";

        return Response::redirect($waUrl);
    }
}
