<?php

namespace App\Factories;

use App\Models\Product;

class XmlFeedGenerator implements FeedGenerator
{
    public function generate()
    {

        $products = Product::select(
            'id',
            'name',
            'stock'
        )->get();

        $xml = new \SimpleXMLElement('<products/>');

        foreach ($products as $product) {

            $item = $xml->addChild('product');

            $item->addChild('id', $product->id);
            $item->addChild('name', $product->name);
            $item->addChild('stock', $product->in_stock ? 'available' : 'out_of_stock');

        }

        return $xml->asXML();

    }
}