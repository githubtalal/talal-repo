<?php

namespace App\PaymentMethods;

use App\Models\Cart;

class BemoBank extends PaymentMethod
{
    protected $label = 'Bemo Bank';
    public function getKey(): string
    {
        return 'bbsf';
    }

    public function parseResponse($data): PaymentMethod
    {
        return $this;
    }

    public function isPaymentSucceeded(): bool
    {
        return true;
    }

    public function getPaymentInfo(): array
    {
        return [];
    }

    public function getBasicInfo(): array
    {
        return [];
//        return [
//            [
//                'name' => 'api_key',
//                'type' => 'text',
//                'autocomplete' => 'false'
//            ]
//        ];
    }
}
