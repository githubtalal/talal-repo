<?php

namespace App\Facebook\Messages\Templates;

use App\Facebook\Messages\Buttons\Button;
use App\Facebook\Messages\Buttons\UrlButton;

class GenericTemplate extends Template
{
    private $buttons = [];

    private $title;
    private $subtitle;
    private $image_url;
    private $default_action;

    /**
     * Array of properties for each template
     * @param $properties
     * @return self
     */
    public static function create($properties = []): self
    {
        $instance = new self();
        foreach ($properties as $key => $value) {
            if (property_exists(self::class, $key)) {
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    public function addButton(Button $button)
    {
        $this->buttons[] = $button;
        return $this;
    }

    public function setTitle($newTitle)
    {
        $this->title = $newTitle;
        return $this;
    }

    public function setSubtitle($newSubtitle)
    {
        $this->subtitle = $newSubtitle;
        return $this;
    }

    public function setDefaultAction(UrlButton $urlButton)
    {
        $this->default_action = $urlButton;
        return $this;
    }

    public function setImage($imageUrl)
    {
        $this->image_url = $imageUrl;
        return $this;
    }

    public function prepare(): array
    {
        $buttons = array_map(function ($button) {
            return $button->toArray();
        }, $this->buttons);

        return [
            'elements' => [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'image_url' => $this->image_url,
                'default_action' => $this->default_action,
                'buttons' => $buttons,
            ],
        ];
    }

    public function getTemplateKey(): string
    {
        return 'generic';
    }
}
