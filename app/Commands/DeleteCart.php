<?php

namespace App\Commands;

class DeleteCart extends Command
{
    const SIG = 'delete-cart';


    public static function create(array $payload = []): self
    {
        return new self();
    }

    public function run()
    {
        $this->cartRepository->deleteCart();
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }
}
