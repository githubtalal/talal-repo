<?php

namespace App\Commands\CommandResponse\Messenger;


use App\Commands\CommandResponse\Response;
use App\Facebook\Messages\QuickReplies\Text;
use Carbon\Carbon;

class SetReservationStartMonth extends Response
{
    /**
     * @param \App\Commands\SetReservationStartMonth $command
     * @return Response
     */
    public static function create($command): Response
    {
        $instance = new self();
        Carbon::setLocale('ar');
        $date = Carbon::now()->setMonth($command->getMonth());
        $response = Text::create();
        $response->setText(__('responses.cart.pick_day'));
        $payload = [
            \App\Commands\SetReservationStartMonth::MONTH => $command->getMonth(),
            \App\Commands\SetReservationStartMonth::YEAR => $command->getYear(),
            \App\Commands\SetReservationStartMonth::PRODUCT_ID => $command->getProductId(),
            \App\Commands\SetReservationStartMonth::PAGE => $command->getCurrentPage(),
        ];
        if ($command->hasPreviousPage()) {
            $payload[\App\Commands\SetReservationStartMonth::PAGE] = $command->getCurrentPage() - 1;
            $response->add(__('pagination.previous'), \App\Commands\SetReservationStartMonth::buildPayload($payload));
        }

        foreach ($command->getDays() as $day) {
            $response->add($day['text'], \App\Commands\SetReservationDay::buildPayload([
                \App\Commands\SetReservationDay::PRODUCT_ID => $command->getProductId(),
                \App\Commands\SetReservationDay::YEAR => $command->getYear(),
                \App\Commands\SetReservationDay::MONTH => $command->getMonth(),
                \App\Commands\SetReservationDay::DAY => $day['value'],
            ]));
        }

        if ($command->hasNextPage()) {
            $payload[\App\Commands\SetReservationStartMonth::PAGE] = $command->getCurrentPage() + 1;
            $response->add(__('pagination.next'), \App\Commands\SetReservationStartMonth::buildPayload($payload));
        }

        $instance->responses = $response;

        return $instance;
    }
}
