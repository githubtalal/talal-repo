<?php

namespace App\Commands;

use App\Facebook\Messages\QuickReplies\Text;
use App\Models\Category;

class ListCategories extends Command
{
    const SIG = 'list-categories';
    //    const PER_PAGE = 11;
    const PER_PAGE = 4;
    const PARENT_ID = 'parent_id';
    const PAGE = 'page';

    const DEFAULT_PAGE = 1;
    private $page;
    private $parentId;
    private $categories;

    public static function create(array $payload = []): self
    {
        // [PARENT, PAGE]
        $instance = new self();

        $instance->page = $payload[0] ?? self::DEFAULT_PAGE;

        $instance->parentId = $payload[1] ?? null;


        return $instance;
    }

    public static function buildPayload(array $properties = []): string
    {
        $page = $properties[self::PAGE] ?? self::DEFAULT_PAGE;
        $parent_id = $properties[self::PARENT_ID] ?? null;

        $payload = self::SIG;

        $payload .= '_' . $page;

        if ($parent_id)
            $payload .= '_' . $parent_id;

        return $payload;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function run()
    {
        $this->categories = $this->categoryRepository
            ->skip(($this->page - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get();
    }

    public function hasNextPage(): bool
    {
        return $this->categoryRepository->count() > ($this->page * self::PER_PAGE);
    }

    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    public function getLastPage(): int
    {
        return ceil($this->categoryRepository->count() / self::PER_PAGE);
    }

    public function getCurrentPage(): int
    {
        return $this->page;
    }
}
