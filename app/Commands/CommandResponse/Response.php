<?php

namespace App\Commands\CommandResponse;

use App\Commands\Command;

abstract class Response
{
    /**
     * Whether the current response expects another reply from user
     * @var bool
     */
    protected bool $expectReply = false;

    /**
     * Expected reply from user, ex: SetCartFirstName
     */
    protected $expectedReply = null;

    /**
     * It could be array of responses or just one response
     */
    protected $responses;

    protected $additionalData = [];

    public abstract static function create($command): self;

    public function getResponse()
    {
        return $this->responses;
    }

    public function replyExpected(): bool
    {
        return $this->expectReply;
    }

    public function expectedReply(): ?string
    {
        return $this->expectedReply;
    }

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }
}
