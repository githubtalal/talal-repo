<?php

namespace App\Commands;

class SetCustomerLastName extends Command
{
    private string $lastName;
    const SIG = 'set-customer-last-name';
    public static function create(array $payload = []): Command
    {
        $command = new self();

        $lastName = $payload[0];

        $command->lastName = $lastName;

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'customer_last_name' => $this->lastName
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return '';
    }
}
