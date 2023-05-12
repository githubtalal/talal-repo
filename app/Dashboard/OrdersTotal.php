<?php

namespace App\Dashboard;

use App\Dashboard\Dashboard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrdersTotal extends Dashboard
{
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute(): array
    {
        $orders = DB::table('orders')
            ->select(DB::raw('Date(created_at) as day'), DB::raw('count(orders.id) as count'))
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at')
            ->whereBetween('created_at', [$this->filters['date']['start'], $this->filters['date']['end']])
            ->groupBy('day');

        if ($this->filters['status'] != 'all') {
            $orders->where('status', $this->filters['status']);
        }

        $orders = $orders->get()->toArray();

        // process dates for labels
        $start = Carbon::parse($this->filters['date']['start']);
        $end = Carbon::parse($this->filters['date']['end']);

        $count = $end->diffInDays($start);

        $data[$start->toDateString()] = 0;

        while ($count != 0) {
            $data[$start->addDay(1)->toDateString()] = 0;
            $count--;
        }

        // insert values: (count of orders)
        foreach ($orders as $order) {
            $data[$order->day] = $order->count;
        }

        return [
            'labels' => array_keys($data),
            'values' => array_values($data),
            'max' => max(array_values($data)),
        ];
    }
}
