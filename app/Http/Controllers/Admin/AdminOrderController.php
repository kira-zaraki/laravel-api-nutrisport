<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BackOffice\OrderService;

class AdminOrderController extends Controller
{
    public function recent(Request $request, OrderService $orderService)
    {
        $perPage = $request->get('per_page', 10);
        return $orderService->recentOrders(5, $perPage);
    }
}
