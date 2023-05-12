<?php

namespace App\Facebook;

class FacebookAccessToken
{
    private $access_token;
    private $token_type;
    private $expires_in;

    /**
     * @param array $accessResponse
     * @return FacebookAccessToken
     */
    public static function parse($accessResponse)
    {
        $instance = new self();
        $instance->access_token = $accessResponse->access_token;
        $instance->token_type = $accessResponse->token_type ?? null;
        $instance->expires_in = $accessResponse->expires_in ?? null;
        return $instance;
    }

    public function getExpiryDate()
    {
        return $this->expires_in;
    }

    public function getTokenType()
    {
        return $this->token_type;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }
}
