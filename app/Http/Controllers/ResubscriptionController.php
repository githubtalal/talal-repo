<?php

namespace App\Http\Controllers;

use App\ConfirmOrder;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreSettings;
use App\Models\Subscription;
use App\ResubscriptionCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ResubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('subscriptions.store_id', auth()->user()->store->id)->orderBy('created_at', 'desc')->get();

        $storeSubscriptions = [];
        foreach ($subscriptions as $subscription) {
            $services = [];
            $order = Order::withoutGlobalScope('store_access')->where('id', $subscription->order_id)->first();
            if ($order) {
                foreach ($order->items as $item) {
                    $product = Product::withoutGlobalScope('store_access')->where('id', $item->product_id)->first();
                    $services[] = [
                        'name' => $product->additional['permission_name'],
                    ];
                }
                $storeSubscriptions[] = [
                    'id' => $subscription->id,
                    'payment_method' => $order->payment_method,
                    'services' => $services,
                    'status' => $order->status,
                    'approved_at' => $subscription->approved_at,
                ];
            }

        }

        $permissions = [];
        $products = Product::query()->withoutGlobalScope('store_access')->whereJsonContainsKey('additional->permission_name')->get();
        foreach ($products as $product) {
            $permission = Permission::where('name', $product->additional['permission_name'])->first();
            $model_has_permission = DB::table('model_has_permissions')
                ->where([
                    ['model_id', auth()->user()->id],
                    ['permission_id', $permission->id],
                ])->first();
            if ($model_has_permission && $model_has_permission->expires_at) {
                $permissions[] = [
                    'name' => $product->additional['permission_name'],
                    'expires_at' => $model_has_permission->expires_at,
                ];
            }
        }

        return view('newDashboard.subscriptions.index', compact('storeSubscriptions', 'permissions'));
    }

    public function create()
    {
        $products = Product::query()->withoutGlobalScope('store_access')
            ->whereJsonContainsKey('additional->permission_name')
            ->get();

        $storeId = auth()->user()->store_id;
        $products = $products->filter(function($product) use ($storeId) {
            return storeHasPermission($product->additional['permission_name'], $storeId) || $product->additional['permission_name'] !== 'website_widget'    ;
        });

        // get admin store
        $adminStore = Store::whereHas('user.roles', function ($q) {
            $q->where('name', 'super-admin');
        })->first();

        // get payment methods of ecart admin's store
        $payment_methods = StoreSettings::where([
            ['store_id', $adminStore->id],
            ['key', 'payment_method'],
        ])->first();

        if (!$payment_methods) {
            return back()->with('error_message', 'No payment methods available by Admin.');
        }

        $methods = $payment_methods->value;
        $originalMethods = [];

        $enabled_payment_methods = [];
        foreach ($methods as $key => $value) {
            if ($value['enabled'] ?? false) {
                $class = config('payment_methods')[$key];
                $paymentObject = new $class($adminStore->id, (array)$value);
                if ($key == 'cod' && ($value['label'] ?? false)) {
                    $paymentObject->setLabel($value['label']);
                }
                $enabled_payment_methods[] = $paymentObject;
            }
        }

        if (!$enabled_payment_methods) {
            return back()->with('error_message', 'No payment methods available by Admin.');
        }

        foreach ($enabled_payment_methods as $method) {
            if ($method->getKey() == 'cod') {
                $originalMethods['cod'] = $method->getLabel();
            } else {
                $originalMethods[$method->getKey()] = __('payment_methods.payment_methods.' . $method->getKey());
            }
        }

        return view('newDashboard.subscriptions.resubscription', compact('products', 'originalMethods' ,'enabled_payment_methods'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'payment_method' => 'required',
            'services' => 'array|required',
        ]);

        $user = auth()->user();
        $store = $user->store;

        // eCart Store
        $adminStore = Store::whereHas('user.roles', function ($q) {
            $q->where('name', 'super-admin');
        })->first();

        session()->put('user_id', $user->id);
        session()->put('store_id', $adminStore->id);

        // Converting all abandoned carts to active=False
        Cart::query()->where([
            'customer_uuid' => $user->id,
            'store_id' => $adminStore->id
        ])->update([
            'active' => false,
        ]);

        $cartClass = new ResubscriptionCart();
        $cart = $cartClass->createCart();

        $cart->update([
            'customer_first_name' => $user->name,
            'customer_last_name' => '',
            'customer_phone_number' => $user->phone_number,
            'customer_governorate' => 'Damascus',
            'additional' => [
                'additional_fields' => request('additional_user_fields', []),
            ],
        ]);

        $subscription = Subscription::query()->create([
            'store_id' => $store->id,
            'payment_ref' => $cart->payment_ref,
        ]);

        foreach ($request->services as $productId) {
            $cartClass->addProduct($productId);
        }
        $cartClass->calculateCartTotal();

        $cart->update([
            'payment_method' => $request->payment_method,
            'payment_redirect_uri' => 'resubscription.payment.redirect'
        ]);
        $cart = $cart->refresh();

        $checkoutResult = $cartClass->checkout($cart);

        if (is_string($checkoutResult)) {
            return redirect()->away($checkoutResult);
        } else if ($checkoutResult instanceof Order) {
            $subscription->update([
                'order_id' => $checkoutResult->id,
            ]);
            return \redirect()->route('resubscription.index')->with('success_message', __('responses.checkout.inperson_payment'));
        } else {
            return redirect()->back()->with('error_message', 'عملية الدفع لم تنجح!
                الرجاء أختيار وسيلة دفع أخرى أو إعادة المحاولة.')->withInput($request->all());
        }
    }

    public function redirectAfterPayment()
    {
        $paymentRef = request('payment_ref');

        if (!$paymentRef) abort(400);

        $order = Order::query()
            ->withoutGlobalScope('store_access')
            ->where('payment_ref', $paymentRef)
            ->first();

        if (!$order || !$order->isPaid()) {
            return \redirect()->route('resubscription.create')->with('error_message', 'عملية الدفع لم تنجح!
                الرجاء أختيار وسيلة دفع أخرى أو إعادة المحاولة.');
        }

        return \redirect()->route('resubscription.index')->with('success_message', 'تم تجديد الأشتراك بنجاح');
    }

    public function resubscriptionCallback()
    {
        $gate_way = \request('gateway') ?? request('amp;gateway');
        $cart = \App\Models\Cart::find(request('cart_id'));
        $store = Store::where('id', $cart->store_id)->first();
        $user = auth()->user();

        $class = config('payment_methods.' . $gate_way);
        $paymentMethod = new $class($store->id);
        $paymentMethod = $paymentMethod->parseResponse(array_merge($cart->payment_info, request()->all()));

        if ($paymentMethod->isPaymentSucceeded()) {
            $cart->update([
                'customer_first_name' => $user->name,
                'customer_last_name' => '',
                'customer_phone_number' => $user->phone_number,
                'customer_governorate' => 'Damascus',
            ]);
            $order = (new ResubscriptionCart())->saveOrder($cart);

            $order->withoutEvents(function () use ($order, $paymentMethod) {
                $order->update([
                    'status' => Order::PAID,
                    'trx_num' => $paymentMethod->getTransactionNumber(),
                ]);
            });

            $subscription = Subscription::create([
                'order_id' => $order->id,
                'store_id' => $user->store->id,
            ]);

            (new ConfirmOrder($subscription))->handle();
            return \redirect()->route('resubscription.index');
        }

        return \redirect()->route('resubscription.create')->with('error_message', 'Fatora Payment does not succeed.');
    }
}
