<?php

namespace Src\Entities;

/**
 * Parent class abstrak untuk entitas katalog (Inheritance, Encapsulation, Polymorphism).
 */
abstract class CatalogEntity
{
    public function __construct(
        protected int $id,
        protected string $slug,
        protected string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function toSummary(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
