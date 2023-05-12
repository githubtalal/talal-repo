<?php

namespace App\Commands;

class RemoveItemFromCart extends Command
{
    const ITEM_ID = 'item_id';
    const SIG = 'remove-from-cart';

    private $itemId;

    public static function create(array $payload = []): self
    {
        $instance = new self();

        if (!isset($payload[0]))
            throw new \Exception('Payload is not completed');
        $instance->itemId = $payload[0];
        return $instance;
    }

    public function run()
    {
        $this->cartRepository->removeFromCart($this->itemId);
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::ITEM_ID]))
            throw new \Exception('Missing item id');
        return self::SIG . '_' . $properties[self::ITEM_ID];
    }

    public function getItemId()
    {
        return $this->itemId;
    }
}
