<?php

namespace App\Listeners;

use App\Events\SubscriptionConfirmed;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConfirmSubscription
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;

        $subscription = Subscription::query()
            ->where('order_id', $order->id)
            ->orWhere('payment_ref', $order->payment_ref)
            ->first();

        if ($subscription) {
            $subscription->update([
                'approved_at' => now(),
                'order_id' => $order->id,
            ]);
            SubscriptionConfirmed::dispatch($subscription);
        }

    }
}
