<?php

namespace App\Facebook\Messages;

use App\Facebook\Messages\QuickReplies\Text;
use App\Facebook\Messages\Templates\Template;
use GuzzleHttp\Client;

class Message
{
    private $pageAccessToken;

    private $latestSentMessage = null;

    public static function create($pageAccessToken)
    {
        $instance = new self();
        $instance->pageAccessToken = $pageAccessToken;
        return $instance;
    }

    public function parseMessage($message)
    {
        // Get the fist element of the array
        if (isset($message['entry']) === false) return $this;
        $message = array_shift($message['entry'])['messaging'];
        $this->recipient = $message['recipient']['id'];
        $this->timestamp = $message['timestamp'];
        $this->sender = $message['sender']['id'];
        $this->message = $message['message'];
        $this->textMessage = $message['message']['text'] ?? null;
        $this->messageId = $message['message']['id'] ?? null;
        return $this;
    }

    public function markTypingOn()
    {
        $this->makeSenderActions('typing_on');
    }

    public function markTypingOff()
    {
        $this->makeSenderActions('typing_off');
    }

    public function markAsSeen()
    {
        $this->makeSenderActions('mark_seen');
    }

    public function makeSenderActions($action)
    {
        $message = $this->prepareMessage();
        $message['sender_action'] = $action;
        $this->sendMessage($message);
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function sendTemplate(Template $template)
    {
        $message = $this->prepareMessage();
        $message['message'] = $template->toArray();
        return $this->sendMessage($message);
    }

    public function sendQuickReply(Text $quickReply)
    {
        $message = $this->prepareMessage();
        $message['message'] = $quickReply->prepare();
        $message['messaging_type'] = 'RESPONSE';

        return $this->sendMessage($message);
    }

    public function sendText($text)
    {
        $message = $this->prepareMessage();
        $message['message'] = [
            'text' => $text,
        ];
        return $this->sendMessage($message);
    }

    public function sendFile($file)
    {
        // TODO: Implement sending file functionality
        // @link https://developers.facebook.com/docs/messenger-platform/send-messages#sending_attachments

        $message = $this->prepareMessage();
        $message['message'] = $file->getReply();
        return $this->sendMessage($message);
    }

    private function prepareMessage()
    {
        return [
            'recipient' => [
                'id' => $this->recipient,
            ],
        ];
    }

    /**
     * @param $message
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendMessage($message): ?string
    {
        try {
            $client = new Client();
            $httpResponse = $client->post('https://graph.facebook.com/v14.0/me/messages', [
                'query' => [
                    'access_token' => $this->pageAccessToken,
                ],
                'verify' => false,
                'json' => $message,
            ]);
            $response = json_decode((string)$httpResponse->getBody());
            if ($httpResponse->getStatusCode() === 200) {
                $this->latestSentMessage = $message;
                if ($response->message_id ?? null)
                    return $response->message_id;
            } else {
                logger('Sending message failed', [
                    'response' => $response,
                    'message' => $message,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            report($e);
            logger('Error while sending messenger message', [
                'exception' => $e->getMessage(),
                'message' => $message,
            ]);
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getLatestSentMessage()
    {
        return $this->latestSentMessage;
    }
}
