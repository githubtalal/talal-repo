<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\AddToCart;
use App\Commands\CommandResponse\Response;
use App\Commands\GetDescription;
use App\Commands\ListCategories;
use App\Commands\ListCategoryProducts;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\Messages\Templates\ButtonTemplate;
use App\Facebook\Messages\Templates\CarouselTemplate;
use App\Facebook\Messages\Templates\GenericTemplate;
use Illuminate\Support\Facades\Storage;

class ListCategoriesProducts extends Response
{

    /**
     * @param ListCategoryProducts $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();

        $productsCarousel = CarouselTemplate::create();

        if (count($command->getProducts()) == 0) {
            $response->responses = __('responses.products.empty_content');
            return $response;
        }

        foreach ($command->getProducts() as $product) {
            $productTemplate = GenericTemplate::create();

            if ($product->description) {
                $sendDescription = PostbackButton::create();

                $sendDescription->setTitle(__('responses.products.description'))
                    ->setPayload(GetDescription::buildPayload([
                        GetDescription::PRODUCT_ID => $product->id,
                    ]));

                $description = preg_replace('/(<\/p>|<\/h1>|<\/h2>)/', "$1\n", $product->description);
                $formattedDescription = html_entity_decode(strip_tags($description));

                $productTemplate
                    ->setSubtitle($formattedDescription)
                    ->addButton($sendDescription);
            }

            $addToCartButton = PostbackButton::create();
            $addToCartButton->setTitle(__('responses.cart.add_to_cart'))
                ->setPayload(AddToCart::buildPayload([
                    AddToCart::PRODUCT_ID => $product->id,
                ]));

            $productTemplate->setTitle($product->name . ' - ' . price_format($product->price, __('app.currency_types.' . $product->currency)))
                ->setImage(Storage::disk('public')->url($product->image_url))
                ->addButton($addToCartButton);

            $productsCarousel->addGenericTemplate($productTemplate);
        }

        if ($command->hasNextPage() || $command->hasPreviousPage()) {

            //            if ($command->hasPreviousPage()) {
            //                $paginationTemplate->addButton(
            //                    PostbackButton::create()
            //                        ->setTitle(__('responses.categories.previous'))
            //                        ->setPayload(ListCategoryProducts::buildPayload([
            //                            ListCategoryProducts::PAGE => $command->getCurrentPage() - 1,
            //                            ListCategoryProducts::CATEGORY_ID => $command->getCategoryId(),
            //                        ]))
            //                );
            //            }

            if ($command->hasNextPage()) {
                $paginationTemplate = GenericTemplate::create();
                $paginationTemplate->setTitle(__('responses.categories.next_title'));
                $paginationTemplate->addButton(
                    PostbackButton::create()
                        ->setTitle(__('responses.categories.next'))
                        ->setPayload(ListCategoryProducts::buildPayload([
                            ListCategoryProducts::PAGE => $command->getCurrentPage() + 1,
                            ListCategoryProducts::CATEGORY_ID => $command->getCategoryId(),
                        ]))
                );
                $productsCarousel->addGenericTemplate($paginationTemplate);
            }
        }

        $buttonTemplate = ButtonTemplate::create();
        $buttonTemplate->editText(__('responses.cart.actions'));

        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.categories.return_to_categories'))->setPayload(ListCategories::buildPayload()));
        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.cart'))->setPayload(\App\Commands\ShowCart::buildPayload()));
        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.checkout'))->setPayload(\App\Commands\Checkout::buildPayload()));
        $response->responses = [$productsCarousel, $buttonTemplate];

        return $response;
    }
}
