<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreSettings;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignupPaymentController extends Controller
{
    public function callback()
    {
        $payment_ref = \request('payment_ref');

        // TODO: Add logger

        $cart = \App\Models\Cart::query()->where('payment_ref', $payment_ref)->first();
        $gate_way = $cart->payment_method;

        if (!$cart) abort(400);

        request()->request->set('user_id', $cart->customer_uuid);
        request()->request->set('store_id', $cart->store_id);

        $class = config('payment_methods.' . $gate_way);
        $paymentMethod = new $class($cart->store_id);
        $paymentMethod = $paymentMethod->parseResponse(array_merge($cart->payment_info, request()->all()));

        if ($paymentMethod->isPaymentSucceeded() && $cart->active) {
            $cart->update([
                'customer_first_name' => $cart->additional['store_info']['store_manager'],
                'customer_last_name' => '',
                'customer_phone_number' => $cart->additional['store_info']['phone_number'],
                'customer_governorate' => 'Damascus',
            ]);
            $order = (new \App\Cart())->saveOrder($cart);

            $storeUser = $this->createUserAndStore($cart->additional['store_info'], $cart->additional['store_info']['products'] ?? []);

            \request()->request->set('store_id', $storeUser->store_id);
            session()->put('store_id', $storeUser->store_id);

            $order->update([
                'status' => Order::PAID,
                'trx_num' => $paymentMethod->getTransactionNumber(),
                'payment_info' => [
                    'subscription_date' => now(),
                    'expire_in' => now()->addMonth(),
                    'subscription_store_id' => $storeUser->store_id,
                ],
            ]);
            $storeUser->store()->update([
                'expires_at' => now()->addMonth(),
                'order_id' => $order->id,
                'is_active' => true,
            ]);
        }
    }


    public function redirect()
    {
        $payment_ref = \request('payment_ref');

        $order = Order::query()->where('payment_ref', $payment_ref)->first();

        if (!$order || !$order->isPaid()) {
            return redirect('/')->with('error_message', __('app.payment_error.payment_error'));
        }
        $store = Store::query()->where('order_id', $order->id)->first();

        $user = User::query()->where('store_id', $store->id)->first();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function createUserAndStore($data, $products = [])
    {
        $products = Product::query()->find($products);
        $permissions = [];
        foreach ($products as $product) {
            if ($product->additional['permission_name'] ?? false) {
                $permissions[] = $product->additional['permission_name'];
            }
        }

        $store = Store::query()->create([
            'name' => $data['store_name'],
            'slug' => Str::slug($data['store_name']),
            'type' => $data['store_type'],
            'token' => Str::random(24),
        ]);
        $paymentMethods = [
            'cod' => [
                'enabled' => true,
            ],
        ];

        StoreSettings::create([
            'key' => 'payment_method',
            'value' => $paymentMethods,
            'store_id' => $store->id,
        ]);

        // store bot settings (steps) by default
        StoreSettings::create([
            "key" => "bot_settings",
            "value" => [
                'steps' => [
                    'governorate' => 'enabled',
                    'address' => 'enabled',
                    'notes' => 'enabled',
                ],
                'hour' => 'enabled',
                'power_button' => 'enabled',
            ],
            "store_id" => $store->id,
        ]);

        $user = User::query()->create([
            'name' => $data['store_manager'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'store_id' => $store->id,
        ]);
        $user->givePermissionTo($permissions);
        $user->assignRole('store-owner');
        return $user;
    }

}
