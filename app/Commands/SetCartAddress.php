<?php

namespace App\Commands;

class SetCartAddress extends Command
{

    private string $address;
    const SIG = 'set-cart-address';
    public static function create(array $payload = []): Command
    {
        $command = new self();

        $address = $payload[0];

        $command->address = $address;

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'customer_address' => $this->address
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return '';
    }
}
