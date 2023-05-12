<?php

namespace App\Facebook\MessengerProfiler\Properties;

use App\Facebook\Messages\Buttons\Button;

class PersistentMenu implements IProperty
{
    private $buttons = [];

    public static function create(): self
    {
        return new self();
    }

    public function addButton(Button $button): self
    {
        $this->buttons[] = $button;
        return $this;
    }

    public function toArray(): array
    {
        return [
            [
                'locale' => 'default',
                'composer_input_disabled' => false,
                'call_to_actions' => array_map(function (Button $button) {
                    return $button->toArray();
                }, $this->buttons)
            ]
        ];
    }

    public function getKey(): string
    {
        return 'persistent_menu';
    }
}
