<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class CategoriesReport extends Report
{
    public function prepareQueryBuilder()
    {
        $this->queryBuilder = DB::table('categories')
            ->select(
                'categories.name as category_name',
                DB::raw('sum(total) as orders_total'),
                DB::raw('count(order_items.id) as orders_count')
            )
            ->whereNull('categories.deleted_at')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->whereNull('products.deleted_at')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereNull('order_items.deleted_at')
            ->groupBy('categories.id');

        if (auth()->user()->isStoreAdmin()) {
            $this->queryBuilder->where('categories.store_id', auth()->user()->store->id);
        }

        $this->addFilter('categories.id', 'number');
        $this->addFilter('categories.name', 'string');

        $this->applyOrders();
        $this->applyFilters();
    }

    public function prepareTable()
    {
        $this->table_name = trans_choice('app.categories.categories', 2);

        $this->addColumn([
            'name' => __('app.categories.info.Name'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'name' => __('app.reports.orders_values'),
            'type' => 'number',
        ]);

        $this->addColumn([
            'name' => __('app.reports.orders_count'),
            'type' => 'number',
        ]);
    }

    public function formatData()
    {
        foreach ($this->records as $record) {
            $record->orders_total = price_format($record->orders_total);
        }
    }
}
