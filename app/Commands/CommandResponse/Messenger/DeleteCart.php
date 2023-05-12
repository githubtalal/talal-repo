<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Commands\DeleteCart as DeleteCartCommand;

class DeleteCart extends Response
{

    /**
     * @param DeleteCartCommand $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();

        $response->responses = __('responses.cart.deleted_cart');

        return $response;
    }
}
