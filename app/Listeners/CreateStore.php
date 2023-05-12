<?php

namespace App\Listeners;

use App\Events\StoreCreated;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateStore
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;

        if (!isset($order->cart->additional['store_info']))
            return;

        $store_info = $data = $order->cart->additional['store_info'];
        $permissions = [];

        foreach ($order->items as $orderItem) {
            $product = $orderItem->product;
            if ($product->additional['permission_name'] ?? false) {
                $permissions[] = $product->additional['permission_name'];
            }
        }

        $store = Store::query()->create([
            'name' => $data['store_name'],
            'slug' => Str::slug($data['store_name']),
            'type' => $data['store_type'],
            'token' => Str::random(24),
            'order_id' => $order->id,
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

        StoreCreated::dispatch($store);
    }
}
