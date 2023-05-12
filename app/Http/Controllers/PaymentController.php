<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Events\OrderConfirmed;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function process(Request $request)
    {
        $payment_ref = $request->get('payment_ref', '');
        $cart = Cart::query()->where('payment_ref', $payment_ref)->first();

        logger("[PaymentProcessor] Got process request with payment ref [$payment_ref]");


        if (!$payment_ref || !$cart || !$cart->active) {
            logger('[PaymentProcessor] Got Invalid payment ref', [
                'payment_ref' => $payment_ref,
                'cart' => $cart,
            ]);
            abort(400, 'Invalid Payment Ref');
        }

        request()->request->set('user_id', $cart->customer_uuid);
        request()->request->set('store_id', $cart->store_id);

        logger('[PaymentProcessor] Starting Payment Processing');

        $paymentMethod = config('payment_methods.' . $cart->payment_method);

        if (!$paymentMethod) {
            logger("[PaymentProcessor] Got invalid payment method [$paymentMethod]");
            abort(400, 'Unrecognized payment method');
        }

        $paymentMethod = new $paymentMethod($cart->store_id);

        $paymentMethod = $paymentMethod->parseResponse(array_merge($cart->payment_info, request()->all()));

        if ($paymentMethod->isPaymentSucceeded()) {
            $trxNumber = $paymentMethod->getTransactionNumber();

            logger("[PaymentProcessor] Payment Succeeded with transaction number [$trxNumber].");

            $order = (new \App\Cart())->saveOrder($cart);

            Order::query()
                ->withoutGlobalScope('store_access')
                ->where([
                    'id' => $order->id,
                ])
                ->update([
                    'status' => Order::PAID,
                    'trx_num' => $paymentMethod->getTransactionNumber(),
                ]);

            $cart->update([
                'active' => false,
            ]);

            $order = $order->refresh();
            OrderCreated::dispatch($order);
            OrderConfirmed::dispatch($order);
        } else {
            logger('[PaymentProcessor] Payment Failed.');
        }
    }

    public function callback(Request $request)
    {
        $this->process($request);
        return $this->redirect($request);
    }

    public function redirect(Request $request)
    {
        $payment_ref = $request->get('payment_ref');
        $cart = Cart::query()->where('payment_ref', $payment_ref)->first();
        $order = Order::query()->withoutGlobalScope('store_access')->where('payment_ref', $payment_ref)->first();

        if (!$payment_ref || !$cart)
            abort(400);

        if (!$order || !$order->isPaid()) {
            return view('failed-payment', [
                'message' => __('responses.checkout.payment_failed')
            ]);
        }

        $redirect_uri = $cart->payment_redirect_uri ?? null;

        if (!$redirect_uri)
            return redirect()->to('/');

        return redirect()->route($redirect_uri, [
            'payment_ref' => $payment_ref,
        ]);
    }
}
