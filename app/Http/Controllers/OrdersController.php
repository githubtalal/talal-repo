<?php

namespace App\Http\Controllers;

use App\CancelOrder;
use App\ConfirmOrder;
use App\Events\OrderCanceled;
use App\Events\OrderConfirmed;
use App\Http\Resources\Product as ProductResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreSettings;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Exports\OrdersExport;
use Excel;

class OrdersController extends Controller
{
    public function index()
    {
        //dd(request()->all());
        $orders = Order::query();

        // for super admin we need stores orders without subscriptions
        if (auth()->user()->isSuperAdmin()) {
            $routeName = Route::currentRouteName();
            if ($routeName == 'orders.index') {
                $orders->where('store_id', '!=', auth()->user()->id);
            } else {
                // get subscriptions
                $orders->where('store_id', auth()->user()->id);
            }
        }

        if (request('status', 'all') != 'all') {
            $orders->where('status', request('status'));
        }

        if (request('search')) {
            $orders->where(function ($query) {
                $query->where(DB::raw('CONCAT(first_name," ",last_name)'), 'like', '%' . request('search') . '%')
                    ->orWhere('phone_number', 'like', '%' . request('search') . '%')
                    ->orWhere('id', request('search'));
            });
        }

        if (request('governorate')) {
            $orders->where('governorate', request('governorate'));
        }

        if (request('date')) {
            $date = explode(" - ", request('date'));
            $start = Carbon::parse($date[0])->toDateString();
            $end = Carbon::parse($date[1])->toDateString();
            $start = $start . ' 00:00:00';
            $end = $end . ' 23:59:59';
            $orders->whereBetween('created_at', [$start, $end]);
        }

        if (request()->filled('total')) {
            $orders->where('total', request('op'), request('total'));
        }

        $orders = $orders->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // get governorates
        $cities = Order::groupby('governorate')->pluck('governorate')->toArray();

        // get status
        $statuses = Order::groupby('status')->pluck('status')->toArray();

        return view('newDashboard.orders', compact('orders', 'cities', 'statuses'));
    }

    public function view(Order $order)
    {
        $payment_methods = [];
        $productsItems = [];
        $reservationsItems = [];
        $methods = get_enabled_payment_methods();

        foreach ($methods as $method) {
            if ($method->getKey() == 'cod') {
                $payment_methods['cod'] = $method->getLabel();
            } else {
                $payment_methods[$method->getKey()] = __('payment_methods.payment_methods.' . $method->getKey());
            }

        }

        $notes = $order->notes()->with('user.store')->orderBy('created_at', 'DESC')->get();

        foreach ($order->items as $item) {
            if ($item->product()->withTrashed()->first()->type == 'product') {
                $productsItems[] = $item;
            } else {
                $reservationsItems[] = $item;
            }

        }

        return view('newDashboard.orders.view', compact('order', 'payment_methods', 'notes', 'productsItems', 'reservationsItems'));
    }

    public function update(Request $request, Order $order)
    {

        $order->update([
            "status" => $request->status
        ]);
        /*
        if ($order->isPaid()) {
            OrderConfirmed::dispatch($order);
        }

        if ($order->isCanceled())
            OrderCanceled::dispatch($order);*/

        return redirect()->back()->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.order.orders', 1)]));
    }

    public function get_reservations()
    {
        $events = [];
        $orders = DB::table('orders')->whereNull('orders.deleted_at');

        if (request('status', 'all') != 'all') {
            $orders->where('status', request('status'));
        }

         $reservations = $orders
            ->select(
                'products.name',
                'order_items.additional',
                'order_items.order_id',
                'orders.status'
            )
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.store_id', auth()->user()->store->id)
            ->whereNull('order_items.deleted_at')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.type', 'reservation')
            ->whereNull('products.deleted_at')
            ->get();

        foreach ($reservations as $reservation) {

            $additional = json_decode($reservation->additional, true);
            $status = $reservation->status;
            $color;
            switch ($status)
            {
                case Order::PENDING :
                    $color = '#8A2BE2';
                    break;
                case Order::PAID :
                    $color = '#90EE90';
                    break;
                case Order::COMPLETED :
                    $color = '#006400';
                    break;
                case Order::INPROGRESS :
                    $color = '#FF8C00';
                    break;
                case Order::FAILED :
                    $color = '#8B0000';
                    break;
                default:
                    $color = '#FF0000';
                    break;
            }

            $item = [
                'title' => $reservation->name,
                'start' => Carbon::parse($additional['checkin'])->timezone('Asia/Damascus')->toDateTimeString(),
                'url' => route('orders.view', [$reservation->order_id]),
                'color' => $color
            ];

            $events[] = $item;
        }

        return view('newDashboard.reservations-calendar', compact('events'));
    }


