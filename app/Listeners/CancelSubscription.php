<?php

namespace App\Listeners;

use App\Models\Product;
use App\Models\Store;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CancelSubscription
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
        $subscription = Subscription::query()->where('order_id', $order->id)->first();

        if (!$subscription ||
            !$subscription->approved_at ||
            !$subscription->processed)
            return;

        $store = Store::where('id', $subscription->store_id)->first();
        $user = $store->user()->first();

        foreach ($order->items as $item) {
            $product = Product::withoutGlobalScope('store_access')->where('id', $item->product_id)->first();
            $permission = Permission::where('name', $product->additional['permission_name'])->first();

            $model_has_permission = DB::table('model_has_permissions')
                ->where([
                    ['model_id', $user->id],
                    ['permission_id', $permission->id],
                ])->first();

            $expiry_date = Carbon::parse($model_has_permission->expires_at);
            $new_expiry_date = $expiry_date->subMonth();

            if ($new_expiry_date < now()) {
                $new_expiry_date = null;
            }

            DB::table('model_has_permissions')
                ->where([
                    ['model_id', $user->id],
                    ['permission_id', $permission->id],
                ])
                ->update(['expires_at' => $new_expiry_date]);
        }

        $subscription->update([
            'approved_at' => null,
            'processed' => false,
        ]);
    }
}
