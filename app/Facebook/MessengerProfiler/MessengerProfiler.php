<?php

namespace App\Facebook\MessengerProfiler;

use App\Facebook\MessengerProfiler\Properties\IProperty;
use GuzzleHttp\Client;

class MessengerProfiler
{

    const BASE_URL = 'https://graph.facebook.com/v14.0/';


    /**
     * @var IProperty[] Messenger Profile attributes, ex: {get_started, greetings, etc...}
     */
    private $properties = [];

    private $httpClient;

    /**
     * @var string Page access token.
     */
    private $pageAccessToken;

    private function __construct($pageAccessToken)
    {
        $this->pageAccessToken = $pageAccessToken;
        $this->httpClient = new Client([
            'base_uri' => self::BASE_URL,
            'defaults' => [
                'query' => [
                    'access_token' => $this->pageAccessToken,
                ],
            ],
        ]);
    }

    public static function create($pageAccessToken): self
    {
        return new self($pageAccessToken);
    }

    public function addProperty(IProperty $property)
    {
        $this->properties[] = $property;
        return $this;
    }

    public function send()
    {
        $data = [];

        foreach ($this->properties as $property) {
            $data[$property->getKey()] = $property->toArray();
        }

       try {
           $httpResponse = $this->httpClient->post('me/messenger_profile?' . "access_token=$this->pageAccessToken", [
               'json' => $data,
               'headers' => [
                   'Content-Type' => 'application/json'
               ],
               'verify' => false,
           ]);
           $response = json_decode((string) $httpResponse->getBody());
           logger('[FACEBOOK CONNECT] Status from set messenger profiler', [
               'http_status' => $httpResponse->getStatusCode(),
               'response' => $response,
           ]);
           return $response->result === 'success';
       } catch (\Exception $exception) {
           throw new \Exception('Something went wrong, ' . $exception->getMessage());
       }
    }

    /**
     * @param string $properties
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteProperty($properties)
    {
        // TODO: Check if all passed properties exist.
        if (is_string($properties)) $properties = [$properties];

        try {
            $httpResponse = $this->httpClient->delete('/me/messenger_profile', [
                'data' => [
                    'fields' => $properties,
                ]
            ]);
            $response = json_decode((string) $httpResponse->getBody());
            return $response->result === 'success';
        } catch (\Exception $exception) {
            throw new \Exception('Something went wrong, ' . $exception->getMessage());
        }
    }
}
