<?php


return [
    'payment_methods' => [
        \App\PaymentMethods\Fatora::class => 'شبكة فاتورة',
        \App\PaymentMethods\MtnCash::class => ' (MTN) كاش موبايل',
        \App\PaymentMethods\MtnCashApi::class => 'بوابة دفع أونلاين - (MTN) كاش موبايل',
        \App\PaymentMethods\BemoBank::class => 'بنك بيمو',
        \App\PaymentMethods\CashonDelivery::class => 'الدفع عند التوصيل',
        \App\PaymentMethods\Haram::class => 'الهرم للحوالات المالية',
        \App\PaymentMethods\SyriatelCash::class => 'سيرياتيل كاش',
        \App\PaymentMethods\SyriatelCashApi::class => 'بوابة دفع أونلاين - سيرياتيل كاش',
        \App\PaymentMethods\Fouad::class => 'الفؤاد للحوالات المالية',
        \App\PaymentMethods\EcashQr::class => 'eCash - QR',
        \App\PaymentMethods\EcashCard::class => 'eCash - Card',
        'fatora' => 'شبكة فاتورة',
        'mtn_cash' => ' (MTN) كاش موبايل',
        'mtn-cash-api' => ' (MTN) كاش موبايل',
        'bbsf' => 'بنك بيمو',
        'cod' => 'الدفع عند التوصيل',
        'haram' => 'الهرم للحوالات المالية',
        'syriatel_cash' => 'سيرياتيل كاش',
        'syriatel-cash-api' => 'سيرياتيل كاش',
        'fouad' => 'الفؤاد للحوالات المالية',
        'ecash-qr' => 'eCash - QR',
        'ecash-card' => 'eCash - Card',
    ],
    'payment_logos' => [
        'mtn-cash-api' => 'Baseet/images/MTN_Group_Logo.png',
        'syriatel-cash-api' => 'Baseet/images/Syriatel.png',
        'fatora' => 'Baseet/images/fatora-logo.png',
    ],
    'fields' => [
        'username' => 'اسم المستخدم',
        'password' => 'كلمة المرور',
        'address' => 'العنوان',
        'terminal_id' => 'terminal id',
        'phone number' => 'رقم الهاتف',
        'api_key' => 'api_key',
        'user_name' => 'أسم المستخدم',
        'merchant_gsm' => 'رقم التاجر',
        'notification_number' => 'الرقم الذي سيتم ارسال الاشعار عليه (واتس اب)',
    ],
    'labels' => [
        'fatora' => 'شبكة فاتورة',
        'mtn_cash' => ' (MTN) كاش موبايل',
        'bbsf' => 'بنك بيمو',
        'cod' => 'الدفع عند التوصيل',
        'haram' => 'الهرم للحوالات المالية',
        'syriatel_cash' => 'سيريتل كاش - Syriatel Cash',
        'fouad' => 'الفؤاد للحوالات المالية',
    ],
];