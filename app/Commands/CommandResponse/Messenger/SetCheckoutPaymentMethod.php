<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Cart;
use App\Commands\CommandParser;
use App\Commands\CommandResponse\CommandResponse;
use App\Commands\CommandResponse\CommandResponseHelper;
use App\Commands\CommandResponse\Response;
use App\Commands\GenerateInvoice;
use App\Commands\SetCartPaymentMethod;
use App\Commands\SetExtraUserFields;
use App\Facebook\Facebook;
use App\Facebook\Messages\Buttons\UrlButton;
use App\Facebook\Messages\Templates\ButtonTemplate;
use App\Models\Order;

class SetCheckoutPaymentMethod extends Response
{
    /**
     * @param SetCartPaymentMethod $command
     * @return Response
     */
    public static function create($command): Response
    {
        $response = new self();
        $cartClass = new Cart();
        $cart = $cartClass->getCart();
        $class = config('payment_methods.' . $cart->payment_method);
        $payment = new $class($cart->store_id, []);

        $cart = $command->getCart();

        $additional = $cart->additional;
        $additional_callback = [];

        if ($payment->getExtraUserFields()) {
            if (!$additional || !array_key_exists($cart->payment_method . '_is_complete', $additional) || !$additional[$cart->payment_method . '_is_complete']) {
                $additional[$cart->payment_method . '_is_complete'] = false;
                $cart->update(['additional' => $additional]);
                $response->expectReply = true;
                $response->expectedReply = SetExtraUserFields::class;
                $response->responses = $payment->getExtraUserFields()[0]['label'];
                return $response;
            } else {
                $additional_callback = $additional['additional_user_fields_' . $cart->payment_method];
                $additional[$cart->payment_method . '_is_complete'] = false;
                $additional['additional_fields'] = $additional_callback;
                $cart->update([
                    'additional' => $additional
                ]);
            }
        }

        $checkoutResult = $cartClass->checkout($cart);
        $paymentClass = getPaymentClassByKey($cart->payment_method);
        $payment = new $paymentClass($cart->store_id);

        if (is_string($checkoutResult)) {
            $buttonTemplate = ButtonTemplate::create();
            $button = UrlButton::create()->setTitle(__('responses.checkout.click_here'))->setPayload($checkoutResult);

            $buttonTemplate->addButton($button);

            $buttonTemplate->editText(__('responses.checkout.pay_with', [
                'payment_method' => $payment->getLabel(),
            ]));

            $response->responses = $buttonTemplate;

        } else if ($checkoutResult instanceof Order) {
            // Generate Invoice Response
            $payload = GenerateInvoice::buildPayload([
                GenerateInvoice::ORDER_INDEX => $checkoutResult->id,
            ]);

            $invoice = CommandParser::parsePayload($payload);
            $invoiceResponse = CommandResponse::create($invoice)->getResponse();
            $theResponse = [];

            if ($payment->getDescription()) {
                $theResponse[] = str_replace('<br>', chr(10), $payment->getDescription());
            }

            $theResponse[] = __('responses.checkout.order_completed', ['order_id' => $checkoutResult->id]);

            // send additional message that store defined it.
            $additional_message = get_additional_message();
            if ($additional_message) {
                $theResponse[] = $additional_message;
            }

            $response->responses = $theResponse;
        } else {
            $response->responses = CommandResponseHelper::generatePaymentMethodsResponse('responses.checkout.payment_failed');

        }

        return $response;
    }

    public function getAdditionalData(): array
    {
        return [
            0,
        ];
    }
}
