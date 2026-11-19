<?php

namespace Src\Contracts;

use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Kontrak polimorfik sumber data katalog (in-code maupun database).
 */
interface CatalogSourceInterface
{
    /**
     * @return array<int, StoreEntity>
     */
    public function getStores(): array;

    public function findStoreBySlug(string $slug): ?StoreEntity;

    public function findStoreById(int $id): ?StoreEntity;

    public function findProductById(int $productId): ?ProductEntity;
}
