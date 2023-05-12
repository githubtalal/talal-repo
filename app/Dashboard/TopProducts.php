<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Illuminate\Support\Facades\DB;

class TopProducts extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        $results = DB::table('products')
            ->select(
                'products.name as name',
                'products.price',
                'products.image_url',
                'status',
                DB::raw('count(order_items.order_id) as orders_count')
            )
            ->whereNull('products.deleted_at')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.store_id', auth()->user()->store->id)
            ->whereNull('order_items.deleted_at')
            ->whereBetween('order_items.created_at', [$this->filters['date']['start'], $this->filters['date']['end']])
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNull('orders.deleted_at');

        if ($this->filters['status'] != 'all') {
            $results->where('status', $this->filters['status']);
        }

        $results = $results
            ->groupBy('products.id')
            ->orderBy('orders_count', 'DESC')
            ->take(5)->get()->toArray();

        return $results;
    }
}
