<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;

class RemoteItemFromCart extends Response
{

    public static function create($command): Response
    {
        $response = new self();

        $response->responses = __('responses.cart.removed_from_cart');

        return $response;
    }
}
