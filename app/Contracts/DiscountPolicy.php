<?php

namespace App\Contracts;

use App\DTO\Product;

interface DiscountPolicy
{
    /**
     * Return the discount percentage (0..100) applicable to the product.
     * Implementations must be pure functions (no side effects).
     */
    public function getDiscountPercent(Product $product): int;
}
