<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Illuminate\Support\Facades\DB;

class TopPaymentMethods extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        $methods = DB::table('orders')
            ->select(
                'payment_method as name',
                DB::raw('count(payment_method) as orders_count')
            )
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at')
            ->whereBetween('orders.created_at', [$this->filters['date']['start'], $this->filters['date']['end']]);

        if ($this->filters['status'] != 'all') {
            $methods->where('status', $this->filters['status']);
        }

        $methods =
        $methods
            ->groupBy('payment_method')
            ->orderBy('orders_count', 'DESC')
            ->take(5)->get();

        $labels = $methods->pluck('name')->toArray();
        $values = $methods->pluck('orders_count')->toArray();

        // format data
        foreach ($labels as $key => $value) {
            $labels[$key] = __('payment_methods.payment_methods.' . Config('payment_methods.' . $value));
        }

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
