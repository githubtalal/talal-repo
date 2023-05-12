<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Facebook\Messages\QuickReplies\File;

class GenerateInvoice extends Response
{

    public static function create($command): Response
    {
        $response = new self();

        $order = $command->getOrder();

        $url = generate_invoice($order);

        if (ifAppLocal())
            $url = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';

        $file = File::create();
        $file->setURl($url);

        $response->responses = $file;
        return $response;
    }
}
