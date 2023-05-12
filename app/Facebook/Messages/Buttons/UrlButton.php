<?php

namespace App\Facebook\Messages\Buttons;

class UrlButton extends Button
{

    public function getType()
    {
        return 'web_url';
    }

    public function getIdentifier(): array
    {
        return [
            'url' => $this->payload,
        ];
    }

    public static function create($properties = []): Button
    {
        return new self();
    }
}
