<?php

namespace App\Commands;

class SetCartFirstName extends Command
{
    private string $firstName;
    private string $lastName;
    const SIG = 'set-cart-first-name';
    public static function create(array $payload = []): Command
    {
        $command = new self();

        $name = $payload[0];

        $name = explode(' ', $name);
        $firstName = $name[0];
        array_shift($name);
        $lastName = implode(' ', $name);

        $command->firstName = $firstName;
        $command->lastName = $lastName;

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'customer_first_name' => $this->firstName,
            'customer_last_name' => $this->lastName
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return '';
    }
}
