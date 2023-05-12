<?php

namespace App\Http\Controllers;

use App\Dashboard\OrdersTotal;
use App\Dashboard\SalesByCategories;
use App\Dashboard\Statistics;
use App\Dashboard\TopCustomers;
use App\Dashboard\TopPaymentMethods;
use App\Dashboard\TopProducts;
use App\Invoice;
use App\Models\Order;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function tos()
    {
        return view('tos');
    }
    public function whoWeAre()
    {
        return view('who-we-are');
    }

    public function dashboard()
    {
        $filters = [];

        if (request('date')) {
            $date = explode(" - ", request('date'));
            $start_date = Carbon::parse($date[0])->toDateString();
            $end_date = Carbon::parse($date[1])->toDateString();
        } else {
            $date = get_default_filter_date();
            $start_date = $date['start'];
            $end_date = $date['end'];
        }

        $start_date = $start_date . ' 00:00:00';
        $end_date = $end_date . ' 23:59:59';

        $filters = [
            'date' => [
                'start' => $start_date,
                'end' => $end_date,
            ],
            'status' => request('status') ?? Order::COMPLETED,
        ];

        $ordersTotal = (new OrdersTotal($filters))->execute();
        $salesByCategories = (new SalesByCategories($filters))->execute();
        $topProducts = (new TopProducts($filters))->execute();
        $topCustomers = (new TopCustomers($filters))->execute();
        $topPaymentMethods = (new TopPaymentMethods($filters))->execute();
        $statistics = (new Statistics($filters))->execute();

        return view('newDashboard.dashboard', compact(
            'ordersTotal',
            'salesByCategories',
            'topProducts',
            'topCustomers',
            'topPaymentMethods',
            'statistics'
        ));
    }

    public function invoice()
    {
        $order = Order::where('id', 125)->first();
        $data = [
            'invoiceId' => $order->id,
            'date' => now()->toDateString(),
            'store_name' => $order->store->name,
            'store_type' => $order->store->type,
            'customer_name' => $order->first_name . ' ' . $order->last_name,
            'customer_phone_number' => $order->phone_number,
            'products' => $order->items()->get(),
            'subtotal' => $order->total,
        ];

        return (new Invoice($order))->prepareInvoice();
        // return view('newDashboard.invoice', compact('data'));
    }
}
