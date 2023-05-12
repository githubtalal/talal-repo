<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Facebook\Messages\QuickReplies\Text;

class Question extends Response
{

    public static function create($command): Response
    {
        $response = new self();
        $object = $command->get_Answer();
        $response->responses = $object->answer;
        return $response;
    }
}
