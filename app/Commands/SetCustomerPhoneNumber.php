<?php

namespace App\Commands;

class SetCustomerPhoneNumber extends Command
{
    const SIG = 'set-customer-phone-number';
    private string $phoneNumber;

    public static function create(array $payload = []): Command
    {
        $command = new self();
        logger($payload[0]);
        $phoneNumber = translateNumber($payload[0]);
        logger($phoneNumber);
        $command->phoneNumber = $phoneNumber;

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'customer_phone_number' => $this->phoneNumber
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return '';
    }
}
