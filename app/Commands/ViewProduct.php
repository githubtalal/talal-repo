<?php

namespace App\Commands;

use App\Models\Product;
use Illuminate\Support\Arr;

class ViewProduct extends Command
{
    const SIG = 'view-product';
    const PRODUCT_ID = 'product_id';

    private $productId;
    private $product;


    public static function create(array $payload = []): self
    {
        // Product id is not difined
        if (!isset($payload[0]))
            throw new \Exception('Product Id is not defined');

        $instance = new self();

        $instance->productId = $payload[0];

        return $instance;
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::PRODUCT_ID]))
            throw new \Exception('Product Id is not defined');
        return self::SIG . '_' . $properties[self::PRODUCT_ID];

    }

    public function run()
    {
        $this->product = $this->productRepository
            ->find($this->productId);
    }

    public function getProduct()
    {
        return $this->product;
    }


}
