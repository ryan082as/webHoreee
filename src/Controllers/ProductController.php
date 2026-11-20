<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Contracts\CatalogSourceInterface;
use Src\Core\Request;
use Src\Core\Response;

class ProductController
{
    public function __construct(
        private CatalogSourceInterface $catalog
    ) {
    }

    public function show(Request $request, string $slug, string $productSlug): Response
    {
        $store = $this->catalog->findStoreBySlug($slug);
        if ($store === null) {
            return new Response('Toko tidak ditemukan', 404);
        }

        $product = $store->findProductBySlug($productSlug);
        if ($product === null) {
            return new Response('Barang tidak ditemukan', 404);
        }

        return Response::view('product', [
            'store' => $store,
            'product' => $product,
            'title' => $product->getName() . ' - ' . $store->getName(),
        ]);
    }
}
