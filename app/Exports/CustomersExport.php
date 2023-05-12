<?php



namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class CustomersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'phone_number'
        ];
    }

    public function collection()
    {
        $customers = DB::table('customers')
            ->select(
                DB::raw('CONCAT(customers.first_name," ",customers.last_name) as name'),
                'customers.phone_number'
            )
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at')
            ->groupBy('customers.id')
            ->get();

     
   return $customers;
    }
}
