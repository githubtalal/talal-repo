<?php

namespace App\Facebook\Messages\Events;

class Messaging extends GeneralEvent
{
    private $textMessage;

    private $isPostBack = false;

    private $payload;

    public function fromEvent($eventData)
    {
        $messagingEvent = $eventData['entry'][0]['messaging'][0];

        $this->textMessage = $messagingEvent['message']['text'];

        $this->messageId = $messagingEvent['message']['mid'];

        $this->isPostBack = isset($messagingEvent['message']['quick_reply']);

        if ($this->isPostBack) {
            $this->payload = $messagingEvent['message']['quick_reply']['payload'];
        }

        parent::fromEvent($eventData);

        return $this;
    }

    public function getTextMessage()
    {
        return $this->textMessage;
    }

    public function isPostBack()
    {
        return $this->isPostBack;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
