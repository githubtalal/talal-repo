<?php

namespace App\Http\Controllers;

use App\Commands\CommandParser;
use App\Commands\CommandResponse\CommandResponse;
use App\Facebook\Messages\Events\MessageParser;
use App\Facebook\Messages\Events\Messaging;
use App\Facebook\Messages\Events\Postback;
use App\Facebook\Messages\Message;
use App\Facebook\Messages\QuickReplies\File;
use App\Facebook\Messages\QuickReplies\Text;
use App\Facebook\Messages\Templates\Template;
use App\Models\BotMessages;
use App\Models\StoreBot;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FacebookWebhookController extends Controller
{
    private $token = 'arKmUAvgn244G3siQ5dg';

    public function verify(Request $request)
    {
        logger('Facebook hooks verification', request()->all());
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $this->token) {
                logger('Facebook hooks verified', request()->all());
                return response($challenge);
            }
        }
    }

    public function reply(Request $request)
    {

        $validatedRequest = $this->verifyWebhooksSignature();

        if (!$validatedRequest) {
            logger('Failed signature verification while listening to facebook webhooks', [
                'request_body' => request()->all(),
                'request_content' => request()->getContent(),
                'ip_address' => request()->ip(),
            ]);
            return response('Forbidden', 403)->send();
        }

        logger('New message from facebook webhooks', $request->all());
        try {
            $messageLog = [];

            $event = MessageParser::parse($request->all());

            // Handle ended subscription
            $storeMessengerBot = StoreBot::query()->where([
                'platform' => 'facebook',
                'platform_id' => $event->getRecipientId(),
            ])->first();

            if (!$storeMessengerBot) {
                logger('Message received from expired/inactive store', [
                    'request_body' => request()->all(),
                    'request_content' => request()->getContent(),
                    'ip_address' => request()->ip(),
                ]);
                return response('Forbidden', 403)->send();
            }

            if (!storeHasPermission('messenger_bot', $storeMessengerBot->store_id)) {
                logger('Store is inactive/ has expired feature');
                return response('Forbidden', 403)->send();
            }

            $messageLog['sender_id'] = $event->getSenderId();

            request()->request->set('user_id', $event->getSenderId());
            request()->request->set('store_id', $storeMessengerBot->store_id);

            $messageLog['recipient_id'] = $event->getRecipientId();
            $messageLog['platform'] = 'facebook';
            $messageLog['message_id'] = $event->getMessageId();

            $messageSender = Message::create($storeMessengerBot->token)->setRecipient($event->getSenderId());
            $isPostBack = ($event instanceof Messaging && $event->isPostBack()) || $event instanceof Postback;

            $response = null;

            if ($isPostBack) {
                $messageSender->markAsSeen();
                $command = CommandParser::parsePayload($event->getPayload());

                if ($command) {
                    $messageLog['message_type'] = get_class($event);

                    $messageLog['message_text'] = $event->getPayload();

                    $messageLog['additional'] = [
                        'message_body' => request()->all(),
                    ];

                    logger('Command received', [
                        'command' => get_class($command),
                        'payload' => $event->getPayload(),
                        'sender' => $event->getSenderId(),
                        'receiver' => $event->getRecipientId(),
                    ]);

                    $command->run();
                    $response = CommandResponse::create($command);
                }
            } else {

                $latestMessage = BotMessages::query()->where([
                    'sender_id' => $event->getSenderId(),
                    'platform' => 'facebook',
                    'recipient_id' => $event->getRecipientId(),
                ])->latest()->first();

                if ($latestMessage && $latestMessage->additional['reply_expected']) {
                    $messageSender->markAsSeen();
                    $expectedCommand = call_user_func([$latestMessage->additional['expected_reply'], 'create'], array_merge($latestMessage->additional['expected_reply_payload'], [$event->getTextMessage()]));
                    $expectedCommand->run();
                    $response = CommandResponse::create($expectedCommand);
                }
            }
            if ($response) {
                $replies = [];
                $messageSender->markTypingOn();
                foreach (Arr::wrap($response->getResponse()) as $reply) {
                    if (is_string($reply)) {
                        $messageSender->sendText($reply);
                    } else if ($reply instanceof Template) {
                        $messageSender->sendTemplate($reply);
                    } else if ($reply instanceof Text) {
                        $messageSender->sendQuickReply($reply);
                    } else if ($reply instanceof File) {
                        $messageSender->sendFile($reply);
                    }

                    $replies[] = $messageSender->getLatestSentMessage();
                }

                $messageLog['additional']['expected_reply'] = $response->expectedReply();
                $messageLog['additional']['reply_expected'] = $response->replyExpected();
                $messageLog['additional']['reply_body'] = $replies;
                $messageLog['additional']['expected_reply_payload'] = $response->getAdditionalData();
                $messageLog['reply_type'] = get_class($response);
                $messageLog['reply'] = get_class($response);
                BotMessages::create($messageLog);
            }
            $messageSender->markTypingOff();
        } catch (\Throwable$e) {
            logger('Error while handling facebook', [
                'store_id' => isset($storeMessengerBot) ? $storeMessengerBot->store_id : null,
                'exception' => $e->getMessage(),
                'full_trace' => $e->getTrace(),
                'raw_request' => $request->toArray(),
            ]);
            if (isset($messageSender)) {
                $messageSender->sendText('حدث خطأ ما, يرجى المحاولة لاحقاً.');
            }

        }
    }

    private function verifyWebhooksSignature()
    {
        $signature = request()->header('X_HUB_SIGNATURE');
        $content = request()->getContent();

        return hash_equals(
            $signature,
            'sha1=' . hash_hmac('sha1', $content, config('hooks.facebook_app_secret'))
        );
    }
}
