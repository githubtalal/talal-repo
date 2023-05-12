<?php

namespace App;

use App\Events\OrderCreated;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\PaymentMethods\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Cart
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
            'platform_id' => $this->getCustomerId(),
        ]);
        return $cart;
    }

    public function can_add_product_to_cart($productId)
    {
        $canAdd = true;
        $product = Product::find($productId);

        $cart = $this->getCart();

        if (!$cart) {
            $cart = $this->createCart();
            $cart->update([
                'currency' => $product->currency,
            ]);
        } else {
            // in case there is a cart
            if (count($cart->items) > 0) {
                // if cart not empty we have to make sure of the new product currency
                if ($product->currency != $cart->currency) {
                    $canAdd = false;
                }

            } else {
                // in case it is empty
                $cart->update([
                    'currency' => $product->currency,
                ]);
            }
        }
        return $canAdd;
    }

    public function     addProduct($productId, $data = [])
    {
        $cart = $this->getCart();
        if (!$cart) {
            $cart = $this->createCart();
        }

        $product = Product::find($productId);
        $cartItem = CartItem::query()->create([
            'product_id' => $productId,
            'cart_id' => $cart->id,
            'price' => $product->price,
            'quantity' => $data['quantity'] ?? 1,
            'additional' => $data,
        ]);
        $cartItem = $this->calculateCartItem($cartItem);
        $this->reCalculateCart();
        return $cartItem;
    }

    public function calculateCartItem($cartItem)
    {
        $qty = $this->getQtyFromCartItem($cartItem);
        $totals = $this->calculateProductTotals($cartItem->product, $qty);

        $cartItem->update([
            'price' => $totals['total'],
            'fees_amount' => $totals['total_fees'],
            'tax_amount' => $totals['total_tax'],
            'subtotal' => $totals['subtotal'],
        ]);
        return $cartItem;
    }

    public function reCalculateCart()
    {
        $cart = $this->getCart();
        $total = 0;
        $fees = 0;
        $tax = 0;
        $subtotal = 0;

        foreach ($cart->items as $item) {
            $qty = $this->getQtyFromCartItem($item);
            $totals = $this->calculateProductTotals($item->product, $qty);
            $cartItem = $this->calculateCartItem($item);

            $total += $totals['total'];
            $fees += $totals['total_fees'];
            $tax += $totals['total_tax'];
            $subtotal += $totals['subtotal'];
        }

        $cart->update([
            'fees_amount' => $fees,
            'tax_amount' => $tax,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
        return $cart;
    }

    public function updateItemQty($itemId, $qty)
    {
        $cart = $this->getCart();
        $cart->items()->find($itemId)->update(['quantity' => $qty]);
        $this->reCalculateCart();
    }

    public function saveOrder($cart)
    {
        $customer = Customer::query()->where([
            'uuid' => $this->getCustomerId(),
        ])->first();
        if (!$customer) {
            $customer = Customer::query()->withoutGlobalScope('store_access')->create([
                'uuid' => $this->getCustomerId(),
                'phone_number' => $cart->customer_phone_number,
                'first_name' => $cart->customer_first_name,
                'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
            ]);
        } else {
            Customer::query()->withoutGlobalScope('store_access')
                ->where('id', $customer->id)
                ->update([
                'first_name' => $cart->customer_first_name,
                'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
                'phone_number' => $cart->customer_phone_number,
            ]);
        }

        $order = Order::query()->withoutGlobalScope('store_access')->create([
            'store_id' => $this->getStoreId(),
            'customer_id' => $customer->id,
            'first_name' => $cart->customer_first_name,
            'last_name' => $cart->customer_last_name ?? $cart->customer_first_name,
            'governorate' => $cart->customer_governorate,
            'address' => $cart->customer_address ?? null,
            'phone_number' => $cart->customer_phone_number ?? null,
            'total' => '',
            'payment_method' => $cart->payment_method,
            'payment_ref' => $cart->payment_ref ?? null,
            'payment_info' => $cart->payment_info,
            'platform' => $cart->platform,
            'notes' => $cart->notes,
            'additional_question1' => $cart->additional_question1,
            'additional_question2' => $cart->additional_question2,
            'currency' => $cart->currency,
            'cart_id' => $cart->id,
        ]);

        foreach ($cart->items as $item) {
            $qty = $this->getQtyFromCartItem($item);
            $itemTotals = $this->calculateProductTotals($item->product, $qty);

            OrderItem::query()->create([
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'price' => $itemTotals['price'],
                'fees_amount' => $itemTotals['fees'],
                'fees_amount_subtotal' => $itemTotals['total_fees'],
                'tax_amount' => $itemTotals['tax'],
                'tax_amount_subtotal' => $itemTotals['total_tax'],
                'subtotal' => $itemTotals['subtotal'],
                'total' => $itemTotals['total'],
                'quantity' => $item->quantity,
                'additional' => $item->additional,
            ]);
        }

        $cartInfo = $this->reCalculateCart();
        $order->update([
            'subtotal' => $cartInfo->subtotal,
            'fees_amount' => $cartInfo->fees_amount,
            'tax_amount' => $cartInfo->tax_amount,
            'total' => $cartInfo->total,
        ]);
        return $order;
    }

    public function removeFromCart($cartItemId)
    {
        $cart = $this->getCart();
        if (!$cart) {
            return;
        }

        $cart->items()->where('id', $cartItemId)->forceDelete();
        $cart = $this->reCalculateCart();
        return $cart;
    }

    public function deleteCart()
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->update(['active' => false]);
            return true;
        }
        return false;
    }

    public function updateCart()
    {
        $deleted_products = [];
        $updated_products = [];

        $cart = $this->getCart();
        if (!$cart) {
            return [
                'deleted_products' => $deleted_products,
                'updated_products' => $updated_products,
            ];
        }

        // get deleted products
        $all_items = $cart->items()->withTrashed()->get();
        foreach ($all_items as $item) {
            if ($item->deleted_at) {
                $product = Product::withTrashed()->where([['id', $item->product_id], ['store_id', $this->getStoreId()]])->first();
                $deleted_products[] = $product;
            }
        }

        $cart = $this->getCart();
        if ($cart->items()->count() == 0) {
            return [
                'deleted_products' => $deleted_products,
                'updated_products' => $updated_products,
            ];
        }

        foreach ($cart->items as $item) {
            $product = Product::where([['id', $item->product_id], ['store_id', $this->getStoreId()]])->first();

            $category = Category::where([['id', $product->category_id], ['store_id', $this->getStoreId()]])->first();

            if (!$category->active) {
                $this->removeFromCart($item->id);
                $deleted_products[] = $product;
            }

            // Checking if the product is active
            if (!$product->active) {
                $this->removeFromCart($item->id);
                $deleted_products[] = $product;
            }

            // Check the totals
            $previousCartItemTotal = $item->price;
            $itemQty = $this->getQtyFromCartItem($item);
            $newCartItemTotal = $this->calculateProductTotals($item->product, $itemQty);

            // It should be deleted, there is something wrong the cart item.
            if ($newCartItemTotal == 0) {
                $this->removeFromCart($item);
                $deleted_products[] = $product;
            } else if ($newCartItemTotal['total'] != $item->price) {
                $item->update([
                    'price' => $newCartItemTotal['total'],
                    'fees_amount' => $newCartItemTotal['total_fees'],
                    'tax_amount' => $newCartItemTotal['total_tax'],
                    'quantity' => $itemQty,
                    'subtotal' => $newCartItemTotal['subtotal'],
                ]);

                $updated_products[] = $product;
            }

            $this->reCalculateCart();
        }

        return [
            'deleted_products' => $deleted_products,
            'updated_products' => $updated_products,
        ];
    }

    public function getStoreId()
    {
        return session('store_id') ?? request()->get('store_id');
    }

    public function getCustomerId()
    {
        return session('user_id') ?? request()->get('user_id');
    }

    function empty() {
        $cart = $this->getCart();
        if (!$cart) {
            return;
        }

        foreach ($cart->items as $item) {
            $item->delete();
        }

        $this->reCalculateCart();
    }

    /**
     * @param \App\Models\Cart $cart
     * @param array $additional
     * @return null|string
     */
    public function checkout($cart, $additional = [])
    {
        $payment_method = $cart->payment_method;
        $payment_ref = $cart->payment_ref;

        if (!config()->has('payment_methods.' . $payment_method)) {
            return null;
        }

        $cart = $cart->refresh();

        $paymentMethodClassName = config('payment_methods.' . $payment_method);
        /** @var PaymentMethod $paymentMethodClass */
        $paymentMethodClass = new $paymentMethodClassName($cart->store_id);

        if ($paymentMethodClass->needsRedirect()) {
            $redirectUri = $paymentMethodClass->getRedirectUri($cart, array_merge($additional, [
                'payment_ref' => $payment_ref,
            ]));

            if (!$redirectUri) {
                return null;
            }

            $cart->update([
                'payment_info' => $paymentMethodClass->getPaymentInfo(),
            ]);

            return $redirectUri;
        }

        $order = $this->saveOrder($cart);

        Order::query()
            ->withoutGlobalScope('store_access')
            ->where([
                'id' => $order->id,
            ])
            ->update([
            'status' => Order::PENDING,
        ]);

        $cart->update([
            'active' => false,
        ]);

        OrderCreated::dispatch($order);

        return $order;

    }

    public function calculateProductTotals(Product $product, $qty = 1)
    {
        $additionalTotals = $product->getFeesTax();

        $fees = $additionalTotals['fees'];
        $tax = $additionalTotals['tax'];
        $sub_total = $product->price * $qty;

        $totalTax = $tax * $qty;
        $totalFees = $fees * $qty;
        $total = $totalFees + $totalTax + $sub_total;

        $currency = __('app.currency_types.' . $product->currency);

        return array_merge($additionalTotals, [
            'price' => $product->price,
            'tax' => $tax,
            'fees' => $fees,
            'total_tax' => $totalTax,
            'total_fees' => $totalFees,
            'subtotal' => $sub_total,
            'total' => $total,

            'formatted_price' => price_format($product->price, $currency),
            'formatted_tax' => price_format($tax, $currency),
            'formatted_fees' => price_format($fees, $currency),
            'formatted_total_tax' => price_format($totalTax, $currency),
            'formatted_total_fees' => price_format($totalFees, $currency),
            'formatted_subtotal' => price_format($sub_total, $currency),
            'formatted_total' => price_format($total, $currency),
        ]);
    }

    public function getQtyFromCartItem(CartItem $cartItem)
    {
        $product = $cartItem->product;
        $tz = 'Asia/Damascus';

        if (!$product) {
            return 0;
        }

        if ($product->type == 'product') {
            return $cartItem->quantity;
        }

        $checkInDate = $cartItem->additional['checkin'] ?? null;

        $checkoutDate = $cartItem->additional['checkout'] ?? null;

        // No checkin date means that the product is missing a required field, therefore it should be deleted
        if (!$checkInDate) {
            return 0;
        }

        // No checkout date, it could mean that originally the product was requiring an end date then the admin changed it.
        if (!$checkoutDate) {
            return 1;
        }

        $checkInDate = Carbon::parse($checkInDate)->timezone($tz);
        $checkoutDate = Carbon::parse($checkoutDate)->timezone($tz);

        return abs($checkoutDate->diffInDays($checkInDate));
    }
}
