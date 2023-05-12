<?php

namespace App\Commands;

class SetCustomerGovernorate extends Command
{
    const GOVERNORATE = 'governorate';
    const SIG = 'set-customer-governorate';

    protected $governorate;

    public static function create(array $payload = []): Command
    {
        $command = new self();

        $command->governorate = $payload[0];

        return $command;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'customer_governorate' => $this->governorate,
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::GOVERNORATE])) {
            throw new \InvalidArgumentException('Missing property: ' . self::GOVERNORATE);
        }

        return self::SIG . '_' . $properties[self::GOVERNORATE];

    }
}
