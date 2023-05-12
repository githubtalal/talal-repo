<?php

namespace App\Listeners;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class SyncStorePermissionsWithSubscription
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
        $subscription = $event->subscription;

        if (!$subscription || !$subscription->approved_at)
            return;

        if ($subscription->processed)
            return;

        $order = Order::where('id', $subscription->order_id)->first();

        $store = Store::where('id', $subscription->store_id)->first();

        $user = $store->user()->first();

        $orderItems = $order->items()->pluck('product_id')->toArray();

        $neededProducts = Product::withoutGlobalScope('store_access')
            ->whereIn('id', $orderItems)->get();

        /**
         * Setting default value for set the entire store expiry date,
         * Generally the store expiry date will be the farthest expiry date of all the services
         * Otherwise, it's gonna be Today
         **/
        $maxDate = now();

        $neededServices = [];
        foreach ($order->items as $orderItem) {
            $neededServices[] = [
                'id' => $orderItem->product->id,
                'name' => $orderItem->product->additional['permission_name'],
                'quantity' => $orderItem->quantity ?? 1,
            ];
        }

        foreach ($neededServices as $service) {
            $permission = Permission::where('name', $service['name'])->first();

            if (!$user->hasPermissionTo($service['name'])) {
                $user->givePermissionTo($service['name']);
                $newExpiresAt = now()->addMonths($service['quantity']);
            } else {
                $model_has_permission = DB::table('model_has_permissions')
                    ->where([
                        ['model_id', $user->id],
                        ['permission_id', $permission->id],
                    ])->first();

                if (!$model_has_permission->expires_at) {
                    // in register case the expires_at will be null
                    $newExpiresAt = now()->addMonths($service['quantity']);
                } else {
                    $expiresAt = Carbon::parse($model_has_permission->expires_at);

                    if ($expiresAt->lte(now())) {
                        $newExpiresAt = now()->addMonths($service['quantity']);
                    } elseif ($expiresAt->gt(now())) {
                        // The store has a remaining days from the previous subscription
                        $newExpiresAt = $expiresAt->addMonths($service['quantity']);
                    }
                }

                $maxDate = $newExpiresAt->gt($maxDate) ? $newExpiresAt : $maxDate;
            }

            DB::table('model_has_permissions')
                ->where([
                    ['model_id', $user->id],
                    ['permission_id', $permission->id],
                ])
                ->update(['expires_at' => $newExpiresAt]);

            OrderItem::where([
                ['product_id', $service['id']],
                ['order_id', $order->id],
            ])->update(['expires_at' => $newExpiresAt]);

        }
        $store->update([
            'is_active' => true,
            'expires_at' => $maxDate
        ]);

        $subscription->update([
            'approved_at' => now(),
            'processed' => true,
        ]);
    }
}
