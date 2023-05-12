<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;


class Checkout extends Response
{
    use Messenger;

    protected bool $expectReply = true;
    protected $expectedReply = \App\Commands\SetCartFirstName::class;

    /**
     * @param \App\Commands\Checkout $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new static();
        $final_response = [];

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

        // we need to check the cart after updating
        if ($command->hasProducts()) {
            $final_response[] = __('responses.checkout.enter_complelete_name');
        } else {
            $final_response[] = __('responses.cart.empty_cart');
            $response->expectReply = false;
        }

        $response->responses = $final_response;
        return $response;
    }
}
