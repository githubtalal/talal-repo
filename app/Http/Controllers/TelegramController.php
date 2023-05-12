<?php

namespace App\Http\Controllers;

use App\Facebook\Facebook;
use App\Models\Product;
use App\Models\PromotionMessage;
use App\Models\StoreBot;
use App\Models\TelegramBot;
use App\Models\TelegramMessageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelegramController extends Controller
{
    public function view()
    {
        $store = auth()->user()->store;
        $bot = $store->bots()->where([
            'store_id' => $store->id,
            'platform' => 'telegram',
            'active' => true,
            'token_type' => 'api_key',
        ])->first();


        return view('newDashboard.bot.telegram', compact('bot'));
    }


    public function broadcast_form()
    {

        $store = auth()->user()->store;
        $bot = $store->bots()->where([
            'store_id' => $store->id,
            'platform' => 'telegram',
            'active' => true,
            'token_type' => 'api_key',
        ])->first();

        $products = [];
        $previous_channels = [];
        if ($bot) {
            $items = $bot->store->products()->get(['id', 'name']);

            if ($items)
                foreach ($items as $item)
                    $products[$item->id] = $item->name;
            $previous_channels = PromotionMessage::select('channel_id')->where(['store_bot_id' => $bot->id])->distinct('channel_id')->get();;

        } else {
            return redirect()->route('bot.telegram.view');
        }


        return view('newDashboard.bot.telegram_broadcast', compact('bot', 'products','previous_channels'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;
        $data = [
            'store_id' => $store->id,
            'platform' => 'telegram',
            'active' => true,
            'token_type' => 'api_key',
        ];
        $bot = $store->bots()->where($data)->first();
        if ($bot) {
            $bot->update([
                'token' => $request->token,
            ]);
        } else {
            $data['token'] = $request->token;
            $data['platform_id'] = $request->token;
            $bot = $store->bots()->create($data);
        }
        $bot->setTelegramWebhook();
        return redirect()->route('bot.telegram.view')->with('success_message', __('app.responses_messages.updated_successfully'));
    }

    public function broadcast(Request $request)
    {

        $product_id = $request->get('product_id');
        $product = Product::find($product_id);
        if (!$product)
            return redirect()->back();
        $textMessage = $product->name  . PHP_EOL;
        $channel_id = $request->channel_id;
        $image = Storage::disk('public')->url($product->image_url);
        $store = auth()->user()->store;
        $bot = $store->bots()->where([
            'store_id' => $store->id,
            'platform' => 'telegram',
            'active' => true,
            'token_type' => 'api_key',
        ])->first();
        $telegramBot = new TelegramBot($bot->token);
        if ($request->has('use_custom_message')) {
            $textMessage = $request->message;
            if ($request->hasFile('image'))
                $image = Storage::disk('public')->url($request->file('image')->storePublicly('telegram', ['disk' => 'public']));
        }



        if (!app()->isProduction())
            $image = 'https://th.bing.com/th/id/R.1cfe799c041f1d8e2695be2ff4cc4928?rik=zXQPiI93jjNArg&pid=ImgRaw&r=0';

       // return $request->previous_channel_id;

        $botInfo = $telegramBot->getMe();
        $mediaMessage = [
            'chat_id' => "@$channel_id",
            'photo' => $image,
            'caption' => $textMessage,
            'reply_markup' => $telegramBot->buildInlineKeyBoard([
                [
                    $telegramBot->buildInlineKeyboardButton(__('responses.cart.add_to_cart'), "https://t.me/{$botInfo['result']['username']}?start=$product_id"),
                ]
            ]),
        ];
        try {
            $message = $telegramBot->sendPhoto($mediaMessage);
            logger('Sent message to telegram channel', [
                'message' => $mediaMessage,
                'telegram_result' => $message
            ]);

            if ($message['ok']) {
                $promotion_message = PromotionMessage::create(['channel_id' => $channel_id, 'message_text' => $textMessage, 'image' => $image, 'store_id' => $store->id, 'store_bot_id' => $bot->id]);
                return redirect()->back()->with('success_message', 'تم إرسال الرسالة بنجاح');
            } else {
                return redirect()->back()->with('error_message', 'حدث خطأ في الإرسال,
                الرجاء المحاولة مرة أخرى.');
            }
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error_message', 'حدث خطأ في الإرسال,
                الرجاء المحاولة مرة أخرى.');
        }
    }
}
