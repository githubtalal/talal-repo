<?php

namespace App;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ConfirmOrder
{
    protected $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle()
    {
        if ($this->subscription->approved_at) {
            return;
        }

        $order = Order::where('id', $this->subscription->order_id)->first();

        $store = Store::where('id', $this->subscription->store_id)->first();

        $user = $store->user()->first();

        $orderItems = $order->items()->pluck('product_id')->toArray();

        $neededProducts = Product::withoutGlobalScope('store_access')
            ->whereIn('id', $orderItems)->get();

        $neededServices = [];
        foreach ($neededProducts as $product) {
            $neededServices[] = [
                'id' => $product->id,
                'name' => $product->additional['permission_name'],
            ];
        }

        foreach ($neededServices as $service) {
            $permission = Permission::where('name', $service['name'])->first();

            if (!$user->hasPermissionTo($service['name'])) {
                $user->givePermissionTo($service['name']);
                $newExpiresAt = now()->addMonth();
            } else {
                $model_has_permission = DB::table('model_has_permissions')
                    ->where([
                        ['model_id', $user->id],
                        ['permission_id', $permission->id],
                    ])->first();

                if (!$model_has_permission->expires_at) {
                    // in register case the expires_at will be null
                    $newExpiresAt = now()->addMonth();
                } else {
                    $expiresAt = Carbon::parse($model_has_permission->expires_at);

                    if ($expiresAt->lte(now())) {
                        $newExpiresAt = now()->addMonth();
                    } elseif ($expiresAt->gt(now())) {
                        $newExpiresAt = $expiresAt->addMonth();
                    }
                }
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
        $store->update(['is_active' => true]);
        $this->subscription->update(['approved_at' => now()]);
    }
}
