<?php

namespace App\Commands;

use App\Models\Product;
use Illuminate\Support\Arr;

class ListCategoryProducts extends Command
{

    const SIG = 'list-category-products';
    const CATEGORY_ID = 'category_id';
    const PAGE = 'page';
    const PER_PAGE = 3;

    private $page;
    private $categoryId;
    private $products;


    public static function create(array $payload = []): self
    {
        // Example: [CategoryId, PAGE};
        $instance = new self();

        $instance->categoryId = $payload[0] ?? null;

        $instance->page = $payload[1] ?? self::DEFAULT_PAGE;

        return $instance;
    }

    public function run()
    {
        $this->products = $this->productRepository->where([
            'category_id' => $this->categoryId,
        ])
            ->skip(($this->page - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get();
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::CATEGORY_ID]))
            throw new \Exception('Missing category id property');

        $categoryId = $properties[self::CATEGORY_ID];
        $page = $properties[self::PAGE] ?? self::DEFAULT_PAGE;

        return self::SIG . '_' . $categoryId . '_' . $page;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function hasNextPage()
    {
        return $this->productRepository->where([
            'category_id' => $this->categoryId,
        ])->count() > ($this->page * self::PER_PAGE);
    }

    public function hasPreviousPage()
    {
        return $this->page > 1;
    }

    public function getCurrentPage()
    {
        return $this->page;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }
}
