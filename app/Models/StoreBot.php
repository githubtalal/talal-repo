<?php

namespace App\Models;

use App\Traits\StoreAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use App\Traits\Loggable;


class StoreBot extends Model
{
    use HasFactory, StoreAccess, Loggable;

    protected $guarded = [];

    protected $dates = ['expires_at'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    function setTelegramWebhook()
    {
        //https://api.telegram.org/bot<token>/setWebhook?url=https://bot.madfox.solutions/api/telegrambot/3
        try {
            $url = URL::to('/') . '/api/telegrambot/' . $this->store_id;
            $endpoint = "https://api.telegram.org/bot" . $this->token . "/setWebhook" . "?url=$url";

            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', $endpoint, [
                //                'query' => [
                //                    'url' => route('telegrambot.get', [
                //                        'storeId' => $this->store->id,
                //                    ]),
                //                ],
                'verify' => false,
            ]);
            $statusCode = $response->getStatusCode();
            $content = $response->getBody();

            if ($statusCode == 200) {
                $this->setTelegramCommand();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            logger('[Telegram Connect] Error while setting telegram webhook ', [
                'store_id' => $this->store_id,
                'exception' => $e->getMessage(),
            ]);

            report($e);
            return false;
        }
    }

    function setTelegramCommand()
    {
        $handler = new TelegramMessageHandler();
        $handler->telegram = new TelegramBot($this->token);
        $handler->store_id = $this->store_id;
        $handler->sendCommands();
    }


    public function promotion_messages(){

        return $this->hasMany(PromotionMessage::class,'store_bot_id');
    }
}
