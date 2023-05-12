<?php

namespace App\Facebook\Messages\Templates;

use App\Facebook\Messages\Buttons\Button;

class ButtonTemplate extends Template
{
    private $text;

    private $buttons = [];

    public static function create($properties = []): self
    {
        return new self();
    }

    public function addButton(Button $button)
    {
        $this->buttons[] = $button;
        return $this;
    }

    public function editText($newText)
    {
        $this->text = $newText;
        return $this;
    }

    public function getTemplateKey(): string
    {
        return 'button';
    }

    public function prepare(): array
    {
        $buttons = array_map(function ($button) {
            return $button->toArray();
        }, $this->buttons);

        return [
            'text' => $this->text,
            'buttons' => $buttons,
        ];
    }
}
