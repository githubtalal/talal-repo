<?php

namespace App\Listeners;

use App\Events\SubscriptionCreated;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateSubscription
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
        $store = $event->store;
        $orderId = $store->order_id;
        $user = $store->user->first();

        $order = Order::query()->find($orderId);
        $subscription = Subscription::create([
            'order_id' => $orderId,
            'store_id' => $store->id,
            'payment_ref' => $order->payment_ref,
        ]);

        SubscriptionCreated::dispatch($subscription);
    }
}
