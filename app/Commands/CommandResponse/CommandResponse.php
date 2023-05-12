<?php

namespace App\Commands\CommandResponse;

use App\Commands\AboutUs;
use App\Commands\AdditionalQuestion1;
use App\Commands\AdditionalQuestion2;
use App\Commands\AddToCart;
use App\Commands\Checkout;
use App\Commands\ContactUs;
use App\Commands\DeleteCart;
use App\Commands\FAQs;
use App\Commands\GenerateInvoice;
use App\Commands\GetDescription;
use App\Commands\GetStarted;
use App\Commands\ListCategories;
use App\Commands\ListCategoryProducts;
use App\Commands\PoweredByEcart;
use App\Commands\Question;
use App\Commands\RemoveItemFromCart;
use App\Commands\SetCartAddress;
use App\Commands\SetCartFirstName;
use App\Commands\SetCartNotes;
use App\Commands\SetCartPaymentMethod;
use App\Commands\SetCustomerGovernorate;
use App\Commands\SetCustomerLastName;
use App\Commands\SetCustomerPhoneNumber;
use App\Commands\SetExtraUserFields;
use App\Commands\SetReservationDay;
use App\Commands\SetReservationHour;
use App\Commands\SetReservationMinutes;
use App\Commands\SetReservationStartMonth;
use App\Commands\ShowCart;

class CommandResponse
{
    public static function create($command)
    {
        $response = null;
        if ($command instanceof AddToCart) {
            $response = \App\Commands\CommandResponse\Messenger\AddToCart::create($command);
        } else if ($command instanceof DeleteCart) {
            $response = \App\Commands\CommandResponse\Messenger\DeleteCart::create($command);
        } else if ($command instanceof ListCategories) {
            $response = \App\Commands\CommandResponse\Messenger\ListCategories::create($command);
        } else if ($command instanceof ListCategoryProducts) {
            $response = \App\Commands\CommandResponse\Messenger\ListCategoriesProducts::create($command);
        } else if ($command instanceof RemoveItemFromCart) {
            $response = \App\Commands\CommandResponse\Messenger\RemoteItemFromCart::create($command);
        } else if ($command instanceof ShowCart) {
            $response = \App\Commands\CommandResponse\Messenger\ShowCart::create($command);
        } else if ($command instanceof Checkout) {
            $response = \App\Commands\CommandResponse\Messenger\Checkout::create($command);
        } else if ($command instanceof SetCartFirstName) {
            $response = \App\Commands\CommandResponse\Messenger\SetCartFirstName::create($command);
        } else if ($command instanceof SetCustomerLastName) {
            $response = \App\Commands\CommandResponse\Messenger\SetCartLastName::create($command);
        } else if ($command instanceof SetCartAddress) {
            $response = \App\Commands\CommandResponse\Messenger\SetCartAddress::create($command);
        } else if ($command instanceof SetCustomerGovernorate) {
            $response = \App\Commands\CommandResponse\Messenger\SetCustomerGovernorate::create($command);
        } else if ($command instanceof SetCartPaymentMethod) {
            $response = \App\Commands\CommandResponse\Messenger\SetCheckoutPaymentMethod::create($command);
        } else if ($command instanceof SetCustomerPhoneNumber) {
            $response = \App\Commands\CommandResponse\Messenger\SetCartPhoneNumber::create($command);
        } else if ($command instanceof GetStarted) {
            $response = \App\Commands\CommandResponse\Messenger\GetStarted::create($command);
        } else if ($command instanceof SetReservationStartMonth) {
            $response = \App\Commands\CommandResponse\Messenger\SetReservationStartMonth::create($command);
        } else if ($command instanceof SetReservationHour) {
            $response = \App\Commands\CommandResponse\Messenger\SetReservationHour::create($command);
        } else if ($command instanceof SetReservationDay) {
            $response = \App\Commands\CommandResponse\Messenger\SetReservationDay::create($command);
        } else if ($command instanceof SetReservationMinutes) {
            $response = \App\Commands\CommandResponse\Messenger\SetReservationMinutes::create($command);
        } else if ($command instanceof SetCartNotes) {
            $response = \App\Commands\CommandResponse\Messenger\SetCartNotes::create($command);
        } else if ($command instanceof ContactUs) {
            $response = \App\Commands\CommandResponse\Messenger\ContactUs::create($command);
        } else if ($command instanceof FAQs) {
            $response = \App\Commands\CommandResponse\Messenger\FAQs::create($command);
        } else if ($command instanceof Question) {
            $response = \App\Commands\CommandResponse\Messenger\Question::create($command);
        } else if ($command instanceof AboutUs) {
            $response = \App\Commands\CommandResponse\Messenger\AboutUs::create($command);
        } else if ($command instanceof PoweredByEcart) {
            $response = \App\Commands\CommandResponse\Messenger\PoweredByEcart::create($command);
        } else if ($command instanceof GenerateInvoice) {
            $response = \App\Commands\CommandResponse\Messenger\GenerateInvoice::create($command);
        } else if ($command instanceof AdditionalQuestion1) {
            $response = \App\Commands\CommandResponse\Messenger\AdditionalQuestion1::create($command);
        } else if ($command instanceof AdditionalQuestion2) {
            $response = \App\Commands\CommandResponse\Messenger\AdditionalQuestion2::create($command);
        } else if ($command instanceof GetDescription) {
            $response = \App\Commands\CommandResponse\Messenger\GetDescription::create($command);
        } else if ($command instanceof SetExtraUserFields) {
            $response = \App\Commands\CommandResponse\Messenger\SetExtraUserFields::create($command);
        }

        return $response;
    }
}
