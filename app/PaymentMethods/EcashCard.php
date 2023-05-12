<?php

namespace App\PaymentMethods;

class EcashCard extends Ecash
{

    function getCheckoutType()
    {
        return 'Card';
    }

    public function getKey(): string
    {
        return 'ecash-card';
    }
}
