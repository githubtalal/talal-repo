<?php

namespace App\Commands;

class ShowCart extends Command
{

    const SIG = 'view-cart';


    public static function create(array $payload = []): self
    {
        return new self();
    }

    public function run()
    {
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }

    public function getCart()
    {
        return $this->cartRepository->getCart();
    }

    public function updateCart()
    {
        return $this->cartRepository->updateCart();
    }
}
