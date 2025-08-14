<?php

namespace Tests\Feature;

use App\Contracts\ProductRepository;
use App\DTO\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetProductsEndpointTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Bind a fake repo so the endpoint doesn't touch filesystem
        $this->app->bind(ProductRepository::class, function () {
            return new class implements ProductRepository {
                public function all(): array {
                    return [
                        new Product('000001', 'A', 'boots', 89000),
                        new Product('000002', 'A', 'boots', 99000),
                        new Product('000003', 'C', 'boots', 71000),
                        new Product('000004', 'D', 'sandals', 79500),
                        new Product('000005', 'E', 'sneakers', 59000),
                    ];
                }
            };
        });
    }

    public function test_returns_max_five(): void
    {
        $res = $this->getJson('/api/products');
        $res->assertStatus(200)
            ->assertJsonStructure(['products'])
            ->assertJsonCount(5, 'products');
    }

    public function test_filters_by_category_and_price_before_discounts(): void
    {
        $res = $this->getJson('/api/products?category=boots&priceLessThan=80000');
        $res->assertStatus(200)
            ->assertJsonCount(1, 'products'); // only sku 000003 is <= 80000
    }

    public function test_invalid_priceLessThan(): void
    {
        $res = $this->getJson('/api/products?priceLessThan=abc');
        $res->assertStatus(422);
    }
}
