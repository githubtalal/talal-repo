<?php

namespace App\Facebook\Messages\Templates;

abstract class Template
{

    /**
     * Array of properties for each template
     * @param $properties
     * @return self
     */
    abstract public static function create($properties = []): self;

    abstract public function getTemplateKey(): string;

    abstract public function prepare(): array;

    public function toArray(): array {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => array_merge([
                    'template_type' => $this->getTemplateKey(),
                ], $this->prepare()),
            ],
        ];
    }
}
