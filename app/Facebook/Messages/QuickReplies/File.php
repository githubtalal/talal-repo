<?php

namespace App\Facebook\Messages\QuickReplies;

class File
{

    private $url;

    public static function create()
    {
        return new self();
    }

    public function setURl($url)
    {
        $this->url = $url;
        return $url;
    }
    public function getReply()
    {
        $reply =  [
            'attachment' => [
                'type' => 'file',
                'payload' => [
                    'url' => $this->url
                ]
            ]
        ];

        return $reply;
    }
}
