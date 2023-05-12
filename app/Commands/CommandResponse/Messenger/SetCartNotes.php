<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\ProcessSteps;
use App\Commands\CommandResponse\Response;

class SetCartNotes extends Response
{
    protected bool $expectReply = false;
    protected $expectedReply = null;

    /**
     * @param \App\Commands\SetCartNotes $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();

        $result = ProcessSteps::getNextStep($command);

        $response->expectReply = $result['expectReply'];
        $response->expectedReply = $result['expectedReply'];

        $response->responses = $result['generatedResponse'];

        return $response;
    }
}
