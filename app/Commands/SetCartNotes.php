<?php

namespace App\Commands;

class SetCartNotes extends Command
{
    private $notes;
    const SIG = 'set-cart-notes';

    public static function create(array $payload = []): Command
    {
        $instance = new self();
        $instance->notes = $payload[0] ?? '';

        return $instance;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'notes' => $this->notes,
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }
}
