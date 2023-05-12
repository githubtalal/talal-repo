<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class PaymentMethodsReport extends Report
{
    public function prepareQueryBuilder()
    {
        $this->queryBuilder = DB::table('orders')
            ->select(
                'payment_method as payment_method_name',
                DB::raw('sum(total) as orders_total'),
                DB::raw('count(payment_method) as orders_count')
            )
            ->whereNull('orders.deleted_at')
            ->groupBy('payment_method');

        if (auth()->user()->isStoreAdmin()) {
            $this->queryBuilder->where('orders.store_id', auth()->user()->store->id);
        }

        $this->applyFilters();
        $this->applyOrders();
    }

    public function prepareTable()
    {
        $this->table_name = trans_choice('app.payment_methods', 2);

        $this->addColumn([
            'name' => trans_choice('app.payment_methods', 1),
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
            $record->payment_method_name = __('payment_methods.payment_methods.' . Config('payment_methods.' . $record->payment_method_name));
            $record->orders_total = price_format($record->orders_total);
        }
    }
}
