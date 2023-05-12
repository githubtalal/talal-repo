<?php

namespace App\Commands;

use App\Cart;
use App\Models\Category;
use App\Models\Product;

abstract class Command
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_PER_PAGE = 8;

    protected $cartRepository;
    protected $productRepository;
    protected $categoryRepository;

    public function __construct()
    {
        $this->cartRepository = new Cart();
        $this->productRepository = Product::query()->where([['store_id', request('store_id')], ['active', 1]]);
        $this->categoryRepository = Category::query()->where([['store_id', request('store_id')], ['active', 1]]);
    }

    abstract public static function create(array $payload = []): self;

    abstract public function run();


    abstract public static function buildPayload(array $properties = []): string;
}
