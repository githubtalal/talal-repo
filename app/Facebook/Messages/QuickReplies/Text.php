<?php

namespace App\Facebook\Messages\QuickReplies;

class Text
{
    private $replies = [];

    private $text;

    public static function create()
    {
        return new self();
    }

    public function setText($text)
    {
        $this->text = $text;
        return $text;
    }
    public function add($text, $payload, $image_url = null)
    {
        $reply =  [
            'content_type' => 'text',
            'title' => $text,
            'payload' => $payload,
        ];
        if ($image_url)
            $reply['image_url'] = $image_url;

        $this->replies[] = $reply;

        return $this;
    }

    public function prepare()
    {
        return [
            'text' => $this->text,
            'quick_replies' => $this->replies,
        ];
    }
}
