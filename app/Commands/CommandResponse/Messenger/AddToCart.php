<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Commands\ListCategories;
use App\Commands\SetReservationStartMonth;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\Messages\QuickReplies\Text;
use App\Facebook\Messages\Templates\ButtonTemplate;
use Carbon\Carbon;

class AddToCart extends Response
{

    private $product;

    /**
     * @param \App\Commands\AddToCart $command
     * @return static
     */
    public static function create($command): self
    {
        Carbon::setLocale('ar_SY');
        $instance = new self();
        $instance->product = $command->getProduct();

        $buttonTemplate = ButtonTemplate::create();

        if (!$command->isCurrecymatch()) {
            $buttonTemplate->editText(__('responses.currency_are_not_matched'));
        } else {

            if ($instance->product->isReservation()) {
                $monthsQuickReply = Text::create();
                $monthsQuickReply->setText(__('responses.cart.pick_month'));

                $monthsCount = 12;
                $nowDate = now();

                for ($i = 0; $i < $monthsCount; $i++) {
                    $monthsQuickReply->add($nowDate->translatedFormat('F Y'), SetReservationStartMonth::buildPayload([SetReservationStartMonth::MONTH => $nowDate->month, SetReservationStartMonth::YEAR => $nowDate->year, SetReservationStartMonth::PRODUCT_ID => $instance->product->id, SetReservationStartMonth::PAGE => 1]));
                    $nowDate->addMonth();
                }

                $instance->responses = $monthsQuickReply;
                return $instance;
            } else {
                $buttonTemplate->editText(__('responses.cart.added_to_cart'));
            }
        }

        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.categories.return_to_categories'))->setPayload(ListCategories::buildPayload()));
        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.cart'))->setPayload(\App\Commands\ShowCart::buildPayload()));
        $buttonTemplate->addButton(PostbackButton::create()->setTitle(__('responses.cart.checkout'))->setPayload(\App\Commands\Checkout::buildPayload()));
        $instance->responses = $buttonTemplate;


        return $instance;
    }

    public function replyExpected(): bool
    {
        return !$this->product->isReservation();
    }

    public function expectedReply(): string
    {
        if (!$this->product->isReservation()) {
            return __('responses.cart.added_to_cart');
        }
        return '';
    }
}
