<?php

namespace Src\Entities;

/**
 * Entitas Barang dengan enkapsulasi properti dan override polimorfik toSummary().
 */
class ProductEntity extends CatalogEntity
{
    /**
     * @param array<string, string> $specs
     */
    public function __construct(
        int $id,
        private int $storeId,
        string $slug,
        string $name,
        private int $price,
        private ?string $thumbnailUrl,
        private ?string $imageUrl,
        private string $description,
        private array $specs = [],
    ) {
        parent::__construct($id, $slug, $name);
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string, string>
     */
    public function getSpecs(): array
    {
        return $this->specs;
    }

    public function toSummary(): string
    {
        return "{$this->name} - {$this->getFormattedPrice()}";
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->storeId,
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => $this->price,
            'thumbnail_url' => $this->thumbnailUrl,
            'image_url' => $this->imageUrl,
            'description' => $this->description,
            'specs' => $this->specs,
        ];
    }
}
