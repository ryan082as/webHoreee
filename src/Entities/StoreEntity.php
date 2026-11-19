<?php

namespace Src\Entities;

/**
 * Entitas Toko dengan enkapsulasi properti dan override polimorfik toSummary().
 */
class StoreEntity extends CatalogEntity
{
    /**
     * @param array<int, ProductEntity> $products
     */
    public function __construct(
        int $id,
        string $slug,
        string $name,
        private string $category,
        private string $description,
        private string $address,
        private string $whatsappNumber,
        private string $operationalHours,
        private array $products = [],
    ) {
        parent::__construct($id, $slug, $name);
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getWhatsappNumber(): string
    {
        return $this->whatsappNumber;
    }

    public function getCleanWhatsappDigits(): string
    {
        return (string) preg_replace('/\D+/', '', $this->whatsappNumber);
    }

    public function getOperationalHours(): string
    {
        return $this->operationalHours;
    }

    /**
     * @return array<int, ProductEntity>
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function findProductBySlug(string $productSlug): ?ProductEntity
    {
        foreach ($this->products as $product) {
            if ($product->getSlug() === $productSlug) {
                return $product;
            }
        }

        return null;
    }

    public function toSummary(): string
    {
        return "{$this->name} ({$this->category})";
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'address' => $this->address,
            'whatsapp_number' => $this->whatsappNumber,
            'operational_hours' => $this->operationalHours,
            'products' => array_map(fn (ProductEntity $product) => $product->toArray(), $this->products),
        ];
    }
}
