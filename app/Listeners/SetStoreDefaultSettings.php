<?php

namespace App\Listeners;

use App\Models\StoreSettings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetStoreDefaultSettings
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
        $store = $event->store;

        $paymentMethods = [
            'cod' => [
                'enabled' => true,
            ],
        ];

        StoreSettings::create([
            'key' => 'payment_method',
            'value' => $paymentMethods,
            'store_id' => $store->id,
        ]);

        // Checkout flow
        StoreSettings::create([
            "key" => "bot_settings",
            "value" => [
                'steps' => [
                    'governorate' => 'enabled',
                    'address' => 'enabled',
                    'notes' => 'enabled',
                ],
                'hour' => 'enabled',
                'power_button' => 'enabled',
            ],
            "store_id" => $store->id,
        ]);
    }
}
