<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Illuminate\Support\Facades\DB;

class TopCustomers extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        $results = DB::table('customers')
            ->select(
                DB::raw('CONCAT(customers.first_name," ",customers.last_name) as name'),
                DB::raw('sum(total) as orders_total'),
                DB::raw('count(orders.customer_id) as orders_count')
            )
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at')
            ->whereBetween('orders.created_at', [$this->filters['date']['start'], $this->filters['date']['end']]);

        if ($this->filters['status'] != 'all') {
            $results->where('status', $this->filters['status']);
        }

        $results =
        $results->groupBy('customers.id')
            ->orderBy('orders_count', 'DESC')
            ->take(5)->get()->toArray();

        return $results;
    }
}
