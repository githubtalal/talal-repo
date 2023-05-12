<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Illuminate\Support\Facades\DB;

class SalesByCategories extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        $categories = DB::table('categories')
            ->select(
                'categories.name as category_name',
                DB::raw('count(order_items.id) as orders_count')
            )
            ->whereNull('categories.deleted_at')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->whereNull('products.deleted_at')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereNull('order_items.deleted_at')
            ->where('categories.store_id', auth()->user()->store->id)
            ->whereBetween('order_items.created_at', [$this->filters['date']['start'], $this->filters['date']['end']])
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNull('orders.deleted_at');

        if ($this->filters['status'] != 'all') {
            $categories->where('status', $this->filters['status']);
        }

        $categories = $categories->groupBy('categories.id')
            ->orderBy('orders_count', 'DESC')
            ->take(6)->get();

        // dd($categories);
        $labels = $categories->pluck('category_name')->toArray();
        $values = $categories->pluck('orders_count')->toArray();

        // calculate percentage
        $total = array_sum($values);

        foreach ($values as $key => $value) {
            $values[$key] = number_format(($value / $total) * 100);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
