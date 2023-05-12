<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Commands\SetCustomerPhoneNumber;

class SetCartFirstName extends Response
{
    protected bool $expectReply = true;
    protected $expectedReply = SetCustomerPhoneNumber::class;

    /**
     * @param \App\Commands\SetCartFirstName $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();

        $response->responses = __('responses.checkout.enter_phone');

        return $response;
    }
}
