# Products API — Discounts & Filters (Laravel 12)

A small, fast, and testable REST API that lists products with **configurable discounts** and **pre-discount filters**, designed to stay performant even with **20k+** items.

- **Endpoint:** `GET /api/products`
- **Filters:** `category`, `priceLessThan` (in **cents**, applied **before** discounts)
- **Max items per response:** `5`
- **Currency:** `EUR`
- **Data source:** JSON file (path configurable via `.env`)

---

## Prerequisites

- Docker + Docker Compose

---

## How to run (one command)

```bash
docker compose up -d --build
```

First boot note

On a fresh clone, the PHP container will run composer install inside the container.
This can take a few minutes the first time.

Watch progress:

```bash
docker compose logs -f php
```

When you see php-fpm running without errors, the app is ready.
Open: http://localhost:8000

Endpoint
GET /api/products

Query parameters

category — (optional) exact category match (e.g., boots)

priceLessThan — (optional, integer cents) include only products whose original price ≤ this value

Rules

boots → 30% discount

sku = 000003 → 15% discount

If multiple discounts apply, the largest wins

Response returns at most 5 items (order doesn’t matter)

Examples

# all products (max 5)
curl "http://localhost:8000/api/products"

# filter by category
curl "http://localhost:8000/api/products?category=boots"

# filter by price (cents, pre-discount)
curl "http://localhost:8000/api/products?priceLessThan=80000"

# combined
curl "http://localhost:8000/api/products?category=boots&priceLessThan=80000"

Tests (PHPUnit)

All tests run without using the filesystem or network (in-memory fakes).
# full suite
docker compose exec php php artisan test

# only unit or feature
docker compose exec php php artisan test --testsuite=Unit
docker compose exec php php artisan test --testsuite=Feature

# filter by class or method
docker compose exec php php artisan test --filter=DiscountEngineTest
docker compose exec php php artisan test --filter="GetProductsEndpointTest::test_returns_max_five"

Generate large datasets (optional)

Use the built-in Artisan command to generate any number of products into a JSON file:
# generate 20k products into storage/app/products.json
docker compose exec php php artisan products:generate 20000 --path=storage/app/products.json