<?php

namespace App\Facebook\Messages\Buttons;

class CallButton extends Button
{

    public static function create($properties = []): Button
    {
        return new self();
    }

    public function getType()
    {
        return 'phone_number';
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getIdentifier(): array
    {
        return [
            'payload' => $this->payload,
        ];
    }
}
