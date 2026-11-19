<?php

namespace Src\Catalog;

use App\Models\Product;
use App\Models\Store;
use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Implementasi sumber data database via Eloquent Models (MySQL lokal/server).
 */
class DatabaseCatalogSource extends BaseCatalogSource
{
    public function getStores(): array
    {
        return Store::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Store $store) => $this->hydrateStore($store->toArray()))
            ->all();
    }

    public function findStoreBySlug(string $slug): ?StoreEntity
    {
        $store = Store::with(['products' => fn ($query) => $query->orderBy('id')])
            ->where('slug', $slug)
            ->first();
        if (! $store) {
            return null;
        }

        return $this->hydrateStore(
            $store->toArray(),
            $store->products->toArray(),
        );
    }

    public function findStoreById(int $id): ?StoreEntity
    {
        $store = Store::find($id);

        return $store ? $this->hydrateStore($store->toArray()) : null;
    }

    public function findProductById(int $productId): ?ProductEntity
    {
        $product = Product::find($productId);

        return $product ? $this->hydrateProduct($product->toArray()) : null;
    }
}
