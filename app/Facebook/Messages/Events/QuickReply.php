<?php

namespace App\Facebook\Messages\Events;

class QuickReply extends GeneralEvent
{
    private $text;

    private $payload;

    public function fromEvent($eventData)
    {
        $postbackEvent = $eventData['entry'][0]['messaging'][0];

        $this->senderId = $postbackEvent['sender']['id'];

        $quickReplyData = $postbackEvent['message'];

        $this->payload = $quickReplyData['quick_reply']['payload'];

        $this->messageId = $quickReplyData['mid'];

        $this->text = $quickReplyData['text'];

        return parent::fromEvent($eventData);
    }

    public function getText()
    {
        return $this->text;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
