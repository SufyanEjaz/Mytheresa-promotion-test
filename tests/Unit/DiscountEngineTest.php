<?php

namespace Tests\Unit;

use App\DTO\Product;
use App\Services\Discounts\CategoryDiscountPolicy;
use App\Services\Discounts\DiscountEngine;
use App\Services\Discounts\SkuDiscountPolicy;
use PHPUnit\Framework\TestCase;

class DiscountEngineTest extends TestCase
{
    public function test_applies_max_discount(): void
    {
        $engine = new DiscountEngine(
            new CategoryDiscountPolicy(['boots' => 30]),
            new SkuDiscountPolicy(['000003' => 15])
        );

        $p1 = new Product('000001', 'Item', 'boots', 10000);
        $p2 = new Product('000003', 'Item', 'boots', 10000);
        $p3 = new Product('X', 'Item', 'sandals', 10000);

        $this->assertSame(30, $engine->bestPercentFor($p1));
        $this->assertSame(30, $engine->bestPercentFor($p2)); // boots(30) beats sku(15)
        $this->assertSame(0,  $engine->bestPercentFor($p3));
    }

    public function test_apply_structure_and_rounding(): void
    {
        $engine = new DiscountEngine(new CategoryDiscountPolicy(['boots' => 30]));

        $p = new Product('000001', 'Item', 'boots', 89000);
        $out = $engine->apply($p);

        $this->assertSame(62300, $out['price']['final']); // 30% off
        $this->assertSame('30%', $out['price']['discount_percentage']);
        $this->assertSame('EUR', $out['price']['currency']);
    }
}