    public function create_reservation()
    {
        // get products
        $products = Product::where('type', 'reservation')->get();

        $steps = getStoreSteps();

        $notesLabel = get_notes_label();

        // get payment methods info
        $payment_method_message = get_payment_method_message();
        $methods = get_enabled_payment_methods();

        foreach ($methods as $key => $value) {
            if ($value->needsRedirect()) {
                unset($methods[$key]);
            }
        }

        $ifHourEnabled = ifHourEnabled();

        // get additional questions
        $bot_settings = StoreSettings::where([
            ['store_id', auth()->user()->store->id],
            ['key', 'bot_settings'],
        ])->first();

        $q1Value = '';
        $q2Value = '';

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1']) {
                $q1Value = $bot_settings['question1'];
            }

            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2']) {
                $q2Value = $bot_settings['question2'];
            }
        }

        return view('newDashboard.reservations.add-reservation', compact(
            'products',
            'steps',
            'notesLabel',
            'q1Value',
            'q2Value',
            'payment_method_message',
            'methods',
            'ifHourEnabled'
        ));
    }

    public function store_reservation(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'payment_method' => 'required',
            'products' => 'required',
        ]);

        // get first and last name
        $name = explode(' ', $request->name);
        $first_name = $name[0];
        array_shift($name);
        $last_name = implode(' ', $name);

        $customer = Customer::query()->where([
            'phone_number' => $request->phone,
        ])->first();

        if (!$customer) {
            $customer = Customer::query()->create([
                'uuid' => '',
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_number' => $request->phone,
            ]);
        } else {
            $customer->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_number' => $request->phone,
            ]);
        }

        $order = Order::query()->create([
            'store_id' => auth()->user()->store->id,
            'customer_id' => $customer->id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'governorate' => $request->governorate ?? '',
            'address' => $request->address ?? '',
            'phone_number' => $request->phone,
            'total' => '',
            'payment_method' => $request->payment_method,
            'payment_ref' => $request->payment_ref ?? null,
            'payment_info' => '',
            'platform' => 'Website',
            'notes' => $request->notes,
            'additional_question1' => $request->question1,
            'additional_question2' => $request->question2,
            'currency' => 'SYP',
        ]);

        $order_total = 0;
        $order_fees = 0;
        $order_tax = 0;
        $ifHourEnabled = false;
        $hourFormat = '';

        foreach ($request->products as $item) {
            $item_total = 0;
            $item_fees = 0;
            $item_tax = 0;

            $product = Product::where('id', $item['product_id'])->first();
            $feesTaxInfo = $product->getFeesTax();

            $additional = [
                'checkin' => $item['start_date'],
            ];

            if ($product->require_end_date && ($item['end_date'] ?? null)) {
                $additional['checkout'] = $item['end_date'];

                $diff = 0;
                $checkin = Carbon::parse($additional['checkin'])->timezone('Asia/Damascus');
                $checkout = Carbon::parse($additional['checkout'])->timezone('Asia/Damascus');

                while ($checkin <= $checkout) {
                    $diff++;
                    $checkin->setTime($checkout->hour, $checkout->minute)->addDay();
                }

                $item_total += $diff * $product->price;
                $item_fees = $feesTaxInfo['fees'] * $diff;
                $item_tax = $feesTaxInfo['tax'] * $diff;
            } else {
                $item_total += $product->price;
                $item_fees = $feesTaxInfo['fees'];
                $item_tax = $feesTaxInfo['tax'];
            }

            OrderItem::create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'price' => $product->price,
                'fees_amount' => $feesTaxInfo['fees'],
                'tax_amount' => $feesTaxInfo['tax'],
                'total' => $item_total + $item_fees + $item_tax,
                'subtotal' => $item_total,
                'fees_amount_subtotal' => $item_fees,
                'tax_amount_subtotal' => $item_tax,
                'quantity' => 1,
                'additional' => $additional,
            ]);

            $order_total += $item_total;
            $order_fees += $item_fees;
            $order_tax += $item_tax;
        }

        $order->update([
            'fees_amount' => $order_fees,
            'tax_amount' => $order_tax,
            'subtotal' => $order_total,
            'total' => $order_total + $order_fees + $order_tax,
        ]);

        return redirect()->route('reservations.index')->with('success_message', __('app.alert_messages.save.success', ['item' => __('app.reservation')]));
    }

    public function get_customer_details()
    {
        $phone_number = request('number');

        $customer = Customer::where([
            'phone_number' => $phone_number,
        ])->first();

        if ($customer) {
            return response()->json($customer);
        }

    }

    public function edit(Order $order)
    {
        $products = Product::where([
            ['type', 'product'],
            ['currency', $order->currency],
        ])->get();

        $reservations = Product::where([
            ['type', 'reservation'],
            ['currency', $order->currency],
        ])->get();

        $reservationsItems = [];
        $productsItems = [];

        $payment_methods = [];
        $methods = get_enabled_payment_methods();

        foreach ($methods as $method) {
            if ($method->getKey() == 'cod') {
                $payment_methods['cod'] = $method->getLabel();
            } else {
                $payment_methods[$method->getKey()] = __('payment_methods.payment_methods.' . $method->getKey());
            }
        }

        $notes = $order->notes()->with('user.store')->orderBy('created_at', 'DESC')->get();

        foreach ($order->items as $item) {
            if ($item->product()->withTrashed()->first()->type == 'product') {
                $productsItems[] = $item;
            } else {
                $reservationsItems[] = $item;
            }
        }

        return view('newDashboard.orders.edit', compact('order', 'products', 'reservations', 'payment_methods', 'notes', 'productsItems', 'reservationsItems'));
    }

    public function update_order(Request $request, Order $order)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone_number' => 'required',
            'status' => 'required',
            'products' => 'required',
        ]);

        if (str_contains(url()->previous(), 'subscriptions')) {
            $viewRoute = 'orders.subscriptions.view';
        } else {
            $viewRoute = 'orders.view';
        }

        $name = explode(' ', $request->name);
        $firstName = $name[0];
        array_shift($name);
        $lastName = implode(' ', $name);

        $orderData = [
            "first_name" => $firstName,
            "last_name" => $lastName,
            "phone_number" => $request->phone_number,
            "governorate" => $request->governorate,
            "address" => $request->address,
            "notes" => $request->notes,
            "additional_question1" => $request->additional_question1,
            "additional_question2" => $request->additional_question2,
        ];

        if ($order->canNotEditOrder) {
            // in case the orders are subscriptions for stores
            $subscription = Subscription::where('order_id', $order->id)->first();

            if ($subscription) {
                if ($request->status == Order::PAID || $request->status == Order::COMPLETED) {
                    (new ConfirmOrder($subscription))->handle();
                } else if ($request->status == Order::CANCELED || $request->status == Order::FAILED) {
                    if ($subscription->approved_at) {
                        (new CancelOrder($subscription))->handle();
                    }
                }
            }

            $order->update(array_merge($orderData, ['status' => $request->status]));
            return redirect()->route($viewRoute, [$order])->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.order.orders', 1)]));
        }

        $order->update(array_merge($orderData, ['payment_method' => $request->payment_method]));

        $order_total = 0;
        $order_fees = 0;
        $order_tax = 0;
        $products = $request->products;
        foreach ($products as $product) {
            $price = 0;
            $fees = 0;
            $tax = 0;
            $originProduct = Product::where('id', $product['product_id'])->first();

            $price = $originProduct->price;

            if (array_key_exists('price', $product) && $product['price'] == 'old') {
                // in this case the item is already exist so it has orderItem_id
                $item = OrderItem::where('id', $product['orderItem_id'])->first();
                $price = $item->price;
            }

            $feesTaxInfo = $originProduct->getFeesTax();

            if (!$originProduct->isReservation()) {
                $total = $price * $product['quantity'];
                $fees = $feesTaxInfo['fees'] * $product['quantity'];
                $tax = $feesTaxInfo['tax'] * $product['quantity'];
            } else {
                $additional['checkin'] = $product['checkin_date'];

                if ($originProduct->require_end_date && ($product['checkout_date'])) {
                    $additional['checkout'] = $product['checkout_date'];

                    $diff = 0;
                    $checkinDate = Carbon::parse($additional['checkin'])->timezone('Asia/Damascus');
                    $checkoutDate = Carbon::parse($additional['checkout'])->timezone('Asia/Damascus');

                    while ($checkinDate <= $checkoutDate) {
                        $diff++;
                        $checkinDate->setTime($checkoutDate->hour, $checkoutDate->minute)->addDay();
                    }

                    $total = ($diff * $price);
                    $fees = $feesTaxInfo['fees'] * $diff;
                    $tax = $feesTaxInfo['tax'] * $diff;
                } else {
                    $total = $price;
                    $fees = $feesTaxInfo['fees'];
                    $tax = $feesTaxInfo['tax'];
                }
            }

            if (!array_key_exists('orderItem_id', $product)) {
                $createdItem = OrderItem::query()->create([
                    'product_id' => $originProduct->id,
                    'order_id' => $order->id,
                    'price' => $price,
                    'total' => $total + $fees + $tax,
                    'fees_amount' => $feesTaxInfo['fees'],
                    'tax_amount' => $feesTaxInfo['tax'],
                    'subtotal' => $total,
                    'fees_amount_subtotal' => $fees,
                    'tax_amount_subtotal' => $tax,
                    'quantity' => $product['quantity'] ?? 1,
                    'additional' => $additional ?? [],
                ]);
                $newItems[] = $createdItem->id;
            } else {
                OrderItem::where('id', $product['orderItem_id'])
                    ->update([
                        'price' => $price,
                        'total' => $total + $fees + $tax,
                        'fees_amount' => $feesTaxInfo['fees'],
                        'tax_amount' => $feesTaxInfo['tax'],
                        'subtotal' => $total,
                        'fees_amount_subtotal' => $fees,
                        'tax_amount_subtotal' => $tax,
                        'quantity' => $product['quantity'] ?? 1,
                        'additional' => $additional ?? [],
                    ]);
                $newItems[] = $product['orderItem_id'];
            }

            $order_total += $total;
            $order_fees += $fees;
            $order_tax += $tax;
        }
        $order->items()->whereNotIn('id', $newItems)->delete();

        // in case this order is subscription
        $subscription = Subscription::where('order_id', $order->id)->first();

        if ($subscription) {
            if ($request->status == Order::PAID || $request->status == Order::COMPLETED) {
                (new ConfirmOrder($subscription))->handle();
            } else if ($request->status == Order::CANCELED || $request->status == Order::FAILED) {
                if ($subscription->approved_at) {
                    (new CancelOrder($subscription))->handle();
                }
            }
        }

        $order->update([
            'status' => $request->status,
            'fees_amount' => $order_fees,
            'tax_amount' => $order_tax,
            'subtotal' => $order_total,
            'total' => $order_total + $order_fees + $order_tax,
        ]);
        return redirect()->route($viewRoute, [$order])->with('success_message', __('app.alert_messages.update.success', ['item' => trans_choice('app.order.orders', 1)]));
    }

    public function get_products()
    {
        $products = Product::find(request('products'));
        return ProductResource::collection($products);
    }

    public function calculateTotalOldOrderLine()
    {
        $price = 0;
        $orderItem = OrderItem::where('id', request('orderItem_id'))->first();

        $product = $orderItem->product;

        if (request('isOld') && request('isOld') == 'old') {
            $price = $orderItem->price;
        } else {
            $price = $product->price;
        }

        if (!$product->isReservation()) {
            $line_subtotal = $price * request('quantity');
            $fees_amount_subtotal = $orderItem->fees_amount * request('quantity');
            $tax_amount_subtotal = $orderItem->tax_amount * request('quantity');
        } else {
            if ($product->require_end_date) {

                $diff = 0;
                $checkin = Carbon::parse(request('startDate'))->timezone('Asia/Damascus');
                $checkout = Carbon::parse(request('endDate'))->timezone('Asia/Damascus');

                while ($checkin <= $checkout) {
                    $diff++;
                    $checkin->setTime($checkout->hour, $checkout->minute)->addDay();
                }

                $line_subtotal = $diff * $price;
                $fees_amount_subtotal = $orderItem->fees_amount * $diff;
                $tax_amount_subtotal = $orderItem->tax_amount * $diff;
            } else {
                $line_subtotal = $price;
                $fees_amount_subtotal = $orderItem->fees_amount;
                $tax_amount_subtotal = $orderItem->tax_amount;
            }
        }

        $line_total = $line_subtotal + $fees_amount_subtotal + $tax_amount_subtotal;

        $formattedLineSubtotal = price_format($line_subtotal, __('app.currency_types.' . $product->currency));
        $formattedFeesAmountSubtotal = price_format($fees_amount_subtotal, __('app.currency_types.' . $product->currency));
        $formattedTaxAmountSubtotal = price_format($tax_amount_subtotal, __('app.currency_types.' . $product->currency));
        $formattedLineTotal = price_format($line_total, __('app.currency_types.' . $product->currency));

        return response()->json([
            'subtotal' => $formattedLineSubtotal,
            'fees_total' => $formattedFeesAmountSubtotal,
            'tax_total' => $formattedTaxAmountSubtotal,
            'total' => $formattedLineTotal,
        ]);
    }

    public function calculateTotalNewOrderLine()
    {
        $product = Product::where('id', request('product_id'))->first();
        $feesTaxInfo = $product->getFeesTax();

        if (!$product->isReservation()) {
            $subtotal = $product->price * request('quantity');
            $fees = $feesTaxInfo['fees'] * request('quantity');
            $tax = $feesTaxInfo['tax'] * request('quantity');
        } else {
            if ($product->require_end_date) {

                $diff = 0;
                $checkin = Carbon::parse(request('startDate'))->timezone('Asia/Damascus');
                $checkout = Carbon::parse(request('endDate'))->timezone('Asia/Damascus');

                while ($checkin <= $checkout) {
                    $diff++;
                    $checkin->setTime($checkout->hour, $checkout->minute)->addDay();
                }

                $subtotal = $diff * $product->price;
                $fees = $feesTaxInfo['fees'] * $diff;
                $tax = $feesTaxInfo['tax'] * $diff;
            } else {
                $subtotal = $product->price;
                $fees = $feesTaxInfo['fees'];
                $tax = $feesTaxInfo['tax'];
            }
        }
        $total = $subtotal + $fees + $tax;

        $formattedSubtotal = price_format($subtotal, __('app.currency_types.' . $product->currency));
        $formattedFeesTotal = price_format($fees, __('app.currency_types.' . $product->currency));
        $formattedTaxTotal = price_format($tax, __('app.currency_types.' . $product->currency));
        $formattedTotal = price_format($total, __('app.currency_types.' . $product->currency));

        return response()->json([
            'subtotal' => $formattedSubtotal,
            'fees_total' => $formattedFeesTotal,
            'tax_total' => $formattedTaxTotal,
            'total' => $formattedTotal,
        ]);
    }

    public function calculateOrderTotal()
    {
        $orderTotal = 0;
        $orderFees = 0;
        $orderTax = 0;
        $price = 0;

        if (request('products')) {

            foreach (request('products') as $item) {

                if (array_key_exists('orderItem_id', $item)) {
                    $orderItem = OrderItem::where('id', $item['orderItem_id'])->first();
                    $product = $orderItem->product;
                } else {
                    $product = Product::where('id', $item['product_id'])->first();
                }

                if (array_key_exists('isOld', $item) && $item['isOld'] == 'old') {
                    $price = $orderItem->price;
                } else {
                    $price = $product->price;
                }

                $feesTaxInfo = $product->getFeesTax();

                if (!$product->isReservation()) {
                    $lineTotal = $price * $item['quantity'];
                    $fees = $feesTaxInfo['fees'] * $item['quantity'];
                    $tax = $feesTaxInfo['tax'] * $item['quantity'];
                } else {
                    if ($product->require_end_date) {

                        $diff = 0;
                        $checkin = Carbon::parse($item['startDate'])->timezone('Asia/Damascus');
                        $checkout = Carbon::parse($item['endDate'])->timezone('Asia/Damascus');

                        while ($checkin <= $checkout) {
                            $diff++;
                            $checkin->setTime($checkout->hour, $checkout->minute)->addDay();
                        }

                        $lineTotal = $diff * $price;
                        $fees = $feesTaxInfo['fees'] * $diff;
                        $tax = $feesTaxInfo['tax'] * $diff;
                    } else {
                        $lineTotal = $price;
                        $fees = $feesTaxInfo['fees'];
                        $tax = $feesTaxInfo['tax'];
                    }
                }
                $orderTotal += $lineTotal;
                $orderFees += $fees;
                $orderTax += $tax;
            }

        }

        $order = Order::where('id', request('order_id'))->first();

        $formattedSubtotal = price_format($orderTotal, __('app.currency_types.' . $order->currency));
        $formattedFeesTotal = price_format($orderFees, __('app.currency_types.' . $order->currency));
        $formattedTaxTotal = price_format($orderTax, __('app.currency_types.' . $order->currency));
        $formattedOrderTotal = price_format($orderTotal + $orderFees + $orderTax, __('app.currency_types.' . $order->currency));

        return response()->json([
            'subtotal' => $formattedSubtotal,
            'fees_total' => $formattedFeesTotal,
            'tax_total' => $formattedTaxTotal,
            'total' => $formattedOrderTotal,
        ]);
    }

    public function add_order_note(Request $request, Order $order)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        try {
            OrderNote::create([
                'body' => $request->body,
                'user_id' => auth()->user()->id,
                'order_id' => $order->id,
            ]);
            return redirect()->back()->with('success_message', __('app.alert_messages.save.success', ['item' => __('app.item')]));
        } catch (\Exception$e) {
            logger($e->getMessage());
            return redirect()->back()->with('error_message', __('app.alert_messages.save.failed', ['item' => __('app.item')]));
        }
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

}
