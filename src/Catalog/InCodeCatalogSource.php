<?php

namespace Src\Catalog;

use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Implementasi sumber data sementara dari database/databaseInCode.php.
 */
class InCodeCatalogSource extends BaseCatalogSource
{
    public function __construct(
        private string $dataFilePath,
    ) {
    }

    public function getStores(): array
    {
        $db = $this->readData();

        return array_map(
            fn (array $store) => $this->hydrateStore($store),
            $db['stores'],
        );
    }

    public function findStoreBySlug(string $slug): ?StoreEntity
    {
        $db = $this->readData();

        foreach ($db['stores'] as $store) {
            if (($store['slug'] ?? '') === $slug) {
                $products = array_values(array_filter(
                    $db['products'],
                    fn (array $product) => (int) $product['store_id'] === (int) $store['id'],
                ));

                return $this->hydrateStore($store, $products);
            }
        }

        return null;
    }

    public function findStoreById(int $id): ?StoreEntity
    {
        $db = $this->readData();

        foreach ($db['stores'] as $store) {
            if ((int) $store['id'] === $id) {
                return $this->hydrateStore($store);
            }
        }

        return null;
    }

    public function findProductById(int $productId): ?ProductEntity
    {
        $db = $this->readData();

        foreach ($db['products'] as $product) {
            if ((int) $product['id'] === $productId) {
                return $this->hydrateProduct($product);
            }
        }

        return null;
    }

    /**
     * @return array{stores: array<int, array<string, mixed>>, products: array<int, array<string, mixed>>}
     */
    private function readData(): array
    {
        return require $this->dataFilePath;
    }
}
