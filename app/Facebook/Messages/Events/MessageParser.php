<?php

namespace App\Facebook\Messages\Events;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MessageParser
{
    public static function parse($message)
    {
        if (isset($message['entry']) === false) return null;

        $tmpMessage = $message;
        $event = Arr::first($message['entry'])['messaging'][0];

        $eventName = Collection::make($event)->except([
            'sender',
            'recipient',
            'timestamp',
            'thread_id',
        ])->keys()->first();

        $event = null;

        switch ($eventName) {
            case 'message':
                $event = new Messaging();
                break;
            case 'postback':
                $event = new Postback();
                break;
            case 'quick_reply':
                $event = new QuickReply();
                break;
            default:
                $event = new GeneralEvent();
                break;
        }

        return $event->fromEvent($message);
    }
}
