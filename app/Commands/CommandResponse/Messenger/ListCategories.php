<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandParser;
use App\Commands\CommandResponse\CommandResponse;
use App\Commands\CommandResponse\Response;
use App\Commands\ListCategories as ListCategoriesCommand;
use App\Commands\ListCategoryProducts;
use App\Facebook\Messages\QuickReplies\Text as TextQuickReply;

class ListCategories extends Response
{

    /**
     * @param ListCategoriesCommand $command
     * @return static
     */
    public static function create($command): self
    {
        $response = new self();

        $categories = TextQuickReply::create();

        // TODO: Handle numbers, and ask Anas is that ok ?
        $categories->setText(__('responses.categories.categories_from_to', [
            'from' => 1,
            'to' => 10,
        ]));

        if (count($command->getCategories()) == 1 && $command->getCurrentPage() == 1) {

            $payload = ListCategoryProducts::buildPayload([
                ListCategoryProducts::CATEGORY_ID => $command->getCategories()[0]->id,
            ]);

            $command = CommandParser::parsePayload($payload);
            $command->run();

            $response->responses = CommandResponse::create($command)->getResponse();

            return $response;
        }

        foreach ($command->getCategories() as $category) {
            // TODO: Handle if the category has children
            $categories->add($category->name, ListCategoryProducts::buildPayload([
                ListCategoryProducts::CATEGORY_ID => $category->id,
            ]));
        }

        if ($command->hasPreviousPage()) {
            $categories->add(__('responses.categories.previous'), ListCategoriesCommand::buildPayload([
                ListCategoriesCommand::PAGE => $command->getCurrentPage() - 1,
            ]));
        }

        if ($command->hasNextPage()) {
            $categories->add(__('responses.categories.next'), ListCategoriesCommand::buildPayload([
                ListCategoriesCommand::PAGE => $command->getCurrentPage() + 1,
            ]));
        }

        $response->responses = $categories;
        return $response;
    }
}
