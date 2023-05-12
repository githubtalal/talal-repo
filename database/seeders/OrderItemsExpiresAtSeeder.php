<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Database\Seeder;

class OrderItemsExpiresAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = Store::whereHas('user.roles', function ($q) {
            $q->where('name', 'store-owner');
        })->get();

        foreach ($stores as $store) {
            $order = Order::where('id', $store->order_id)->first();
            $order->items()->update(['expires_at' => $store->expires_at]);
        }
    }
}
