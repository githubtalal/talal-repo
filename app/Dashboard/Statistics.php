<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Illuminate\Support\Facades\DB;

class Statistics extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        // get customers statistics
        $customers = DB::table('customers')
            ->select('customers.created_at')
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at');

        if ($this->filters['status'] != 'all') {
            $customers->where('status', $this->filters['status']);
        }

        $customers->groupBy('customers.id');

        $total_customers = $customers->get()->count();

        $total_customers_current_month = $customers->whereBetween('customers.created_at', [$this->filters['date']['start'], $this->filters['date']['end']])
            ->get()->count();

        // get orders statistics
        $orders = DB::table('orders')
            ->where('store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at');

        if ($this->filters['status'] != 'all') {
            $orders->where('status', $this->filters['status']);
        }

        // count of orders
        $orders_count = $orders->get()->count();

        // total sales for current month
        $total_orders = $orders->select(DB::raw('sum(total) as total'))
            ->whereBetween('created_at', [$this->filters['date']['start'], $this->filters['date']['end']])
            ->first();

        // get pending orders count
        $pending_orders = DB::table('orders')
            ->where([
                ['store_id', auth()->user()->store->id],
                ['status', 'pending'],
            ])
            ->whereNull('orders.deleted_at')->get()->count();

        // get paid orders count
        $paid_orders = DB::table('orders')
            ->where([
                ['store_id', auth()->user()->store->id],
                ['status', 'paid'],
            ])->whereNull('orders.deleted_at')->get()->count();

        return [
            'customers' => [
                'total' => $total_customers,
                'current_month' => $total_customers_current_month,
            ],
            'orders' => [
                'total' => $total_orders->total,
                'count' => $orders_count,
                'paid_count' => $paid_orders,
                'pending_count' => $pending_orders,
            ],
        ];
    }
}
