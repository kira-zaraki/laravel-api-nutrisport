<?php

namespace App\Services\BackOffice;

use Illuminate\Support\Facades\DB;

class ReportService
{

    public function generateDailyReport(): array
    {

        // Produit le plus vendu
        $mostSold = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->first();


        // Produit le moins vendu
        $leastSold = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.name')
            ->orderBy('total_qty')
            ->first();


        // Produit CA max
        $maxRevenue = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->first();


        // Produit CA min
        $minRevenue = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.name')
            ->orderBy('revenue')
            ->first();


        // CA par site
        $revenueBySite = DB::table('orders')
        ->join('sites', 'orders.site_id', '=', 'sites.id')
        ->select(
            'sites.name as site',
            DB::raw('SUM(orders.total) as revenue')
        )
        ->whereDate('orders.created_at', now()->subDay())
        ->groupBy('sites.name')
        ->get();


        return [
            'mostSold' => $mostSold,
            'leastSold' => $leastSold,
            'maxRevenue' => $maxRevenue,
            'minRevenue' => $minRevenue,
            'revenueBySite' => $revenueBySite,
        ];
    }
}