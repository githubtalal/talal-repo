<?php

namespace App\Commands;

class GetDescription extends Command
{
    const SIG = 'get-description';
    const PRODUCT_ID = 'product_id';

    protected $product;

    public static function create(array $payload = []): Command
    {
        $command = new self();
        $command->product = $payload[0];

        return $command;
    }

    public function run()
    {
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::PRODUCT_ID]))
            throw new \Exception('Missing PRODUCT_ID property');

        $product = $properties[self::PRODUCT_ID];

        return self::SIG . '_' . $product;
    }

    public function get_description()
    {
        $product = $this->productRepository->where('id', $this->product)->first();
        $description = '';
        if ($product->description) {
            $description = html_entity_decode(strip_tags(str_replace('<br>', chr(10), $product->description)));
        }
        return $description;
    }
}
