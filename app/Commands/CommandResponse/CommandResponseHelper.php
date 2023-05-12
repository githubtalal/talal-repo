<?php

namespace App\Commands\CommandResponse;

use App\Cart;
use App\Commands\CommandParser;
use App\Commands\SetCartPaymentMethod;
use App\Commands\SetReservationMinutes;
use App\Facebook\Messages\QuickReplies\Text;
use App\Models\StoreSettings;

class CommandResponseHelper
{
    public static function generatePaymentMethodsResponse($message = '')
    {
        $paymentCarousel = Text::create();

        if (!$message)
            $message = get_payment_method_message();
        else
            // in case payment method failed
            $message = __($message);

        $paymentCarousel->setText($message);

        $cart = new Cart();
        $myCart = $cart->getCart();

        if ($myCart->currency == 'USD') {

            $codMethod = get_cod_payment_method();

            $paymentCarousel->add($codMethod->getLabel(), SetCartPaymentMethod::buildPayload([
                SetCartPaymentMethod::METHOD_CODE => $codMethod->getKey(),
            ]));
        } else {

            $enabled_payments = get_enabled_payment_methods();
            foreach ($enabled_payments as $enabled_payment) {

                if ($enabled_payment->getKey() == 'cod')
                    $label = $enabled_payment->getLabel();
                else
                    $label = __('payment_methods.payment_methods.' . $enabled_payment->getKey());

                $paymentCarousel->add($label, SetCartPaymentMethod::buildPayload([
                    SetCartPaymentMethod::METHOD_CODE => $enabled_payment->getKey(),
                ]));
            }
        }
        return $paymentCarousel;
    }

    public static function generateGovernorateResponse()
    {
        $statesQuickReply = Text::create();
        $statesQuickReply->setText(__('responses.checkout.customer_governorate'));

        foreach (get_states() as $stateCode => $stateName) {
            $statesQuickReply->add($stateName, \App\Commands\SetCustomerGovernorate::buildPayload([
                \App\Commands\SetCustomerGovernorate::GOVERNORATE => $stateCode,
            ]));
        }

        return $statesQuickReply;
    }

    public static function generateNotesResponse()
    {
        // get notes label
        $label = __('responses.cart.enter_notes');

        $bot_settings = StoreSettings::where([
            ['store_id', request('store_id')],
            ['key', 'bot_settings']
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes'])
                $label = $bot_settings['notes'];
        }
        return $label;
    }

    public static function generateAdditionalQ1Response()
    {
        $q1Value = __('app.steps.question1');

        $bot_settings = StoreSettings::where([
            ['store_id', request('store_id')],
            ['key', 'bot_settings']
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1'])
                $q1Value = $bot_settings['question1'];
        }
        return $q1Value;
    }

    public static function generateAdditionalQ2Response()
    {
        $q2Value = __('app.steps.question2');

        $bot_settings = StoreSettings::where([
            ['store_id', request('store_id')],
            ['key', 'bot_settings']
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2'])
                $q2Value = $bot_settings['question2'];
        }
        return $q2Value;
    }

    public static function skipHourStep($dayCommand)
    {
        $payload = SetReservationMinutes::buildPayload([
            SetReservationMinutes::PRODUCT_ID => $dayCommand->getProductId(),
            SetReservationMinutes::YEAR => $dayCommand->getYear(),
            SetReservationMinutes::MONTH => $dayCommand->getMonth(),
            SetReservationMinutes::DAY => $dayCommand->getDay(),
            SetReservationMinutes::HOUR => null,
            SetReservationMinutes::MINUTE => null,
        ]);

        $command = CommandParser::parsePayload($payload);
        $command->run();

        return CommandResponse::create($command)->getResponse();
    }
}
