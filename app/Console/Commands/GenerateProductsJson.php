<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateProductsJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate {count=20000} {--path= : Output JSON path (default: config(products.data_path))}';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a products.json file with random products for performance testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $path  = $this->option('path') ?: config('products.data_path');

        if (!$path) {
            $this->error('config("products.data_path") is not set. Add config/products.php first.');
            return self::FAILURE;
        }

        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                $this->error("Unable to create directory: $dir");
                return self::FAILURE;
            }
        }

        $categories = ['boots', 'sandals', 'sneakers', 'bags', 'accessories'];
        $products   = [];
        $products[] = [ // keep the 5 originals at top (optional)
            'sku' => '000001','name' => 'BV Lean leather ankle boots','category' => 'boots','price' => 89000,
        ];
        $products[] = [
            'sku' => '000002','name' => 'BV Lean leather ankle boots','category' => 'boots','price' => 99000,
        ];
        $products[] = [
            'sku' => '000003','name' => 'Ashlington leather ankle boots','category' => 'boots','price' => 71000,
        ];
        $products[] = [
            'sku' => '000004','name' => 'Naima embellished suede sandals','category' => 'sandals','price' => 79500,
        ];
        $products[] = [
            'sku' => '000005','name' => 'Nathane leather sneakers','category' => 'sneakers','price' => 59000,
        ];

        // start from 6 so SKUs don’t clash
        for ($i = 6; $i <= $count; $i++) {
            $category = $categories[array_rand($categories)];
            $sku      = str_pad((string)$i, 6, '0', STR_PAD_LEFT);
            // realistic-ish prices in cents: 30€ .. 2000€
            $price    = random_int(3000, 200000);

            $products[] = [
                'sku'      => $sku,
                'name'     => "PerfTest {$category} #{$i}",
                'category' => $category,
                'price'    => $price,
            ];
        }

        $json = json_encode(['products' => $products], JSON_PRETTY_PRINT);
        if ($json === false) {
            $this->error('JSON encode failed.');
            return self::FAILURE;
        }

        file_put_contents($path, $json);
        $this->info("Wrote ".number_format(count($products))." products to: $path");

        return self::SUCCESS;
    }
}
