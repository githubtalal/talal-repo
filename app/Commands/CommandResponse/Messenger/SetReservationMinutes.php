<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Commands\ListCategories;
use App\Commands\SetReservationStartMonth;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\Messages\QuickReplies\Text;
use App\Facebook\Messages\Templates\ButtonTemplate;
use Carbon\Carbon;

class SetReservationMinutes extends Response
{

    /**
     * @param \App\Commands\SetReservationMinutes $command
     * @return Response
     */
    public static function create($command): Response
    {
        $instance = new self();
        Carbon::setLocale('ar_SY');

        if ($command->requireEndDate()) {
            // TODO: Duplicated code, see SetReservationStartMonth
            $monthsQuickReply = Text::create();
            $monthsQuickReply->setText(__('responses.cart.pick_month'));

            $monthsCount = 12;
            $checkin = Carbon::parse($command->getStartDate());

            if (!ifHourEnabled() || $checkin->hour != 0)
                $checkin->addDay();

            for ($i = 0; $i < $monthsCount; $i++) {
                $monthsQuickReply->add($checkin->translatedFormat('F Y'), SetReservationStartMonth::buildPayload([SetReservationStartMonth::MONTH => $checkin->month, SetReservationStartMonth::YEAR => $checkin->year, SetReservationStartMonth::PRODUCT_ID => $command->getProductId(), SetReservationStartMonth::PAGE => 1]));
                $checkin->addMonth();
            }

            $instance->responses = [
                __('responses.checkout.enter_end_date'),
                $monthsQuickReply
            ];
        } else {
            $buttonTemplate = ButtonTemplate::create();
            $textMessage = __('responses.cart.product_reserved', ['start_date' => $command->getStartDate()]);
            if ($command->productRequireEndDate()) {
                $textMessage = __('responses.cart.product_reserved_with_end_date', ['start_date' => $command->getStartDate(), 'end_date' => $command->getEndDate()]);
            }
            $buttonTemplate->editText($textMessage);

            $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.categories.return_to_categories'))->setPayload(ListCategories::buildPayload()));
            $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.cart'))->setPayload(\App\Commands\ShowCart::buildPayload()));
            $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.checkout'))->setPayload(\App\Commands\Checkout::buildPayload()));

            $instance->responses = $buttonTemplate;
        }

        return $instance;
    }
}
