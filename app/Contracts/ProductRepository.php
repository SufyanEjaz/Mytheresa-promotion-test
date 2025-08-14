<?php

namespace App\Contracts;

use App\DTO\Product;

interface ProductRepository
{
    /**
     * Return all products as immutable DTOs.
     * Implementations may cache aggressively in-memory.
     *
     * @return Product[]
     */
    public function all(): array;
}
