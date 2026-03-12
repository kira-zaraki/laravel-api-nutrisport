<?php

namespace App\Services\BackOffice;

use App\Models\Order;

class OrderService
{
    public function recentOrders(int $days = 5, int $perPage = 10)
    {
        return Order::with('user')
            ->lastDays($days)
            ->latest()
            ->paginate($perPage)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'client_name' => $order->user->name,
                    'total' => $order->total,
                    'status' => $order->status,
                    'rest_to_pay' => max(0, $order->total - ($order->paid ?? 0)),
                ];
            });
    }
}