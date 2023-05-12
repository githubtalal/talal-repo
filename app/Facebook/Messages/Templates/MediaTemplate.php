<?php

namespace App\Facebook\Messages\Templates;

use App\Facebook\Messages\Buttons\Button;

class MediaTemplate extends Template
{

    /**
     * @var Button[]
     */
    private $buttons = [];

    /**
     * @var array
     */
    private $media = [];

    public static function create($properties = []): Template
    {
        return new self();
    }

    public function addVideo($videoUrl)
    {
        $this->media = [
            'media_type' => 'video',
            'url' => $videoUrl,
        ];
        return $this;
    }

    public function addImage($imageUrl)
    {
        $this->media = [
            'media_type' => 'image',
            'url' => $imageUrl,
        ];
        return $this;
    }

    public function addButton(Button $button)
    {
        $this->buttons[] = $button;
        return $this;
    }

    public function prepare(): array
    {
        $buttons = array_map(function ($button) {
            return $button->toArray();
        }, $this->buttons);

        return [
            'elements' => array_merge($this->media, $buttons),
        ];
    }


    public function getTemplateKey(): string
    {
        return 'media';
    }
}
