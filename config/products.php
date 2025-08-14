<?php

return [
    'data_path' => storage_path('app/products.json'),

    // Business discount rules
    'category_discounts' => [
        'boots' => 30, // percent
    ],
    'sku_discounts' => [
        '000003' => 15, // percent
    ],

    // Response max size
    'max_items' => 5,
];
