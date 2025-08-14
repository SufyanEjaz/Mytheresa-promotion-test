<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

final class GetProductsController extends BaseController
{
    public function __invoke(Request $request, ProductService $service)
    {
        $category = $request->query('category');
        $priceRaw = $request->query('priceLessThan');

        $priceLessThan = null;
        if ($priceRaw !== null && $priceRaw !== '') {
            if (!ctype_digit((string)$priceRaw)) {
                return response()->json(['message' => 'priceLessThan must be an integer (cents)'], 422);
            }
            $priceLessThan = (int)$priceRaw;
        }

        $result = $service->search(
            $category ? (string)$category : null,
            $priceLessThan
        );

        return response()->json(['products' => $result]);
    }
}
