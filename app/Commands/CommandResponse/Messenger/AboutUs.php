<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;

class AboutUs extends Response
{

    public static function create($command): Response
    {
        $response = new self();

        $res = $command->get_about_us();

        if ($res == null)
            $res = 'About us section is empty!';

        $response->responses = $res;
        return $response;
    }
}
