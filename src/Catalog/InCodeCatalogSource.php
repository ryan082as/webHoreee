<?php

declare(strict_types=1);

namespace Src\Catalog;

use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Sumber data berbasis Array/PHP Object Class tanpa ketergantungan framework.
 * Mengimplementasikan kontrak CatalogSourceInterface.
 */
class InCodeCatalogSource extends BaseCatalogSource
{
    private string $dataFilePath;

    public function __construct(?string $dataFilePath = null)
    {
        if ($dataFilePath !== null) {
            $this->dataFilePath = $dataFilePath;
        } elseif (file_exists(__DIR__ . '/../../data/database.php')) {
            $this->dataFilePath = __DIR__ . '/../../data/database.php';
        } else {
            $this->dataFilePath = __DIR__ . '/../../database/databaseInCode.php';
        }
    }

    public function getStores(): array
    {
        $db = $this->readData();

        return array_map(
            fn (array $store) => $this->hydrateStore($store),
            $db['stores'] ?? [],
        );
    }

    public function findStoreBySlug(string $slug): ?StoreEntity
    {
        $db = $this->readData();

        foreach ($db['stores'] ?? [] as $store) {
            if (($store['slug'] ?? '') === $slug) {
                $products = array_values(array_filter(
                    $db['products'] ?? [],
                    fn (array $product) => (int) ($product['store_id'] ?? 0) === (int) $store['id'],
                ));

                return $this->hydrateStore($store, $products);
            }
        }

        return null;
    }

    public function findStoreById(int $id): ?StoreEntity
    {
        $db = $this->readData();

        foreach ($db['stores'] ?? [] as $store) {
            if ((int) ($store['id'] ?? 0) === $id) {
                return $this->hydrateStore($store);
            }
        }

        return null;
    }

    public function findProductById(int $productId): ?ProductEntity
    {
        $db = $this->readData();

        foreach ($db['products'] ?? [] as $product) {
            if ((int) ($product['id'] ?? 0) === $productId) {
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
        if (!file_exists($this->dataFilePath)) {
            return ['stores' => [], 'products' => []];
        }

        /** @var array $data */
        $data = require $this->dataFilePath;
        return is_array($data) ? $data : ['stores' => [], 'products' => []];
    }
}
