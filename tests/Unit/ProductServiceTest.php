<?php

namespace Tests\Unit;

use App\Contracts\ProductRepository;
use App\DTO\Product;
use App\Services\Discounts\DiscountEngine;
use App\Services\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private function fakeRepo(array $items): ProductRepository
    {
        return new class($items) implements ProductRepository {
            public function __construct(private array $items) {}
            public function all(): array { return $this->items; }
        };
    }

    public function test_filters_and_limits(): void
    {
        $repo = $this->fakeRepo([
            new Product('1', 'A', 'boots', 100),
            new Product('2', 'B', 'boots', 200),
            new Product('3', 'C', 'sandals', 50),
            new Product('4', 'D', 'boots', 300),
            new Product('5', 'E', 'boots', 400),
            new Product('6', 'F', 'boots', 500),
        ]);

        // Use the real engine with no policies (no discounts applied)
        $engine = new DiscountEngine();

        $service = new ProductService($repo, $engine, 5);

        $out = $service->search('boots', 400);
        $this->assertCount(4, $out); // 1,2,4,5 match (<= 400)
    }
}
