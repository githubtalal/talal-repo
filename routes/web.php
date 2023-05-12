<?php

use Illuminate\Support\Facades\Route;

Route::get('ecart-initiative', function() {

    return view('ecart_initiative');
});


Route::get('test', function () {
    return view('test');
});
Route::get('invoice', function () {
    return view('invoice');
});

Route::get('api/init', [\App\Http\Controllers\GuestController::class, 'init']);

Route::get('invoice/pdf', [\App\Http\Controllers\Controller::class, 'export']);


Route::get('store-init', [\App\Http\Controllers\GuestController::class, 'initStore'])->name('store.init');
Route::get('checkout', [\App\Http\Controllers\GuestController::class, 'checkout'])->name('store.checkout');
Route::post('save-order', [\App\Http\Controllers\GuestController::class, 'saveOrder'])->name('store.saveOrder');
Route::get('gateway/callback', [\App\Http\Controllers\GuestController::class, 'callback'])->name('store.callback');
Route::post('gateway/callback', [\App\Http\Controllers\GuestController::class, 'callback'])->name('store.callback');
Route::post('store/add-to-cart/{product}', [\App\Http\Controllers\GuestController::class, 'addToCart'])->name('store.add_to_cart');
Route::post('store/remove-from-cart/{product}', [\App\Http\Controllers\GuestController::class, 'removeFromCart'])->name('store.remove_from_cart');
Route::get('store/remove-from-cart/{product}', [\App\Http\Controllers\GuestController::class, 'removeFromCart'])->name('store.remove_from_cart');
Route::post('contact_us', [\App\Http\Controllers\GuestController::class, 'contact'])->name('home.contact_us');


Route::get('/check-store-slug', [\App\Http\Controllers\StoreController::class, 'checkStoreSlug'])->name('store.check_slug');

Route::get('register-payment-callback', [\App\Http\Controllers\RegisterController::class, 'registerCallback'])->name('register.call-back');
Route::post('register-payment-callback', [\App\Http\Controllers\RegisterController::class, 'registerCallback'])->name('register.call-back');


Route::match(['GET', 'POST'], '/payment-processor/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment_processor.process');
Route::match(['GET', 'POST'], '/payment-processor/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment_processor.callback');
Route::match(['GET', 'POST'], '/payment-processor/redirect', [\App\Http\Controllers\PaymentController::class, 'redirect'])->name('payment_processor.redirect');

// Payment Redirects
Route::match(['GET', 'POST'], '/register/redirect', [\App\Http\Controllers\RegisterController::class, 'redirectAfterPayment'])->name('register.payment.redirect');
Route::match(['GET', 'POST'], '/guest/redirect', [\App\Http\Controllers\GuestController::class, 'redirectAfterPayment'])->name('guest.payment.redirect');


Route::get('gateway-v2/redirect', [\App\Http\Controllers\PaymentController::class, 'redirect'])->name('payment.redirect');
Route::post('gateway-v2/redirect', [\App\Http\Controllers\PaymentController::class, 'redirect'])->name('payment.redirect');

