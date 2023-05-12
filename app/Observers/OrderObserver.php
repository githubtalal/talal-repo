<?php

namespace App\Observers;

use App\Models\BotMessages;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $storedMessage = BotMessages::where([
            'platform' => 'Telegram',
            'recipient_id' => $order->customer->uuid,
        ])->first();

        if ($storedMessage) {
            $storedMessage->additional = json_encode([
                'current_state' => '',
                'category_id' => '',
                'product_id' => '',
                'first_name' => '',
                'last_name' => '',
                'city' => '',
                'address' => '',
                'phone' => '',
                'notes' => '',
                'additional_question1' => '',
                'additional_question2' => '',
                'year_in' => '',
                'month_in' => '',
                'day_in' => '',
                'time_in' => '',
                'year_out' => '',
                'month_out' => '',
                'day_out' => '',
            ]);
            $storedMessage->save();
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
