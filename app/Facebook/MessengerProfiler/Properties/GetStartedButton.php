<?php


namespace App\Facebook\MessengerProfiler\Properties;

/**
 * @link https://developers.facebook.com/docs/messenger-platform/reference/messenger-profile-api/get-started-button
 */
class GetStartedButton implements IProperty
{

    /**
     * @var string
     */
    private $payload = null;

    public static function create(): self
    {
        return new self();
    }

    /**
     * @param string $payload 1000 character limit.
     * @return $this
     */
    public function setPayload(string $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'payload' => $this->payload,
        ];
    }

    public function getKey(): string
    {
        return 'get_started';
    }
}
