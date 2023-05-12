<?php

namespace App\Listeners;

use App\Facebook\Facebook;
use App\Facebook\Messages\Message;
use App\Models\StoreBot;
use App\Models\TelegramMessageHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChannelsMessages
{
    private $message;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;
        $cart = $order->cart;

        if (!$cart) return;

        $this->generateSuccessOrderMessage($order);

        if ($cart->platform == Facebook::SIGNATURE) {
            $this->sendMessengerMessage($cart);
        } else if ($cart->platform == TelegramMessageHandler::SIGNATURE) {
            $this->sendTelegramMessage($cart);
        }
    }

    private function sendTelegramMessage($cart)
    {
        $chatId = $cart->platform_id ?? null;
        $telegramBot = \App\Models\StoreBot::query()
            ->where('store_id', $cart->store_id)
            ->where('platform', 'telegram')
            ->where('token_type', 'api_key')
            ->first();
        if (!$chatId || !$telegramBot) return;
        $telegram = new \App\Models\TelegramBot($telegramBot->token);

        $content = [
            'chat_id' => $chatId,
            'text' => $this->message
        ];

        $telegram->sendMessage($content);
    }

    private function sendMessengerMessage($cart)
    {
        $facebookBot = StoreBot::query()->where([
            'store_id' => $cart->store_id,
            'platform' => Facebook::SIGNATURE,
            'token_type' => Facebook::PAGE_TOKEN,
        ])->first();
        if (!$facebookBot) return;

        $messageSender = Message::create($facebookBot->token)->setRecipient($cart->customer_uuid);
        $messageSender->sendText($this->message);
    }

    private function generateSuccessOrderMessage($order): void
    {
        if ($order->isPaid()) {
            $this->message = __('responses.checkout.payment_completed', [
                'order_id' => $order->id,
                'payment_id' => $order->trx_num,
            ]);
        } else {
            $this->message = __('responses.checkout.order_completed', ['order_id' => $order->id]);
        }
    }
}
