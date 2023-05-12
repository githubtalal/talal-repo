<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\ProcessSteps;
use App\Commands\CommandResponse\Response;

class SetReservationDay extends Response
{
    private $command;
    protected bool $expectReply = false;
    protected $expectedReply = null;
    /**
     * @param \App\Commands\SetReservationDay $command
     * @return Response
     */
    public static function create($command): Response
    {
        $instance = new self();

        $instance->command = $command;

        $result = ProcessSteps::canSendHour($command);

        $instance->expectReply = $result['expectReply'];
        $instance->expectedReply = $result['expectedReply'];

        $instance->responses = $result['generatedResponse'];

        return $instance;
    }

    public function getAdditionalData(): array
    {
        return [
            $this->command->getProductId(),
            $this->command->getYear(),
            $this->command->getMonth(),
            $this->command->getDay(),
        ];
    }
}
