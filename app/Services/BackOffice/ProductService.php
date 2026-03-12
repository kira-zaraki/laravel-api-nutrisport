<?php

namespace App\Services\BackOffice;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        DB::beginTransaction();

        $product = Product::create([
            'name' => $data['name'],
            'stock' => $data['stock'],
        ]);
        foreach ($data['prices'] as $siteId => $price) {
            ProductPrice::create([
                'product_id' => $product->id,
                'site_id' => $siteId,
                'price' => $price,
            ]);
        }

        DB::commit();

        return $product;
    }
}