<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;

class GetStarted extends Response
{

    public static function create($command): Response
    {
        $response = new self();
        $response->responses = 'Welcome ...';
        return $response;
    }
}
