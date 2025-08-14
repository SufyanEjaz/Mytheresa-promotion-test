<?php

namespace App\Services;

use App\Contracts\ProductRepository;
use App\DTO\Product;
use App\Services\Discounts\DiscountEngine;

final class ProductService
{
    public function __construct(
        private readonly ProductRepository $repo,
        private readonly DiscountEngine $engine,
        private readonly int $maxItems
    ) {}

    /**
     * Filter BEFORE discounts, limit to maxItems.
     *
     * @param string|null $category
     * @param int|null $priceLessThan  price in cents (<= filter), before discounts
     * @return array<int, array<string, mixed>>
     */
    public function search(?string $category, ?int $priceLessThan): array
    {
        $out = [];
        $count = 0;

        /** @var Product $p */
        foreach ($this->repo->all() as $p) {
            if ($category !== null && $p->category !== $category) {
                continue;
            }
            if ($priceLessThan !== null && $p->price > $priceLessThan) {
                continue;
            }

            $out[] = $this->engine->apply($p);
            $count++;

            if ($count >= $this->maxItems) {
                break; // early exit for performance
            }
        }

        return $out;
    }
}
