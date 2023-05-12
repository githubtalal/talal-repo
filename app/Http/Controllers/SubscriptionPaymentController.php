<?php

namespace App\Http\Controllers;

use App\ConfirmOrder;
use App\Models\Order;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use App\ResubscriptionCart;
use Illuminate\Http\Request;

class SubscriptionPaymentController extends Controller
{
    public function process()
    {
        $paymentRef = request('payment_ref');

        $cart = \App\Models\Cart::query()->where('payment_ref', $paymentRef)->first();

        $store = Store::where('id', $cart->store_id)->first();
        $user = User::query()->where('user_id', $cart->user_id)->first();

        $class = config('payment_methods.' . $cart->payment_method);
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
        }
    }

    public function redirect()
    {
        $paymentRef = request('payment_ref');

        $order = Order::query()->where('payment_ref', $paymentRef)->first();

        if (!$order || !$order->isPaid())
            return \redirect()->route('resubscription.create')->with('error_message', __('responses.checkout.payment_failed'));

        return \redirect()->route('resubscription.index');
    }
}
