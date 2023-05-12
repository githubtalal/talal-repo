<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
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
            Subscription::create([
                'order_id' => $store->order_id,
                'store_id' => $store->id,
            ]);
        }
    }
}
