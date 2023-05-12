<?php

namespace App;

use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;

class ResubscriptionCart extends Cart
{
    public function getCart()
    {
        return \App\Models\Cart::query()->where([
            'customer_uuid' => $this->getCustomerId(),
            'store_id' => $this->getStoreId(),
            'active' => true,
        ])->first();
    }

    public function createCart()
    {
        $cart = \App\Models\Cart::query()->create([
            'customer_uuid' => $this->getCustomerId(),
            'active' => true,
            'total' => 0,
            'store_id' => $this->getStoreId(),
            'payment_ref' => Str::random(),
        ]);
        return $cart;
    }

    public function addProduct($productId, $data = [])
    {
        $cart = $this->getCart();
        if (!$cart) {
            $cart = $this->createCart();
        }
        $product = Product::withoutGlobalScope('store_access')->find($productId);
        $cartItem = CartItem::query()->create([
            'product_id' => $productId,
            'cart_id' => $cart->id,
            'price' => $product->price,
            'quantity' => 1,
            'additional' => '',
        ]);
    }

    public function calculateCartTotal()
    {
        $total = 0;
        $cart = $this->getCart();
        foreach ($cart->items as $item) {
            $total += $item->price;
        }
        $cart->update(['total' => $total]);
    }

    public function saveOrder($cart)
    {
        $customer = Customer::withoutGlobalScope('store_access')->where('uuid', $this->getCustomerId())->first();

        if (!$customer) {
            $customer = Customer::query()->create([
                'uuid' => $this->getCustomerId(),
                'phone_number' => $cart->customer_phone_number,
                'first_name' => $cart->customer_first_name,
                'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
            ]);
        } else {
            $customer->update([
                'first_name' => $cart->customer_first_name,
                'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
                'phone_number' => $cart->customer_phone_number,
            ]);
        }

        $data = [
            'store_id' => $this->getStoreId(),
            'customer_id' => $customer->id,
            'first_name' => $cart->customer_first_name,
            'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
            'governorate' => $cart->customer_governorate,
            'address' => $cart->customer_address ?? null,
            'phone_number' => $cart->customer_phone_number ?? null,
            'total' => '',
            'payment_method' => $cart->payment_method,
            'payment_info' => $cart->payment_info,
            'platform' => $cart->platform,
            'payment_ref' => $cart->payment_ref ?? null,
            'currency' => $cart->currency,
        ];

        $order = Order::withoutEvents(function () use ($data) {
            return Order::create($data);
        });

        $total = 0;
        foreach ($cart->items as $item) {
            $orderItem = OrderItem::query()->create([
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'price' => $item->price,
                'total' => $item->price,
                'quantity' => $item->quantity,
                'additional' => $item->additional,
            ]);
            $total += $orderItem->price;
        }

        $updatedOrder = $order->withoutEvents(function () use ($order, $total) {
            $order->update(['total' => $total]);
            return $order;
        });

        return $updatedOrder;
    }

    public function getStoreId()
    {
        return session('store_id');
    }

    public function getCustomerId()
    {
        return session('user_id');
    }
}