Route::get('gateway-v2/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::post('gateway-v2/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');

Route::get('register-payment-processor', [\App\Http\Controllers\SignupPaymentController::class, 'callback'])->name('payment.register.call-back');
Route::post('register-payment-processor', [\App\Http\Controllers\SignupPaymentController::class, 'callback'])->name('payment.register.call-back');

Route::get('register-payment-redirect', [\App\Http\Controllers\SignupPaymentController::class, 'redirect'])->name('payment.register.redirect');
Route::post('register-payment-redirect', [\App\Http\Controllers\SignupPaymentController::class, 'redirect'])->name('payment.register.redirect');


Route::get('/mtn-otp', function () {
    return view('mtn');
})->name('mtn.otp');
Route::get('/syriatel-otp', function () {
    return view('syriatel');
})->name('syriatel.otp');

Route::get('thanks', function () {
    $logo = __('payment_methods.payment_logos.' . 'syriatel-cash-api');
    $logo = asset($logo);
    return view('thanks', ['logo' => $logo]);
})->name('thanks');


Route::group([
    'prefix' => 'webhooks',
], function () {
    Route::get('facebook/pages', [\App\Http\Controllers\FacebookWebhookController::class, 'verify']);
    Route::post('facebook/pages', [\App\Http\Controllers\FacebookWebhookController::class, 'reply']);
    Route::get('facebook/pages-test', [\App\Http\Controllers\FacebookWebhookController::class, 'reply']);
});
Route::group([], function () {

    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/privacy-policy', [\App\Http\Controllers\HomeController::class, 'privacy'])->name('privacy');
    Route::get('/tos', [\App\Http\Controllers\HomeController::class, 'tos'])->name('tos');
    Route::get('/who-we-are', [\App\Http\Controllers\HomeController::class, 'whoWeAre'])->name('whoWeAre');


    Route::get('login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login')->middleware('guest');
    Route::post('login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login.store')->middleware('guest');

    Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'register'])->name('register');
    Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register.store');
    Route::post('/send_otp', [\App\Http\Controllers\RegisterController::class, 'send_otp'])->name('register.send_otp');
    Route::post('/validate_otp', [\App\Http\Controllers\RegisterController::class, 'validate_otp'])->name('register.validate_otp');

});
Route::group([
    'middleware' => ['auth:web', 'StoreExpiration'],
    'prefix' => 'dashboard',
], function () {

    Route::get('/', [\App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/test', [\App\Http\Controllers\HomeController::class, 'invoice']);

    /* Route::get('dashboard', function () {
        return redirect()->route('products.index');
    })->name('dashboard');*/

    Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    Route::group([
        'prefix' => 'plans',
        'as' => 'plans.',
    ], function () {
        Route::get('/', [\App\Http\Controllers\PlanController::class, 'index']);
    });

    Route::group([
        'prefix' => 'orders',
        'as' => 'orders.',
    ], function () {

        Route::get('/export', [\App\Http\Controllers\OrdersController::class, 'export'])->name('export');
        // subscriptions for admin
        Route::group([
            'prefix' => 'subscriptions',
            'as' => 'subscriptions.',
        ], function () {
            Route::get('/', [\App\Http\Controllers\OrdersController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\OrdersController::class, 'view'])->name('view');
            Route::get('/edit/{order}', [\App\Http\Controllers\OrdersController::class, 'edit'])->name('edit');
        });

        Route::get('/', [\App\Http\Controllers\OrdersController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\OrdersController::class, 'create_order'])->name('create');
        Route::post('/', [\App\Http\Controllers\OrdersController::class, 'store_order'])->name('store');
        Route::get('/{order}', [\App\Http\Controllers\OrdersController::class, 'view'])->name('view');
        Route::post('/{order}', [\App\Http\Controllers\OrdersController::class, 'update'])->name('update');
        Route::post('/update-reservation-date/{orderItem}', [\App\Http\Controllers\OrdersController::class, 'update_reservation_date'])->name('update_reservation_date');
        Route::get('/edit/{order}', [\App\Http\Controllers\OrdersController::class, 'edit'])->name('edit');
        Route::post('/update/{order}', [\App\Http\Controllers\OrdersController::class, 'update_order'])->name('update_order');
        Route::post('/store-item/{order}', [\App\Http\Controllers\OrdersController::class, 'storeItem'])->name('storeItem');
        Route::get('/delete-item/{orderItem}', [\App\Http\Controllers\OrdersController::class, 'deleteItem'])->name('deleteItem');

        Route::post('/notes/{order}', [\App\Http\Controllers\OrdersController::class, 'add_order_note'])->name('note.store');
        Route::get('/items/products', [\App\Http\Controllers\OrdersController::class, 'get_products'])->name('get_products');
        Route::get('/old-item/total', [\App\Http\Controllers\OrdersController::class, 'calculateTotalOldOrderLine'])->name('calculateTotalOldOrderLine');
        Route::get('/new-item/total', [\App\Http\Controllers\OrdersController::class, 'calculateTotalNewOrderLine'])->name('calculateTotalNewOrderLine');
        Route::get('/items/total', [\App\Http\Controllers\OrdersController::class, 'calculateOrderTotal'])->name('calculateOrderTotal');

        Route::post('/create_payment_link/{order}', [\App\Http\Controllers\OrdersController::class, 'generate_payment_link'])->name('generate_payment_link');
    });

    Route::group([
        'prefix' => 'reservations',
        'as' => 'reservations.',
    ], function () {
        Route::get('/', [\App\Http\Controllers\OrdersController::class, 'get_reservations'])->name('index');
        Route::get('/create', [\App\Http\Controllers\OrdersController::class, 'create_reservation'])->name('create');
        Route::post('/store', [\App\Http\Controllers\OrdersController::class, 'store_reservation'])->name('store');
        Route::get('/customer-details', [\App\Http\Controllers\OrdersController::class, 'get_customer_details'])->name('get_customer_details');
        // Route::get('/product-currency', [\App\Http\Controllers\OrdersController::class, 'get_currency_of_product'])->name('get_currency_of_product');
    });

    Route::group([
        'prefix' => 'products',
        'as' => 'products.'
    ], function () {
        Route::get('/', [\App\Http\Controllers\ProductsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\ProductsController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\ProductsController::class, 'store'])->name('store');
        Route::get('edit/{product}', [\App\Http\Controllers\ProductsController::class, 'edit'])->name('edit');
        Route::post('update/{product}', [\App\Http\Controllers\ProductsController::class, 'update'])->name('update');
        Route::post('update-status/{product}', [\App\Http\Controllers\ProductsController::class, 'update_status'])->name('update_status');
        Route::get('destroy/{product}', [\App\Http\Controllers\ProductsController::class, 'destroy'])->name('destroy');
        Route::post('/import', [\App\Http\Controllers\ProductsController::class, 'import'])->name('import');
        Route::get('/export', [\App\Http\Controllers\ProductsController::class, 'export'])->name('export');
    });

    Route::group([
        'prefix' => 'categories',
        'as' => 'category.'
    ], function () {
        Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\CategoryController::class, 'store'])->name('store');
        Route::get('edit/{category}', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('edit');
        Route::post('update/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('update');
        Route::get('destroy/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/import', [\App\Http\Controllers\CategoryController::class, 'import'])->name('import');
        Route::get('/export', [\App\Http\Controllers\CategoryController::class, 'export'])->name('export');
    });

    Route::get('customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/export', [\App\Http\Controllers\CustomerController::class, 'export'])->name('customers.export');

    Route::get('/report/{type}/{export}', [\App\Http\Controllers\ReportsController::class, 'get_report'])->name('report');

    Route::get('settings', [\App\Http\Controllers\StoreController::class, 'edit'])->name('settings');
    Route::post('settings/update', [\App\Http\Controllers\StoreController::class, 'update'])->name('settings.update');

    Route::get('createStoreSettings', [\App\Http\Controllers\StoreController::class, 'createStoreSettings'])->name('createStoreSettings');
    Route::post('addStoreSettings', [\App\Http\Controllers\StoreController::class, 'addStoreSettings'])->name('addStoreSettings');

    Route::group([
        'prefix' => 'custom-settings',
        'as' => 'custom-settings.'], function () {
        Route::get('/create', [\App\Http\Controllers\StoreController::class, 'createMySettings'])->name('create');
        Route::post('/store', [\App\Http\Controllers\StoreController::class, 'storeMySettings'])->name('store');
    });

    Route::get('bot-settings/create', [\App\Http\Controllers\StoreController::class, 'create_bot_settings'])->name('create_bot_settings');
    Route::post('bot-settings/add', [\App\Http\Controllers\StoreController::class, 'add_bot_settings'])->name('add_bot_settings');

    Route::get('work_hours', [\App\Http\Controllers\StoreController::class, 'work_hours'])->name('work_hours');
    Route::post('/store', [\App\Http\Controllers\StoreController::class, 'storeWorkHours'])->name('storeWorkHours');

    Route::get('FAQ', [\App\Http\Controllers\StoreController::class, 'FAQ'])->name('FAQ');
    Route::get('contact-us', [\App\Http\Controllers\StoreController::class, 'contact_us'])->name('contact_us');
    Route::get('about-us', [\App\Http\Controllers\StoreController::class, 'about_us'])->name('about_us');

    Route::post('/store-question', [\App\Http\Controllers\StoreController::class, 'store_question'])->name('store_question');
    Route::post('/store-contact-us', [\App\Http\Controllers\StoreController::class, 'store_contact_us'])->name('store_contact_us');
    Route::post('/store-about-us', [\App\Http\Controllers\StoreController::class, 'store_about_us'])->name('store_about_us');


    // protected by super admin
    Route::group([
        'middleware' => ['role:super-admin'],

    ], function () {
    Route::get('stores', [\App\Http\Controllers\StoreController::class, 'index'])->name('stores.index');
    Route::get('stores/edit/{store}', [\App\Http\Controllers\StoreController::class, 'edit_subscribtion_setting'])->name('stores.edit_subscribtion_setting');
    Route::post('stores/update/{store}', [\App\Http\Controllers\StoreController::class, 'update_subscribtion_setting'])->name('stores.update_subscribtion_setting');
    Route::get('reset/{store}', [\App\Http\Controllers\StoreController::class, 'resetStore'])->name('stores.reset');
    });



    Route::group([
        'prefix' => 'account-settings',
        'as' => 'accountSettings.'
    ], function () {
        Route::get('/', [\App\Http\Controllers\StoreController::class, 'accountSettings'])->name('view');
        Route::post('/store', [\App\Http\Controllers\StoreController::class, 'store_account_settings'])->name('store');
    });

    Route::group([
        'prefix' => 'button-settings',
        'as' => 'buttonSettings.'
    ], function () {
        Route::get('/', [\App\Http\Controllers\StoreController::class, 'buttonSettings'])->name('view');
        Route::post('/store', [\App\Http\Controllers\StoreController::class, 'store_button_settings'])->name('store');
    });

    Route::group([
        'prefix' => 'bot',
        'as' => 'bot.'
    ], function () {

        Route::group([
            'prefix' => 'messenger',
            'as' => 'messenger.'
        ], function () {
            Route::get('/', [\App\Http\Controllers\MessengerController::class, 'view'])->name('view');
            Route::post('/create', [\App\Http\Controllers\MessengerController::class, 'create'])->name('create');
            Route::post('pages', [\App\Http\Controllers\MessengerController::class, 'setPages'])->name('set_pages');
            Route::get('facebook/list-pages', [\App\Http\Controllers\MessengerController::class, 'listPages'])->name('list_pages');
        });

        Route::group([
            'prefix' => 'telegram',
            'as' => 'telegram.'
        ], function () {
            Route::get('/', [\App\Http\Controllers\TelegramController::class, 'view'])->name('view');
            Route::post('/', [\App\Http\Controllers\TelegramController::class, 'update'])->name('update');
            Route::post('/broadcast', [\App\Http\Controllers\TelegramController::class, 'broadcast'])->name('broadcast');
            Route::get('/broadcast_form', [\App\Http\Controllers\TelegramController::class, 'broadcast_form'])->name('broadcast_form');

            // protected by super admin
            Route::group([
                'middleware' => ['role:super-admin'],

            ], function () {

                Route::get('/promotion_messages', [\App\Http\Controllers\PromotionMessageController::class, 'view'])->name('promotion_messages');
            });

        });
    });

    Route::group([
        'prefix' => 'notifications',
        'as' => 'notifications.',
        'middleware' => ['role:super-admin'],
    ], function () {
        Route::get('/send_notification', [\App\Http\Controllers\NotificationController::class, 'send_notification_form'])->name('send_notification_form');
        Route::post('/send_notification', [\App\Http\Controllers\NotificationController::class, 'send_notification'])->name('send_notification');
        Route::get('/get_notifiable_stores', [\App\Http\Controllers\NotificationController::class, 'get_notifiable_stores'])->name('get_notifiable_stores');

    });

    Route::group([
        'prefix' => 'notifications',
        'as' => 'notifications.',
    ], function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'view_all_notifications'])->name('view_all_notifications');
        Route::get('/show_notification/{id}', [\App\Http\Controllers\NotificationController::class, 'show_notification'])->name('show_notification');
    });

});

Route::group([
    'middleware' => ['auth:web'],
    'prefix' => 'dashboard',
], function () {

    Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    Route::group([
        'prefix' => 'resubscription',
        'as' => 'resubscription.',
    ], function () {
        Route::get('/', [\App\Http\Controllers\ResubscriptionController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\ResubscriptionController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\ResubscriptionController::class, 'store'])->name('store');
        Route::match(['GET', 'POST'], '/guest/redirect', [\App\Http\Controllers\ResubscriptionController::class, 'redirectAfterPayment'])->name('payment.redirect');

    });
});

Route::get('ecart-store/{slug}', [\App\Http\Controllers\GuestController::class, 'storeBySlug'])->name('store.slug.old');
Route::get('ecart-store/{slug}/{vue_router?}', [\App\Http\Controllers\GuestController::class, 'storeBySlug'])->name('store.slug.old');
Route::get('/{slug}', [\App\Http\Controllers\GuestController::class, 'storeBySlug'])->name('store.slug');
Route::get('{slug}/{vue_router?}', [\App\Http\Controllers\GuestController::class, 'storeBySlug'])->where('vue_router', '.*');

