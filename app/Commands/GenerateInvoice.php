<?php

namespace App\Commands;

use App\Models\Order;

class GenerateInvoice extends Command
{
    const SIG = 'generate-invoice';
    const ORDER_INDEX = 'order_index';
    protected $orderID;

    public static function create(array $payload = []): Command
    {
        $command = new self();
        $command->orderID = $payload[0];

        return $command;
    }

    public function run()
    {
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::ORDER_INDEX]))
            throw new \Exception('Missing ORDER_INDEX property');

        $order_index = $properties[self::ORDER_INDEX];

        return self::SIG . '_' . $order_index;
    }

    public function getOrder()
    {
        $order = Order::where('id', $this->orderID)->first();
        return $order;
    }
}
