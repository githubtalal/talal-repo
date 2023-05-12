<?php

namespace App\Facebook\Messages\Events;

class Postback extends GeneralEvent
{
    private $payload;

    private $title;

    private $referral;



    public function fromEvent($eventData)
    {
        $postbackEvent = $eventData['entry'][0]['messaging'][0];

        $this->senderId = $postbackEvent['sender']['id'];

        $postbackEventData = $postbackEvent['postback'];

        $this->payload = $postbackEventData['payload'];

        $this->referral = $postbackEventData['referral'] ?? [];

        $this->messageId = $postbackEventData['mid'];

        $this->title = $postbackEventData['title'];

        return parent::fromEvent($eventData);
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getReferral()
    {
        return $this->referral;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
