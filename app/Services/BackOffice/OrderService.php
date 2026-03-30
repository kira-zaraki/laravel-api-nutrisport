<?php

namespace App\Services\BackOffice;

use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderService
{
    public function recentOrders(int $days = 5, int $perPage = 10)
    {
        return OrderResource::collection(Order::with('user')
            ->lastDays($days)
            ->latest()
            ->paginate($perPage));
    }
}