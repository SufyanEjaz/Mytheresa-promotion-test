<?php

namespace App\Services\Discounts;

use App\Contracts\DiscountPolicy;
use App\DTO\Product;

final class DiscountEngine
{
    /** @var DiscountPolicy[] */
    private array $policies;

    public function __construct(DiscountPolicy ...$policies)
    {
        $this->policies = $policies;
    }

    public function bestPercentFor(Product $product): int
    {
        $max = 0;
        foreach ($this->policies as $policy) {
            $p = $policy->getDiscountPercent($product);
            if ($p > $max) $max = $p;
            if ($max === 100) break;
        }
        return $max;
    }

    public function apply(Product $product): array
    {
        $original = $product->price;
        $percent  = $this->bestPercentFor($product);

        if ($percent <= 0) {
            return [
                'sku'      => $product->sku,
                'name'     => $product->name,
                'category' => $product->category,
                'price'    => [
                    'original' => $original,
                    'final'    => $original,
                    'discount_percentage' => null,
                    'currency' => 'EUR',
                ],
            ];
        }

        // integer math with correct rounding
        $final = (int) round($original * (100 - $percent) / 100);

        return [
            'sku'      => $product->sku,
            'name'     => $product->name,
            'category' => $product->category,
            'price'    => [
                'original' => $original,
                'final'    => $final,
                'discount_percentage' => $percent . '%',
                'currency' => 'EUR',
            ],
        ];
    }
}
