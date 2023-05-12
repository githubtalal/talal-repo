<?php

namespace App\Facebook\Messages\Buttons;

abstract class Button
{
    protected $title;

    protected $payload;

    abstract public static function create($properties = []): self;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    abstract public function getType();

    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Example ['url' => 'https://google.com']
     * @return array
     */
    abstract public function getIdentifier(): array;

    public function toArray()
    {
        return array_merge([
            'type' => $this->getType(),
            'title' => $this->title,
        ], $this->getIdentifier());
    }

}
