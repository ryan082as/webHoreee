<?php

namespace Src\Catalog;

use Src\Contracts\CatalogSourceInterface;
use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Parent class untuk driver sumber data katalog (shared hydration helper).
 */
abstract class BaseCatalogSource implements CatalogSourceInterface
{
    /**
     * @param array<string, mixed> $storeData
     * @param array<int, array<string, mixed>> $productsData
     */
    protected function hydrateStore(array $storeData, array $productsData = []): StoreEntity
    {
        $products = array_map(
            fn (array $productData) => $this->hydrateProduct($productData),
            $productsData,
        );

        return new StoreEntity(
            id: (int) $storeData['id'],
            slug: (string) $storeData['slug'],
            name: (string) $storeData['name'],
            category: (string) ($storeData['category'] ?? ''),
            description: (string) ($storeData['description'] ?? ''),
            address: (string) ($storeData['address'] ?? ''),
            whatsappNumber: (string) ($storeData['whatsapp_number'] ?? ''),
            operationalHours: (string) ($storeData['operational_hours'] ?? ''),
            products: $products,
        );
    }

    /**
     * @param array<string, mixed> $productData
     */
    protected function hydrateProduct(array $productData): ProductEntity
    {
        return new ProductEntity(
            id: (int) $productData['id'],
            storeId: (int) $productData['store_id'],
            slug: (string) $productData['slug'],
            name: (string) $productData['name'],
            price: (int) $productData['price'],
            thumbnailUrl: $productData['thumbnail_url'] ?? null,
            imageUrl: $productData['image_url'] ?? null,
            description: (string) ($productData['description'] ?? ''),
            specs: is_array($productData['specs'] ?? null) ? $productData['specs'] : [],
        );
    }
}
