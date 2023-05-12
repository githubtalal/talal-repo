<?php

namespace App\Commands;

use Illuminate\Support\Arr;

class CommandParser
{
    public static function parsePayload($payload)
    {
        // Example: list-category-products_ID_PAGE,
        $payload = explode('_', $payload);

        $commandName = Arr::first($payload);
        array_shift($payload);
        if (!$commandName) {
            return null;
        }

        // \Illuminate\Support\Facades\Log::info(serialize($commandName));
        $command = null;
        switch ($commandName) {
            case ListCategories::SIG:
                $command = ListCategories::create($payload);
                break;
            case ListCategoryProducts::SIG:
                $command = ListCategoryProducts::create($payload);
                break;
            case ViewProduct::SIG:
                $command = ViewProduct::create($payload);
                break;
            case AddToCart::SIG:
                $command = AddToCart::create($payload);
                break;
            case ShowCart::SIG:
                $command = ShowCart::create($payload);
                break;
            case DeleteCart::SIG:
                $command = DeleteCart::create($payload);
                break;
            case Checkout::SIG:
                $command = Checkout::create($payload);
                break;
            case SetCartFirstName::SIG:
                $command = SetCartFirstName::create($payload);
                break;
            case SetCustomerLastName::SIG:
                $command = SetCustomerLastName::create($payload);
                break;
            case SetCartAddress::SIG:
                $command = SetCartAddress::create($payload);
                break;
            case SetCustomerGovernorate::SIG:
                $command = SetCustomerGovernorate::create($payload);
                break;
            case SetCartPaymentMethod::SIG:
                $command = SetCartPaymentMethod::create($payload);
                break;
            case RemoveItemFromCart::SIG:
                $command = RemoveItemFromCart::create($payload);
                break;
            case GetStarted::SIG:
                $command = GetStarted::create($payload);
                break;
            case SetReservationStartMonth::SIG:
                $command = SetReservationStartMonth::create($payload);
                break;
            case SetReservationDay::SIG:
                $command = SetReservationDay::create($payload);
                break;
            case SetReservationHour::SIG:
                $command = SetReservationHour::create($payload);
                break;
            case SetReservationMinutes::SIG:
                $command = SetReservationMinutes::create($payload);
                break;
            case SetCartNotes::SIG:
                $command = SetCartNotes::create($payload);
                break;
            case ContactUs::SIG:
                $command = ContactUs::create($payload);
                break;
            case FAQs::SIG:
                $command = FAQs::create($payload);
                break;
            case Question::SIG:
                $command = Question::create($payload);
                break;
            case AboutUs::SIG:
                $command = AboutUs::create($payload);
                break;
            case PoweredByEcart::SIG:
                $command = PoweredByEcart::create($payload);
                break;
            case GenerateInvoice::SIG:
                $command = GenerateInvoice::create($payload);
                break;
            case GetDescription::SIG:
                $command = GetDescription::create($payload);
                break;
            case SetExtraUserFields::SIG:
                $command = SetExtraUserFields::create($payload);
                break;
        }
        return $command;
    }
}
