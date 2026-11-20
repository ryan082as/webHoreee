<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Contracts\CatalogSourceInterface;
use Src\Core\Request;
use Src\Core\Response;
use Src\Entities\StoreEntity;

/**
 * Controller REST API JSON untuk integrasi API katalog multi-toko.
 */
class ApiController
{
    public function __construct(
        private CatalogSourceInterface $catalog
    ) {
    }

    public function stores(Request $request): Response
    {
        $stores = $this->catalog->getStores();
        $data = array_map(fn (StoreEntity $store) => $store->toArray(), $stores);

        return Response::json($data);
    }

    public function storeDetail(Request $request, string $slug): Response
    {
        $store = $this->catalog->findStoreBySlug($slug);

        if ($store === null) {
            return Response::json(['error' => 'Toko tidak ditemukan'], 404);
        }

        return Response::json($store->toArray());
    }

    public function productDetail(Request $request, string $slug, string $productSlug): Response
    {
        $store = $this->catalog->findStoreBySlug($slug);

        if ($store === null) {
            return Response::json(['error' => 'Toko tidak ditemukan'], 404);
        }

        $product = $store->findProductBySlug($productSlug);

        if ($product === null) {
            return Response::json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return Response::json($product->toArray());
    }
}
