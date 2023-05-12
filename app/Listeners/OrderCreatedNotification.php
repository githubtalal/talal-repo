<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedNotification
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
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // send notification to the store owner
        $notifiable = $order->store->user->first();

        $notifiable->notify(new \App\Notifications\OrderCreatedNotification(['text' => __('app.notification.order_created_notification') . ' (' . $order->id . '#)', 'url' => route('orders.view', $order)]));
    }
}
