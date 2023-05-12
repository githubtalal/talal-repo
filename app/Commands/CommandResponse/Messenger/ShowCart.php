<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\Checkout;
use App\Commands\CommandResponse\Response;
use App\Commands\RemoveItemFromCart;
use App\Commands\ShowCart as ShowCartCommand;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\Messages\Templates\ButtonTemplate;
use App\Facebook\Messages\Templates\CarouselTemplate;
use App\Facebook\Messages\Templates\GenericTemplate;
use Illuminate\Support\Facades\Storage;

class ShowCart extends Response
{

    /**
     * @param ShowCartCommand $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();
        $final_response = [];

        // update cart
        $updated = $command->updateCart();

        if (count($updated['deleted_products']) > 0) {
            $final_response[] = __('responses.cart.update_cart');
            foreach ($updated['deleted_products'] as $product) {
                $final_response[] = $product->name;
            }
        }

        if (count($updated['updated_products']) > 0) {
            $final_response[] = __('responses.cart.update_cart_items_price');
            foreach ($updated['updated_products'] as $product) {
                $final_response[] = $product->name;
            }
        }

        // refresh the cart and check it after updating
        $cart = $command->getCart();
        if ($cart && $cart->items()->count()) {

            $carousel = CarouselTemplate::create();

            foreach ($cart->items as $item) {
                $carItemTemplate = GenericTemplate::create();
                $removeFromCartButton = PostbackButton::create();

                $removeFromCartButton->setTitle(__('responses.cart.remove_from_cart'))
                    ->setPayload(RemoveItemFromCart::buildPayload([
                        RemoveItemFromCart::ITEM_ID => $item->id,
                    ]));

                $carItemTemplate->setTitle($item->product->name . ' - ' . price_format($item->price, __('app.currency_types.' . $item->product->currency)))
                    ->setImage(Storage::disk('public')->url($item->product->image_url))
                    ->setSubtitle($item->product->name)
                    ->addButton($removeFromCartButton);

                $carousel->addGenericTemplate($carItemTemplate);
            }

            $checkoutTemplate = ButtonTemplate::create();

            $cartResult = \App\Http\Resources\Cart::make($cart);
            $cartResult = $cartResult->toArray(request());

            $checkoutTemplate
                ->editText(__('responses.cart.summary',[
                    'count' => count($cart->items),
                    'total' => price_format($cart->total, __('app.currency_types.' . $cart->currency)),
                    'total_tax' => $cartResult['formatted_tax_total'] ?? '',
                    'total_fees' => $cartResult['formatted_fees_total'] ?? '',
                    'subtotal' => $cartResult['formatted_sub_total'] ?? '',
                ]))
                ->addButton(
                    PostbackButton::create()
                        ->setTitle(__('responses.cart.checkout'))
                        ->setPayload(Checkout::buildPayload())
                )
                ->addButton(
                    PostbackButton::create()
                        ->setTitle(__('responses.cart.delete_cart'))
                        ->setPayload(\App\Commands\DeleteCart::buildPayload())
                )
                ->addButton(
                    PostbackButton::create()
                        ->setTitle(__('responses.categories.return_to_categories'))
                        ->setPayload(\App\Commands\ListCategories::buildPayload())
                );

            $final_response = array_merge($final_response, [
                $carousel,
                $checkoutTemplate,
            ]);
        } else {
            $emptyCartTemplate = ButtonTemplate::create();

            $emptyCartTemplate
                ->editText(__('responses.cart.empty_cart'))
                ->addButton(
                    PostbackButton::create()
                        ->setTitle(__('responses.categories.return_to_categories'))
                        ->setPayload(\App\Commands\ListCategories::buildPayload())
                );
            $final_response[] = $emptyCartTemplate;
        }

        $response->responses = $final_response;

        return $response;
    }
}
