<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Src\Catalog\DatabaseCatalogSource;
use Src\Catalog\InCodeCatalogSource;
use Src\Contracts\CatalogSourceInterface;
use Src\Entities\CatalogEntity;
use Src\Entities\ProductEntity;
use Src\Entities\StoreEntity;
use Src\Entities\UserEntity;

class CatalogOopTest extends TestCase
{
    public function test_inheritance_polymorphism_and_encapsulation_on_entities_and_sources(): void
    {
        $source = new InCodeCatalogSource();
        $this->assertInstanceOf(CatalogSourceInterface::class, $source);
        $this->assertInstanceOf(CatalogSourceInterface::class, new DatabaseCatalogSource());

        $store = $source->findStoreBySlug('toko-a');
        $this->assertInstanceOf(StoreEntity::class, $store);
        $this->assertInstanceOf(CatalogEntity::class, $store);
        $this->assertSame('Toko A (Kategori A)', $store->toSummary());
        $this->assertSame('6285704244288', $store->getCleanWhatsappDigits());

        $product = $store->findProductBySlug('barang-contoh-1');
        $this->assertInstanceOf(ProductEntity::class, $product);
        $this->assertInstanceOf(CatalogEntity::class, $product);
        $this->assertSame('Barang Contoh 1 - Rp 50.000', $product->toSummary());
        $this->assertSame('Rp 50.000', $product->getFormattedPrice());

        // Test UserEntity untuk penanganan User/Member (Soal UTS)
        $user = new UserEntity(1, 'member-1', 'User Member', 'user@example.com', 'Member');
        $this->assertInstanceOf(CatalogEntity::class, $user);
        $this->assertTrue($user->isMember());
        $this->assertSame('User Member [Member] (user@example.com)', $user->toSummary());
    }
}
