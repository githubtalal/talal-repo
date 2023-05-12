<?php

namespace App\Commands\CommandResponse\Messenger;


use App\Commands\CommandResponse\Response;
use App\Commands\SetReservationMinutes;
use App\Facebook\Messages\QuickReplies\Text;

class SetReservationHour extends Response
{
    private $command;

    /**
     * @param \App\Commands\SetReservationHour $command
     * @return Response
     */
    public static function create($command): Response
    {
        $instance = new self();

        $intervals = [0, 15, 30, 45];
        $instance->command = $command;
        $instance->responses = Text::create();
        $instance->responses->setText(__('responses.cart.pick_minutes'));
        foreach ($intervals as $interval) {
            $hour = $command->getHour();
            // AM
            if ($hour <= 12) {
                $text = now()->setMinute($interval)->setHour($command->getHour())->format('g:i A');
                $instance->responses->add($text, SetReservationMinutes::buildPayload([
                    SetReservationMinutes::MINUTE => $interval,
                    SetReservationMinutes::PRODUCT_ID => $command->getProductId(),
                    SetReservationMinutes::YEAR => $command->getYear(),
                    SetReservationMinutes::MONTH => $command->getMonth(),
                    SetReservationMinutes::DAY => $command->getDay(),
                    SetReservationMinutes::HOUR => $command->getHour(),
                ]));
            }
            $hour = $hour > 12 ? $hour - 12 : $hour;
            // PM
            $text = now()->setMinute($interval)->setHour($hour + 12)->format('g:i A');
            $instance->responses->add($text, SetReservationMinutes::buildPayload([
                SetReservationMinutes::MINUTE => $interval,
                SetReservationMinutes::PRODUCT_ID => $command->getProductId(),
                SetReservationMinutes::YEAR => $command->getYear(),
                SetReservationMinutes::MONTH => $command->getMonth(),
                SetReservationMinutes::DAY => $command->getDay(),
                SetReservationMinutes::HOUR => $hour + 12,
            ]));
        }
        return $instance;
    }
}
