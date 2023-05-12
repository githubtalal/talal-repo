<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        // TODO: add groupBy on cart_id
        $items = CartItem::withTrashed()
            ->where('product_id', $product->id)
            ->get();

        foreach ($items as $item) {
            $cart = Cart::withTrashed()->where('id', $item->cart_id)->first();

            $total = 0;
            foreach ($cart->items as $item) {
                $total += $item->price;
            }
            $cart->total = $total;
            $cart->save();
        }
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
