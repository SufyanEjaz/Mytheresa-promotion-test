<?php

namespace App\DTO;

final class Product
{
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly string $category,
        public readonly int $price // cents
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sku: (string)$data['sku'],
            name: (string)$data['name'],
            category: (string)$data['category'],
            price: (int)$data['price'],
        );
    }

    public function toArray(): array
    {
        return [
            'sku'      => $this->sku,
            'name'     => $this->name,
            'category' => $this->category,
            'price'    => $this->price,
        ];
    }
}
