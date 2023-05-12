<?php

namespace App\Facebook\Messages\Events;

class GeneralEvent
{
    protected $messageId;

    protected $senderId;

    protected $recipientId;


    protected $timestamp;

    public function fromEvent($eventData)
    {
        $messageBody = array_shift($eventData['entry']);
        $this->recipientId = $messageBody['id'];
        $this->timestamp = $messageBody['time'];

        $messagingEvent = array_shift($messageBody['messaging']);

        $this->senderId = $messagingEvent['sender']['id'];
        $this->recipientId = $messagingEvent['recipient']['id'];

        return $this;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getRecipientId()
    {
        return $this->recipientId;
    }

}
