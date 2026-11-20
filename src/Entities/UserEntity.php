<?php

declare(strict_types=1);

namespace Src\Entities;

/**
 * Entitas Pengguna (User/Member) dengan enkapsulasi properti dan polymorphism toSummary().
 * Memenuhi kriteria UTS untuk penanganan Pengguna / Member.
 */
class UserEntity extends CatalogEntity
{
    public function __construct(
        int $id,
        string $slug,
        string $name,
        private string $email,
        private string $role = 'Member', // 'Guest', 'Member', 'Admin'
        private string $phoneNumber = '',
    ) {
        parent::__construct($id, $slug, $name);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function isMember(): bool
    {
        return strtolower($this->role) === 'member';
    }

    public function toSummary(): string
    {
        return "{$this->name} [{$this->role}] ({$this->email})";
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone_number' => $this->phoneNumber,
        ];
    }
}
