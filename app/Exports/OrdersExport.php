<?php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class OrdersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'name',
            'date',
            'phone_number',
            'status',
            'price'
        ];
    }

    public function collection()
    {
        $orders = DB::table('orders')
        ->select(
            'orders.id',
            DB::raw('CONCAT(first_name, " ", last_name)'),
            'orders.created_at',
            'phone_number',
            'status',
            'orders.total'
        )
        ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.store_id', auth()->user()->store->id)
        ->whereNull('order_items.deleted_at')
        ->get();

        return $orders;
    }
}
