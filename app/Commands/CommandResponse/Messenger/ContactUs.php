<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;

class ContactUs extends Response
{

    public static function create($command): Response
    {
        $response = new self();

        $res = $command->get_contact_us();

        if ($res == null)
            $res = 'Contact us section is empty!';

        $response->responses = $res;
        return $response;
    }
}
