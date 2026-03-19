<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Events\OrderCreated;
use App\Enums\OrderStatus;

class OrderService
{

    public function create($user,$cart,$shipping)
    {

        DB::beginTransaction();

        $total = 0;
        $items = [];

        if (empty($cart['items']))
            throw new \Exception('Panier vide');

        foreach ($cart['items'] as $item) {

            $product = Product::lockForUpdate()->findOrFail($item['product_id']);

            if ($product->stock < $item['quantity'])
                throw new \Exception("Stock insuffisant pour le produit {$product->name}");

            $price = $product->prices()
                ->where('site_id', $shipping['site'])
                ->first()
                ->price;

            $total += $price * $item['quantity'];

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $price
            ];
            $product->decrement('stock', $item['quantity']);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'site_id' => $shipping['site'],
            'status' => OrderStatus::PENDING,
            'total' => $total,
            'shipping_name' => $shipping['shipping_name'],
            'shipping_address' => $shipping['shipping_address'],
            'shipping_city' => $shipping['shipping_city'],
            'shipping_country' => $shipping['shipping_country'],
        ]);

        foreach ($items as $item) {
            $order->items()->create($item);
        }

        OrderCreated::dispatch($order);

        DB::commit();

        return $order;

    }

}