<?php

namespace App\Services\Discounts;

use App\Contracts\DiscountPolicy;
use App\DTO\Product;

final class SkuDiscountPolicy implements DiscountPolicy
{
    public function __construct(private readonly array $skuDiscounts) {}

    public function getDiscountPercent(Product $product): int
    {
        return (int)($this->skuDiscounts[$product->sku] ?? 0);
    }
}
