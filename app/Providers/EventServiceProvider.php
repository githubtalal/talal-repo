<?php

namespace App\Providers;

use App\Events\ClaimEvent;
use App\Events\OrderCanceled;
use App\Events\OrderConfirmed;
use App\Events\OrderCreated;
use App\Events\PaymentConfirmed;
use App\Events\StoreCreated;
use App\Events\SubscriptionConfirmed;
use App\Events\SubscriptionCreated;
use App\Listeners\AfterPaymentConfirmation;
use App\Listeners\CancelSubscription;
use App\Listeners\ClaimListener;
use App\Listeners\ConfirmSubscription;
use App\Listeners\CreateStore;
use App\Listeners\CreateSubscription;
use App\Listeners\OrderCreatedNotification;
use App\Listeners\SendChannelsMessages;
use App\Listeners\SetStoreDefaultSettings;
use App\Listeners\SyncStorePermissionsWithSubscription;
use App\Models\Order;
use App\Models\Product;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SendChannelsMessages::class,
            CreateStore::class,
            OrderCreatedNotification::class,
        ],
        OrderConfirmed::class => [
            ConfirmSubscription::class,
        ],
        OrderCanceled::class => [
            CancelSubscription::class,
        ],
        StoreCreated::class => [
            CreateSubscription::class,
            SetStoreDefaultSettings::class
        ],
        SubscriptionCreated::class => [
        ],
        SubscriptionConfirmed::class => [
            SyncStorePermissionsWithSubscription::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
