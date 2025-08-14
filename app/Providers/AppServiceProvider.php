<?php

namespace App\Providers;

use App\Contracts\ProductRepository;
use App\Repositories\JsonProductRepository;
use App\Services\Discounts\CategoryDiscountPolicy;
use App\Services\Discounts\DiscountEngine;
use App\Services\Discounts\SkuDiscountPolicy;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, function ($app) {
            return new JsonProductRepository(config('products.data_path'));
        });

        $this->app->singleton(DiscountEngine::class, function ($app) {
            $cfg = $app['config']['products'];
            return new DiscountEngine(
                new CategoryDiscountPolicy($cfg['category_discounts']),
                new SkuDiscountPolicy($cfg['sku_discounts'])
            );
        });

        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService(
                repo: $app->make(ProductRepository::class),
                engine: $app->make(DiscountEngine::class),
                maxItems: (int) config('products.max_items')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
