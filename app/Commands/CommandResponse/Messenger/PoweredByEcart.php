<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Facebook\Messages\Buttons\Button;
use App\Facebook\Messages\Buttons\CallButton;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\Messages\Buttons\UrlButton;
use App\Facebook\Messages\Templates\ButtonTemplate;

class PoweredByEcart extends Response
{
    public static function create($command): Response
    {
        $response = new self();

        $buttonTemplate = ButtonTemplate::create();
        $buttonTemplate->editText(__('responses.powered_by_ecart_options.powered_button_message'));

        $buttonTemplate->addButton(
            CallButton::create()
                ->setTitle(__('responses.powered_by_ecart_options.click_to_call'))
                ->setPayload(__('responses.powered_by_ecart_options.phone_number'))
        );
        $buttonTemplate->addButton(
            UrlButton::create()
                ->setTitle(__('responses.powered_by_ecart_options.whatsapp_link_title'))
                ->setPayload(__('responses.powered_by_ecart_options.whats_app_link'))
        );
        $buttonTemplate->addButton(
            UrlButton::create()
                ->setTitle(__('responses.powered_by_ecart_options.visit_website'))
                ->setPayload(__('responses.powered_by_ecart_options.ecart_website'))
        );

        $response->responses = $buttonTemplate;
        return $response;
    }
}
