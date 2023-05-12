<?php

namespace App\PaymentMethods;

use App\Models\Cart;

class CashonDelivery extends PaymentMethod
{
    protected $label;

    public function getKey(): string
    {
        return 'cod';
    }

    public function setLabel($label)
    {
        return $this->label = $label;
    }

    public function getLabel(): string
    {
        if (!$this->label)
            $this->label = "الدفع عند التوصيل";

        return $this->label;
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
    }
}
