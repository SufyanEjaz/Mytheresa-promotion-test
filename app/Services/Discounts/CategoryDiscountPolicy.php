<?php

namespace App\Services\Discounts;

use App\Contracts\DiscountPolicy;
use App\DTO\Product;

final class CategoryDiscountPolicy implements DiscountPolicy
{
    public function __construct(private readonly array $categoryDiscounts) {}

    public function getDiscountPercent(Product $product): int
    {
        return (int)($this->categoryDiscounts[$product->category] ?? 0);
    }
}
