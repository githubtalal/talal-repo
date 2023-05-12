<?php

namespace App\Commands;

use Illuminate\Support\Str;

class SetCartPaymentMethod extends Command
{
    const METHOD_CODE = 'method_code';
    const SIG = 'set-cart-payment-method';

    private $paymentMethod;

    public static function create(array $payload = []): Command
    {
        $command = new self();

        $paymentMethod = $payload[0];

        $command->paymentMethod = $paymentMethod;

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'payment_method' => $this->paymentMethod,
            'payment_ref' => Str::random(),
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::METHOD_CODE])) {
            throw new \InvalidArgumentException('Missing property: ' . self::METHOD_CODE);
        }
        return self::SIG . '_' . $properties[self::METHOD_CODE];
    }

    public function getCart()
    {
        return $this->cartRepository->getCart();
    }
}
