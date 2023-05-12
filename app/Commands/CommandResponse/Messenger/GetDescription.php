<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;

class GetDescription extends Response
{
    public static function create($command): Response
    {
        $response = new self();
        $text = $command->get_description();
        $response->responses = $text;
        return $response;
    }
}
