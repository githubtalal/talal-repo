<?php

namespace App\PaymentMethods;

class EcashQr extends Ecash
{

    function getCheckoutType()
    {
        return 'QR';
    }

    public function getKey(): string
    {
        return 'ecash-qr';
    }
}
