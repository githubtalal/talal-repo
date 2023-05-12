<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandParser;
use App\Commands\CommandResponse\CommandResponse;
use App\Commands\CommandResponse\Response;
use App\Commands\SetCartPaymentMethod as SetCartPaymentMethodCommand;
use App\Commands\SetExtraUserFields as SetExtraUserFieldsCommand;

class SetExtraUserFields extends Response
{
    private $command;
    private $currentIdx;
    private $nextIdx;

    /**
     * @param \App\Commands\SetExtraUserFields $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();
        $response->command = $command;

        $response->currentIdx = $command->getIdx();
        $response->nextIdx = $response->currentIdx + 1;

        if (!$command->getfieldInfo($response->nextIdx)) {
            $payload = SetCartPaymentMethodCommand::buildPayload([
                SetCartPaymentMethodCommand::METHOD_CODE => $command->getMethod(),
            ]);

            $command = CommandParser::parsePayload($payload);
            $command->run();
            $response->responses = CommandResponse::create($command)->getResponse();
            return $response;
        }

        $response->expectReply = true;
        $response->expectedReply = SetExtraUserFieldsCommand::class;

        $response->responses = $command->getfieldInfo($response->nextIdx)['label'];
        return $response;
    }

    public function getAdditionalData(): array
    {
        return [
            $this->nextIdx,
        ];
    }
}
