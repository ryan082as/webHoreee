<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Contracts\CatalogSourceInterface;
use Src\Entities\StoreEntity;

class ApiController extends Controller
{
    public function __construct(
        private CatalogSourceInterface $catalog,
    ) {
    }

    public function stores(): JsonResponse
    {
        return response()->json(
            array_map(fn (StoreEntity $store) => $store->toArray(), $this->catalog->getStores()),
        );
    }

    public function storeDetail(string $slug): JsonResponse
    {
        $store = $this->catalog->findStoreBySlug($slug);
        abort_if(! $store, 404);

        return response()->json($store->toArray());
    }

    public function productDetail(string $slug, string $productSlug): JsonResponse
    {
        $store = $this->catalog->findStoreBySlug($slug);
        abort_if(! $store, 404);

        $product = $store->findProductBySlug($productSlug);
        abort_if(! $product, 404);

        return response()->json($product->toArray());
    }
}
