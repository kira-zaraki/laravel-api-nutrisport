<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class CartService
{
    private function key($site,$cartId)
    {
        return "cart:{$site}:{$cartId}";
    }

    public function getCart($site,$cartId)
    {   
        return Cache::get($this->key($site,$cartId),[
            'items' => []
        ]);
    }

    public function add($site,$cartId,$productId,$qty)
    {
        if ($qty <= 0)
            throw new \InvalidArgumentException('La quantité doit être supérieure à 0');

        $product = Product::with(['prices' => fn ($q) => $q->where('site_id', $site)])
            ->findOrFail($productId);

        if ($product->stock < $qty)
            throw new \Exception('Stock insuffisant');

        $price = $product->prices->first()?->price;

        if (!$price)
            throw new \Exception('Prix indisponible pour ce site');

        $cart = $this->getCart($site,$cartId);

        $found = false;

        foreach ($cart['items'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $qty;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart['items'][] = [
                'product_id' => $productId,
                'quantity' => $qty
            ];
        }

        Cache::put(
            $this->key($site,$cartId),
            $cart,
            now()->addDays(3)
        );

        return $cart;

    }

    public function remove($site,$cartId,$productId)
    {

        $cart = $this->getCart($site,$cartId);

        $cart['items'] = array_values(
            array_filter(
                $cart['items'],
                fn($item) => $item['product_id'] != $productId
            )
        );

        Cache::put(
            $this->key($site,$cartId),
            $cart,
            now()->addDays(3)
        );

        return $cart;

    }

    public function clear($site,$cartId)
    {
        Cache::forget($this->key($site,$cartId));
    }
}