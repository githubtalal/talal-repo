<?php


return [
    'fatora' => \App\PaymentMethods\Fatora::class,
    'cod' => \App\PaymentMethods\CashonDelivery::class,
//    'bbsf' => \App\PaymentMethods\BemoBank::class,
    'mtn_cash' => \App\PaymentMethods\MtnCash::class,
    'mtn-cash-api' => \App\PaymentMethods\MtnCashApi::class,
    'syriatel_cash' => \App\PaymentMethods\SyriatelCash::class,
    'syriatel-cash-api' => \App\PaymentMethods\SyriatelCashApi::class,
    'syriatel' => \App\PaymentMethods\SyriatelCash::class,
    'haram' => \App\PaymentMethods\Haram::class,
    'fouad' => \App\PaymentMethods\Fouad::class,
    'ecash-card' => \App\PaymentMethods\EcashCard::class,
//    'ecash-qr' => \App\PaymentMethods\EcashQr::class
];
