<?php

namespace App\Services\BackOffice;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ReportService
{

    public function generateDailyReport(): array
    {
        return Cache::remember('daily_report_' . now()->toDateString(), 3600, function () {
            return [
                'mostSold'      => $this->getProductStats('SUM(order_items.quantity)', 'desc'),
                'leastSold'     => $this->getProductStats('SUM(order_items.quantity)', 'asc'),
                'maxRevenue'    => $this->getProductStats('SUM(order_items.quantity * order_items.price)', 'desc', 'revenue'),
                'minRevenue'    => $this->getProductStats('SUM(order_items.quantity * order_items.price)', 'asc', 'revenue'),
                'revenueBySite' => $this->getRevenueBySite(),
            ];
        });
    }

    private function getProductStats(string $rawSum, string $direction, string $alias = 'total_qty'): ?object
    {
        return DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw("$rawSum as $alias"))
            ->groupBy('products.name')
            ->orderBy($alias, $direction)
            ->first();
    }

    private function getRevenueBySite()
    {
        return DB::table('orders')
            ->join('sites', 'orders.site_id', '=', 'sites.id')
            ->select('sites.name as site', DB::raw('SUM(orders.total) as revenue'))
            ->whereDate('orders.created_at', now()->subDay())
            ->groupBy('sites.name')
            ->get();
    }
}