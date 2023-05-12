<?php

namespace App\Commands\CommandResponse;

use App\Commands\AdditionalQuestion1;
use App\Commands\AdditionalQuestion2;
use App\Commands\SetCartAddress;
use App\Commands\SetCartNotes;
use App\Commands\SetCustomerGovernorate;
use App\Commands\SetCustomerPhoneNumber;
use App\Models\StoreSettings;

class ProcessSteps
{
    public static function getNextStep($step)
    {
        $steps = getStoreSteps();

        if (!$steps) {
            return [
                'expectReply' => false,
                'expectedReply' => null,
                'generatedResponse' => CommandResponseHelper::generatePaymentMethodsResponse()
            ];
        }

        if ($step instanceof SetCustomerPhoneNumber) {
            if (array_key_exists('governorate', $steps)) {
                $expectReply = false;
                $expectedReply = null;
                $response = CommandResponseHelper::generateGovernorateResponse();
            } else if (array_key_exists('address', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\SetCartAddress::class;
                $response = __('responses.checkout.enter_address');
            } else if (array_key_exists('notes', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\SetCartNotes::class;
                $response = CommandResponseHelper::generateNotesResponse();
            } else if (array_key_exists('question1', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion1::class;
                $response = CommandResponseHelper::generateAdditionalQ1Response();
            } else if (array_key_exists('question2', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion2::class;
                $response = CommandResponseHelper::generateAdditionalQ2Response();
            }
        } else if ($step instanceof SetCustomerGovernorate) {
            if (array_key_exists('address', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\SetCartAddress::class;
                $response = __('responses.checkout.enter_address');
            } else if (array_key_exists('notes', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\SetCartNotes::class;
                $response = CommandResponseHelper::generateNotesResponse();
            } else if (array_key_exists('question1', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion1::class;
                $response = CommandResponseHelper::generateAdditionalQ1Response();
            } else if (array_key_exists('question2', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion2::class;
                $response = CommandResponseHelper::generateAdditionalQ2Response();
            } else {
                $expectReply = false;
                $expectedReply = null;
                $response = CommandResponseHelper::generatePaymentMethodsResponse();
            }
        } else if ($step instanceof SetCartAddress) {
            if (array_key_exists('notes', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\SetCartNotes::class;
                $response = CommandResponseHelper::generateNotesResponse();
            } else if (array_key_exists('question1', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion1::class;
                $response = CommandResponseHelper::generateAdditionalQ1Response();
            } else if (array_key_exists('question2', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion2::class;
                $response = CommandResponseHelper::generateAdditionalQ2Response();
            } else {
                $expectReply = false;
                $expectedReply = null;
                $response = CommandResponseHelper::generatePaymentMethodsResponse();
            }
        } else if ($step instanceof SetCartNotes) {
            if (array_key_exists('question1', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion1::class;
                $response = CommandResponseHelper::generateAdditionalQ1Response();
            } else if (array_key_exists('question2', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion2::class;
                $response = CommandResponseHelper::generateAdditionalQ2Response();
            } else {
                $expectReply = false;
                $expectedReply = null;
                $response = CommandResponseHelper::generatePaymentMethodsResponse();
            }
        } else if ($step instanceof AdditionalQuestion1) {
            if (array_key_exists('question2', $steps)) {
                $expectReply = true;
                $expectedReply = \App\Commands\AdditionalQuestion2::class;
                $response = CommandResponseHelper::generateAdditionalQ2Response();
            } else {
                $expectReply = false;
                $expectedReply = null;
                $response = CommandResponseHelper::generatePaymentMethodsResponse();
            }
        } else if ($step instanceof AdditionalQuestion2) {
            $expectReply = false;
            $expectedReply = null;
            $response = CommandResponseHelper::generatePaymentMethodsResponse();
        }

        return [
            'expectReply' => $expectReply,
            'expectedReply' => $expectedReply,
            'generatedResponse' => $response
        ];
    }

    public static function canSendHour($command)
    {
        $bot_settings = StoreSettings::where([
            ['store_id', request('store_id')],
            ['key', 'bot_settings']
        ])->first();

        if ($bot_settings && array_key_exists('hour', $bot_settings->value))
            return [
                'expectReply' => true,
                'expectedReply' => \App\Commands\SetReservationHour::class,
                'generatedResponse' => __('responses.cart.pick_hour')
            ];

        return [
            'expectReply' => false,
            'expectedReply' => null,
            'generatedResponse' => CommandResponseHelper::skipHourStep($command)
        ];
    }
}
