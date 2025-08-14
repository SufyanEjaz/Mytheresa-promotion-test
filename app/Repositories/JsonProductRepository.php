<?php

namespace App\Repositories;

use App\Contracts\ProductRepository;
use App\DTO\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use RuntimeException;

final class JsonProductRepository implements ProductRepository
{
    public function __construct(private readonly string $path) {}

    public function all(): array
    {
        // Cache in-memory for speed; invalidate when file changes (mtime)
        $key = 'products.json:' . (string) @filemtime($this->path);

        return Cache::rememberForever($key, function () {
            if (!File::exists($this->path)) {
                throw new RuntimeException("Products JSON not found at {$this->path}");
            }

            try {
                $raw = json_decode(File::get($this->path), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                throw new RuntimeException("Invalid products JSON: " . $e->getMessage(), 0, $e);
            }

            $list = $raw['products'] ?? [];

            $out = [];
            foreach ($list as $row) {
                $out[] = Product::fromArray($row);
            }

            return $out;
        });
    }
}
