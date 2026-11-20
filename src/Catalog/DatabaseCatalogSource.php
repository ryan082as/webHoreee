<?php

declare(strict_types=1);

namespace Src\Catalog;

use PDO;
use PDOException;
use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;

/**
 * Sumber data berbasis Database MySQL menggunakan PDO native PHP (tanpa framework/ORM).
 */
class DatabaseCatalogSource extends BaseCatalogSource
{
    private ?PDO $pdo = null;

    public function __construct(
        string $host = '127.0.0.1',
        int $port = 3306,
        string $database = 'katalog_lokal',
        string $username = 'root',
        string $password = ''
    ) {
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException) {
            $this->pdo = null;
        }
    }

    public function getStores(): array
    {
        if ($this->pdo === null) {
            return [];
        }

        $stmt = $this->pdo->query('SELECT * FROM stores ORDER BY id ASC');
        $stores = $stmt->fetchAll();

        return array_map(fn (array $store) => $this->hydrateStore($store), $stores);
    }

    public function findStoreBySlug(string $slug): ?StoreEntity
    {
        if ($this->pdo === null) {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM stores WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $store = $stmt->fetch();

        if (!$store) {
            return null;
        }

        $prodStmt = $this->pdo->prepare('SELECT * FROM products WHERE store_id = :store_id ORDER BY id ASC');
        $prodStmt->execute(['store_id' => $store['id']]);
        $products = $prodStmt->fetchAll();

        return $this->hydrateStore($store, $products);
    }

    public function findStoreById(int $id): ?StoreEntity
    {
        if ($this->pdo === null) {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM stores WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $store = $stmt->fetch();

        return $store ? $this->hydrateStore($store) : null;
    }

    public function findProductById(int $productId): ?ProductEntity
    {
        if ($this->pdo === null) {
            return null;
        }

        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch();

        return $product ? $this->hydrateProduct($product) : null;
    }
}
