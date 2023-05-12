<?php

namespace App\Commands;

class Checkout extends Command
{
    const SIG = 'checkout';


    public static function create(array $payload = []): self
    {
        return new self();
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }

    public function hasProducts(): bool
    {
        $cart = $this->cartRepository->getCart();
        return $cart && count($cart->items) > 0;
    }

    public function updateCart()
    {
        return $this->cartRepository->updateCart();
    }
}
