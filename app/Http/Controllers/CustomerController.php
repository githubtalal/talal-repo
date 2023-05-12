<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Exports\CustomersExport;
use Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::table('customers')
            ->select(
                DB::raw('CONCAT(customers.first_name," ",customers.last_name) as name'),
                'customers.phone_number'
            )
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('orders.deleted_at')
            ->groupBy('customers.id');

        if (request('search'))
            $customers->where(function ($query) {
                $query->where(DB::raw('CONCAT(customers.first_name," ",customers.last_name)'), 'like', '%' . request('search') . '%')
                    ->orWhere('customers.phone_number', 'like', '%' . request('search') . '%');
            });

        $customers = $customers->paginate(10);

        return view('newDashboard.customers', compact('customers'));
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

}
