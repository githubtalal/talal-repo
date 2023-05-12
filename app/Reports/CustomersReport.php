<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class CustomersReport extends Report
{
    public function prepareQueryBuilder()
    {
        $this->queryBuilder = DB::table('customers')
            ->select(
                DB::raw('CONCAT(customers.first_name," ",customers.last_name) as name'),
                'customers.phone_number',
                DB::raw('sum(total) as orders_total'),
                DB::raw('count(orders.customer_id) as orders_count')
            )
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->whereNull('orders.deleted_at')
            ->groupBy('customers.id');

        if (auth()->user()->isStoreAdmin()) {
            $this->queryBuilder->where('orders.store_id', auth()->user()->store->id);
        }

        //->having('orders_total', '<', 2000)

        //$this->addFilter('total', 'number');
        $this->addFilter('orders.created_at', 'date');

        $this->applyOrders();
        $this->applyFilters();
    }

    public function prepareTable()
    {
        $this->table_name = __('app.customers');

        $this->addColumn([
            'name' => __('app.order.customer_info.Name'),
            'type' => 'string',
        ]);

        $this->addColumn([
            'name' => __('app.order.customer_info.Phone_number'),
            'type' => 'number',
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
