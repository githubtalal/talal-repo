<?php

namespace App\Facebook\Messages\Templates;

class CarouselTemplate extends Template
{

    /**
     * @var GenericTemplate[]
     */
    private $templates = [];

    public static function create($properties = []): self
    {
        $instance = new self();
        return $instance;
    }

    public function getTemplateKey(): string
    {
        return 'generic';
    }

    public function addGenericTemplate(GenericTemplate $genericTemplate)
    {
        $this->templates[] = $genericTemplate;
        return $this;
    }

    public function prepare(): array
    {
        $templates = array_map(function ($template) {
            $templateArray = $template->toArray();
            if (isset($templateArray['attachment']['payload']['elements']))
                return $templateArray['attachment']['payload']['elements'];
            return null;
        }, $this->templates);

        return [
            'elements' => $templates,
        ];
    }
}
