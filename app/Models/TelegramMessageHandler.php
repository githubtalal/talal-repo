<?php

namespace App\Models;

use App\Cart;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;


class TelegramMessageHandler extends Model
{
    use HasFactory, Loggable;

    /**
     * @var TelegramBot|null null
     */
    public $telegram = null;
    public $store_id = null;
    public $product_limit = 1;

    const SIGNATURE = 'telegram';
    //declare states
    const CATEGORIES = '_S_S10';
    const SUB_CATEGORIES = '_S_S11';
    const PRODUCTS = '_S_S20';
    const CART = '_S_C10';
    const ADD_TO_CART = '_S_C11';
    const MONTH_IN = '_S_C111';
    const DAY_IN = '_S_C112';
    const MONTH_OUT = '_S_C113';
    const DAY_OUT = '_S_C114';
    const REMOVE_FROM_CART = '_S_C12';
    const CHECKOUT = '_S_C20';
    const CHECKOUT_START = '_S_C21';
    const CHECKOUT_TELEGRAM_DATA = '_S_C22';
    const CHECKOUT_FIRST_NAME = '_S_C23';
    const CHECKOUT_LAST_NAME = '_S_C24';
    const CHECKOUT_CITY = '_S_C25';
    const CHECKOUT_HANDEL_CITY = '_S_C26';
    const CHECKOUT_ADDRESS = '_S_C27';
    const CHECKOUT_PHONE = '_S_C28';
    const CHECKOUT_PAYMENT = '_S_C30';
    const CHECKOUT_PAYMENT_METHOD = '_S_payment_method';
    const CHECKOUT_PAYMENT_COD = '_S_payment_cod';
    const CHECKOUT_PAYMENT_FATORA = '_S_payment_fatora';
    const CHECKOUT_PAYMENT_MTN = '_S_payment_mtn_cash';
    const CHECKOUT_PAYMENT_SYRIATEL = '_S_payment_syriatel_cash';
    const CHECKOUT_ADDITIONAL_Q1 = '_S_additional-q1';
    const CHECKOUT_ADDITIONAL_Q2 = '_S_additional-q2';
    const ORDER_DETAILS = '_S_O10';
    const SEND_MONTHS = '_S_send-months';
    const SEND_MONTHS_LIST = '_S_send-months-list';
    const SEND_HOUR = '_S_send-hour';
    const SET_HOUR = '_S_set-hour';
    const SET_NOTES = '_S_set-notes';
    const DELIMITER = "*^*";
    const HELP = '_S_help';
    const FAQ = '_S_faq';
    const ABOUTUS = '_S_about-us';
    const CONTACTUS = '_S_contact-us';
    const CLICKTOCALL = '_S_click-to-call';
    const VISITWEBSITE = '_S_visit-website';
    const POWEREDBYECART = '_S_powered_by_ecart';
    const DESCRIPTION = '_S_description';
    const USEREXTRAFIELDS = 'user_extra_fields';

    public function consumeMessage()
    {
        try {
            $text = $this->telegram->Text();
            session(['store_id' => $this->store_id]);
            session(['user_id' => $this->telegram->UserID()]);
            $callback_query = $this->telegram->Callback_Query();

            //check if message
            if (isset($callback_query['data'])) {
                \Log::debug('callback: ' . $callback_query['data']);
                $this->handelCallbackMessage($callback_query['data']);
            } else {
                \Log::debug('message: ' . $text);
                $this->handelCommandMessage($text);
            }
        } catch (Exception $e) {
            logger('Error while handling telegram message', [
                'error_message' => $e->getMessage(),
                'full_trace' => $e->getTraceAsString(),
                'store_id' => $this->store_id,
                'user_id' => $this->telegram->UserID(),
            ]);
        }
    }

    public function parseMessage($message)
    {
        $result = ['category' => null, 'product' => null, 'state' => null, 'index' => 1, 'year' => 0, 'month' => 0, 'day' => 0, 'city' => null, 'question' => null];

        $messages = explode(TelegramMessageHandler::DELIMITER, $message);
        foreach ($messages as $message) {
            //var_dump($message);
            if (strpos($message, '_S_') !== false) {
                $result['state'] = str_replace('_S_', '', $message);
            }
            if (strpos($message, '_C_') !== false) {
                $result['category'] = str_replace('_C_', '', $message);
            }
            if (strpos($message, '_P_') !== false) {
                $result['product'] = str_replace('_P_', '', $message);
            }
            if (strpos($message, '_I_') !== false) {
                $result['index'] = str_replace('_I_', '', $message);
            }
            if (strpos($message, '_Y_') !== false) {
                $result['year'] = str_replace('_Y_', '', $message);
            }
            if (strpos($message, '_M_') !== false) {
                $result['month'] = str_replace('_M_', '', $message);
            }
            if (strpos($message, '_D_') !== false) {
                $result['day'] = str_replace('_D_', '', $message);
            }
            if (strpos($message, '_G_') !== false) {
                $result['city'] = str_replace('_G_', '', $message);
            }
            if (strpos($message, '_T_') !== false) {
                $result['type'] = str_replace('_T_', '', $message);
            }
            if (strpos($message, '_H_') !== false) {
                $result['hour'] = str_replace('_H_', '', $message);
            }
            if (strpos($message, '_Q_') !== false) {
                $result['question'] = str_replace('_Q_', '', $message);
            }
            if (strpos($message, '_PM_') !== false) {
                $result['payment'] = str_replace('_PM_', '', $message);
            }

        }
        return $result;
    }

    public function handelCallbackMessage($message)
    {
        $message = $this->parseMessage($message);
        \Log::debug('parsed massege: ', $message);
        if (!('_S_' . $message['state'] == TelegramMessageHandler::PRODUCTS)) {
            if ('_S_' . $message['state'] == TelegramMessageHandler::CATEGORIES) {
                $this->sendCategories();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::PRODUCTS) {
                $this->sendProducts($message['category'], $message['index']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CART) {
                $this->sendCart();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::ADD_TO_CART) {
                $this->addToCart($message['product']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::REMOVE_FROM_CART) {
                $this->removeFromCart($message['product']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::MONTH_IN) {
                $this->handelMonth('in', $message['year'], $message['month'], $message['day']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::DAY_IN) {
                $this->handelDay('in', $message['day']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::MONTH_OUT) {
                $this->handelMonth('out', $message['year'], $message['month'], $message['day']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::DAY_OUT) {
                $this->handelDay('out', $message['day']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT) {
                $this->sendCheckout();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_START) {
                $this->updateStoredMessageAdditional('current_state', TelegramMessageHandler::CHECKOUT_FIRST_NAME);
                $this->sendMessage(__('responses.checkout.enter_first_name'));
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_HANDEL_CITY) {
                $this->updateStoredMessageAdditional('city', $message['city']);

                $result = $this->getNextStep(TelegramMessageHandler::CHECKOUT_CITY);

                $this->updateStoredMessageAdditional('current_state', $result['nextStep']);

                return $result['response'];
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_TELEGRAM_DATA) {
                $this->processCheckout();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_PAYMENT_METHOD) {
                $this->updateStoredMessageAdditional('method', $message['payment']);
                $this->handlePaymentMethod($message['payment']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_PAYMENT_COD) {
                $this->handelPaymentCod();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_PAYMENT_FATORA) {
                $this->handelPaymentFatora();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_PAYMENT_MTN) {
                $this->handelPaymentMtn();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CHECKOUT_PAYMENT_SYRIATEL) {
                $this->handelPaymentSyriatel();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::SEND_MONTHS) {
                $this->sendMonths($message['type'], $message['index'] === 1 ? 0 : $message['index'], $message['year'], $message['month']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::SEND_MONTHS_LIST) {
                $this->sendMonthList($message['type']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::SET_HOUR) {
                $this->setCheckDate($message['hour'], $message['type']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::HELP) {
                $this->sendHelp();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::FAQ and $message['question'] == null) {
                $this->sendFAQs();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::FAQ and $message['question'] != null) {
                $this->sendAnswer($message['question']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::ABOUTUS) {
                $this->sendAboutUs();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CONTACTUS) {
                $this->sendContactUs();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::POWEREDBYECART) {
                $this->sendPoweredByEcart();
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::DESCRIPTION) {
                $this->sendDescription($message['product']);
            } else if ('_S_' . $message['state'] == TelegramMessageHandler::CLICKTOCALL) {
                $this->sendPhoneNumber();
            }
            $this->updateStoredMessageAdditional('replaceable_message', null);
        } else {
            $this->sendProducts($message['category'], $message['index']);
        }
    }

    public function sendMessage($message, $chat_id = null)
    {
        $chat_id = $chat_id ?? $this->telegram->ChatID();
        \Log::debug('sending massege: ' . $message . ' Chat Id: ' . $chat_id);
        $content = ['chat_id' => $chat_id, 'text' => $message];
        $this->telegram->sendMessage($content);
    }

    public function sendImage($url, $chat_id = null)
    {
        // $img = curl_file_create($url, 'image/jpg');
        $chat_id = $chat_id ?? $this->telegram->ChatID();
        $content = array('chat_id' => $chat_id, 'photo' => $url);
        return $this->telegram->sendPhoto($content);
    }

    public function handelCommandMessage($message)
    {
        //logger($message);
        //comannds
        $this->updateStoredMessageAdditional('replaceable_message', null);
        if (str_starts_with($message, '/')) {
            $parts = explode(' ', $message);
            //store
            if (count($parts) == 2 && ($parts[0] == '/start')) {
                $this->sendProduct($parts[1]);
            } else if (($message == '/start') || $message == '/store') {
                //                $this->sendMonths('in');
                $this->sendCategories();
            } else if ($message == '/cart') {
                $this->sendCart();
            } else if ($message == '/contact') {
                $this->sendContactUs();
            }

            //$this->sendCategories();
            else if ($message == '/about') {
                $this->sendAboutUs();
            } else if ($message == '/faq') {
                $this->sendFAQs();
            } else if ($message == '/poweredbyecart') {
                $this->sendPoweredByEcart();
            }

            //$this->sendCategories();
            //            else if($message == '/test' )
            //                $this->test();
        } else {
            //reply without callback
            //CHECK MESSAGE
            $storedMessage = $this->getStoredMessage();
            $additional = json_decode($storedMessage->additional);
            $state = $additional->current_state;
            Log::info(array($storedMessage));
            if ($state == TelegramMessageHandler::CHECKOUT_FIRST_NAME) {
                $additional->current_state = TelegramMessageHandler::CHECKOUT_PHONE;

                $name = explode(' ', $message);
                $first_name = $name[0];
                array_shift($name);
                $last_name = implode(' ', $name);

                $additional->first_name = $first_name;
                $additional->last_name = $last_name;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
                $this->sendMessage(__('responses.checkout.enter_phone'));
                //$this->sendMessage(__('responses.checkout.enter_last_name'));
            } /*else if ($state == TelegramMessageHandler::CHECKOUT_LAST_NAME) {
            $additional->last_name = $message;
            $additional->current_state = TelegramMessageHandler::CHECKOUT_CITY;
            $storedMessage->additional = json_encode($additional);
            $storedMessage->save();
            // $this->sendMessage(__('responses.checkout.customer_governorate'));
            $this->sendCity();
            }*/else if ($state == TelegramMessageHandler::CHECKOUT_PHONE) {

                $additional->phone = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == TelegramMessageHandler::CHECKOUT_CITY) {

                $additional->city = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == TelegramMessageHandler::CHECKOUT_ADDRESS) {

                $additional->address = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == TelegramMessageHandler::SET_NOTES) {

                // we saved the user message in additional message before calling send payment methods function (in case we have it),
                // because we update the cart there se we have to send the data before

                $additional->notes = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1) {

                $additional->additional_question1 = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2) {
                $additional->additional_question2 = $message;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $result = $this->getNextStep($state);

                $additional->current_state = $result['nextStep'];
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            } else if ($state == self::SEND_HOUR) {
                $this->sendMinutes(translateNumber($message), $additional->current_direction ?? 'in');
            } else if ($state == TelegramMessageHandler::USEREXTRAFIELDS) {
                $additional = json_decode($storedMessage->additional, true);
                $method = $additional['method'];
                $current_idx = $additional['extra_field_idx'];
                $currentItem = $this->getUserExtraField($method, $current_idx);
                $additional['user_extra_fields'][$currentItem['name']] = $message;

                $current_idx++;
                $nextItem = $this->getUserExtraField($method, $current_idx);
                if (!$nextItem) {
                    $additional['current_state'] = '';
                    $additional[$method . '_is_complete'] = true;
                    $storedMessage->additional = json_encode($additional);
                    $storedMessage->save();
                    $this->handlePaymentMethod($method);
                    return;
                }
                $additional['extra_field_idx'] = $current_idx;
                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $this->sendMessage($nextItem['label']);

            } else
            //echo the message for now
            {
                $this->sendMessage($message);
            }

        }
    }

    public function getNextStep($currentStep)
    {
        $steps = getStoreSteps();

        if (!$steps) {
            return [
                'nextStep' => '',
                'response' => $this->sendPayment(),
            ];
        }

        if ($currentStep == TelegramMessageHandler::CHECKOUT_PHONE) {
            if (array_key_exists('governorate', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_CITY;
                $response = $this->sendCity();
            } else if (array_key_exists('address', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDRESS;
                $response = $this->sendMessage(__('responses.checkout.enter_address'));
            } else if (array_key_exists('notes', $steps)) {
                $nextStep = TelegramMessageHandler::SET_NOTES;
                $response = $this->sendNotes();
            } else if (array_key_exists('question1', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1;
                $response = $this->send_additional_question1();
            } else if (array_key_exists('question2', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2;
                $response = $this->send_additional_question2();
            }
        } else if ($currentStep == TelegramMessageHandler::CHECKOUT_CITY) {
            if (array_key_exists('address', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDRESS;
                $response = $this->sendMessage(__('responses.checkout.enter_address'));
            } else if (array_key_exists('notes', $steps)) {
                $nextStep = TelegramMessageHandler::SET_NOTES;
                $response = $this->sendNotes();
            } else if (array_key_exists('question1', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1;
                $response = $this->send_additional_question1();
            } else if (array_key_exists('question2', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2;
                $response = $this->send_additional_question2();
            } else {
                $nextStep = '';
                $response = $this->sendPayment();
            }
        } else if ($currentStep == TelegramMessageHandler::CHECKOUT_ADDRESS) {
            if (array_key_exists('notes', $steps)) {
                $nextStep = TelegramMessageHandler::SET_NOTES;
                $response = $this->sendNotes();
            } else if (array_key_exists('question1', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1;
                $response = $this->send_additional_question1();
            } else if (array_key_exists('question2', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2;
                $response = $this->send_additional_question2();
            } else {
                $nextStep = '';
                $response = $this->sendPayment();
            }
        } else if ($currentStep == TelegramMessageHandler::SET_NOTES) {
            if (array_key_exists('question1', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1;
                $response = $this->send_additional_question1();
            } else if (array_key_exists('question2', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2;
                $response = $this->send_additional_question2();
            } else {
                $nextStep = '';
                $response = $this->sendPayment();
            }
        } else if ($currentStep == TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q1) {
            if (array_key_exists('question2', $steps)) {
                $nextStep = TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2;
                $response = $this->send_additional_question2();
            } else {
                $nextStep = '';
                $response = $this->sendPayment();
            }
        } else if ($currentStep == TelegramMessageHandler::CHECKOUT_ADDITIONAL_Q2) {
            $nextStep = '';
            $response = $this->sendPayment();
        }

        return [
            'nextStep' => $nextStep,
            'response' => $response,
        ];
    }

    public function sendNotes()
    {
        $chat_id = $this->telegram->ChatID();

        // get notes label
        $label = __('responses.cart.enter_notes');

        $bot_settings = StoreSettings::where([
            ['store_id', $this->store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes']) {
                $label = $bot_settings['notes'];
            }

        }

        $content = ['chat_id' => $chat_id, 'text' => $label];
        // $this->updateStoredMessageAdditional('current_state', TelegramMessageHandler::SET_NOTES);
        $message = $this->telegram->sendMessage($content);
    }

    public function send_additional_question1()
    {
        $chat_id = $this->telegram->ChatID();

        $q1Value = __('app.steps.question1');

        $bot_settings = StoreSettings::where([
            ['store_id', $this->store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('question1', $bot_settings) && $bot_settings['question1']) {
                $q1Value = $bot_settings['question1'];
            }

        }

        $content = ['chat_id' => $chat_id, 'text' => $q1Value];
        $this->telegram->sendMessage($content);
    }

    public function send_additional_question2()
    {
        $chat_id = $this->telegram->ChatID();

        $q2Value = __('app.steps.question2');

        $bot_settings = StoreSettings::where([
            ['store_id', $this->store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('question2', $bot_settings) && $bot_settings['question2']) {
                $q2Value = $bot_settings['question2'];
            }

        }

        $content = ['chat_id' => $chat_id, 'text' => $q2Value];
        $this->telegram->sendMessage($content);
    }

    /*function setNotes($notes)
    {
    $cart = $this->getCart();
    $cart->update([
    'notes' => $notes,
    ]);
    $this->sendPayment();
    }*/

    public function ifHashelpSection()
    {
        if ($this->getAboutUs() || $this->getQuestions() || $this->getContactUs() || $this->ifPoweredButtonEnabled()) {
            return true;
        }

        return false;
    }

    public function sendHelp()
    {
        $chat_id = $this->telegram->ChatID();
        $help = [];

        if ($this->getQuestions()) {
            $help[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.help.faq'), $url = '', $callback_data = TelegramMessageHandler::FAQ . TelegramMessageHandler::DELIMITER),
            ];
        }

        if ($this->getAboutUs()) {
            $help[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.help.about-us'), $url = '', $callback_data = TelegramMessageHandler::ABOUTUS . TelegramMessageHandler::DELIMITER),
            ];
        }

        if ($this->getContactUs()) {
            $help[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.help.contact-us'), $url = '', $callback_data = TelegramMessageHandler::CONTACTUS . TelegramMessageHandler::DELIMITER),
            ];
        }

        if ($this->ifPoweredButtonEnabled()) {
            $help[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.help.Powered-By-Ecart'), $url = '', $callback_data = TelegramMessageHandler::POWEREDBYECART . TelegramMessageHandler::DELIMITER),
            ];
        }

        $keyb = $this->telegram->buildInlineKeyBoard($help);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => __('responses.help.help-section')];
        $message = $this->telegram->sendMessage($content);
        //logger($message);
    }

    public function sendFAQs()
    {
        $chat_id = $this->telegram->ChatID();
        $store_questions = $this->getQuestions();
        $questions = [];
        $index = 0;

        if (!$store_questions) {
            $this->sendMessage(__('responses.faq.empty_questions'));
            return;
        }

        foreach ($store_questions as $object) {
            $questions[] = [
                $this->telegram->buildInlineKeyBoardButton($object->question, $url = '', $callback_data = TelegramMessageHandler::FAQ . TelegramMessageHandler::DELIMITER . '_Q_' . $index . TelegramMessageHandler::DELIMITER),
            ];
            $index++;
        }

        $keyb = $this->telegram->buildInlineKeyBoard($questions);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => __('responses.faq.questions')];
        $this->telegram->sendMessage($content);
    }

    public function sendAnswer($question)
    {
        $objects = $this->getQuestions();
        $object = $objects[$question];
        $this->sendMessage(__('responses.faq.the-answer') . "\n" . $object->answer);
    }

    public function getQuestions()
    {
        $questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', $this->store_id],
        ])->first();

        if ($questions) {
            $questions = json_decode($questions->value);
        }

        return $questions;
    }

    public function sendContactUs()
    {
        $value = $this->getContactUs();

        if (!$value) {
            $this->sendMessage(__('responses.help.content-empty'));
        } else {
            $this->sendMessage(__('responses.help.contact-us') . "\n" . $value);
        }
    }

    public function getContactUs()
    {
        $value = null;
        $contact_us = StoreSettings::where([
            ['key', 'Contact_Us'],
            ['store_id', $this->store_id],
        ])->first();

        if ($contact_us) {
            $value = json_decode($contact_us->value);
        }

        return $value;
    }

    public function sendAboutUs()
    {
        $value = $this->getAboutUs();

        if (!$value) {
            $this->sendMessage(__('responses.help.content-empty'));
        } else {
            $this->sendMessage(__('responses.help.about-us') . "\n" . $value);
        }
    }

    public function getAboutUs()
    {
        $value = null;
        $aboutUs = StoreSettings::where([
            ['key', 'About_Us'],
            ['store_id', $this->store_id],
        ])->first();

        if ($aboutUs) {
            $value = json_decode($aboutUs->value);
        }

        return $value;
    }

    public function ifPoweredButtonEnabled()
    {
        $ifEnabled = false;

        $bot_settings = StoreSettings::where([
            ['store_id', $this->store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            if (array_key_exists('power_button', $bot_settings->value)) {
                $ifEnabled = true;
            }

        }

        return $ifEnabled;
    }

    public function sendPoweredByEcart()
    {
        $chat_id = $this->telegram->ChatID();
        $text = __('responses.powered_by_ecart_options.powered_button_message');
        $buttons = [
            [
                $this->telegram->buildInlineKeyBoardButton(__('responses.powered_by_ecart_options.click_to_call'), $url = '', $callback_data = TelegramMessageHandler::CLICKTOCALL . TelegramMessageHandler::DELIMITER),
            ],
            [
                $this->telegram->buildInlineKeyBoardButton(__('responses.powered_by_ecart_options.whatsapp_link_title'), $url = __('responses.powered_by_ecart_options.whats_app_link'), $callback_data = TelegramMessageHandler::VISITWEBSITE . TelegramMessageHandler::DELIMITER),
            ],
            [
                $this->telegram->buildInlineKeyBoardButton(__('responses.powered_by_ecart_options.visit_website'), $url = __('responses.powered_by_ecart_options.ecart_website'), $callback_data = TelegramMessageHandler::VISITWEBSITE . TelegramMessageHandler::DELIMITER),
            ],
        ];
        $keyb = $this->telegram->buildInlineKeyBoard($buttons);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text];
        $message = $this->telegram->sendMessage($content);
        logger('message', $message);
    }

    public function sendPhoneNumber()
    {
        $this->sendMessage(__('responses.powered_by_ecart_options.phone_number'));
    }

    public function sendCategories()
    {
        $temp_cats = $this->getCategories();
        logger('addddddddddddddd' . count($temp_cats));
        $chat_id = $this->telegram->ChatID();
        $option = [];
        $option_row = [];

        if (count($temp_cats) == 1) {
            $this->sendProducts($temp_cats[0]->id, 1);
            return;
        }

        $index = 0;
        //prepare categories 2 each row
        foreach ($temp_cats as $temp_cat) {
            array_push($option_row, $this->telegram->buildInlineKeyBoardButton($temp_cat->name, $url = '', $callback_data = TelegramMessageHandler::PRODUCTS . TelegramMessageHandler::DELIMITER . '_C_' . $temp_cat->id . TelegramMessageHandler::DELIMITER));
            if ($index % 2 == 1) {
                array_push($option, $option_row);
                $option_row = [];
            }
            $index++;
        }
        if (count($option_row) > 0) {
            array_push($option, $option_row);
        }

        // build buttons
        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => __('responses.categories.select')];
        $message = $this->telegram->sendMessage($content);
        logger('message', $message);
    }

    public function sendProducts($category, $page)
    {
        //\Log::debug('sendProducts: ');
        $chat_id = $this->telegram->ChatID();
        $products = $this->getCategoryProductsByPage($category, $page, 1);
        $product = end($products);
        $replaceableMessage = $this->getStoredMessage();
        $replaceableMessage = (json_decode($replaceableMessage->additional, 1)['replaceable_message']) ?? null;

        if (!$product) {
            $this->sendMessage('no products');
            return;
        };

        //send options buttons
        $option = [];

        if ($product->description) {
            $option[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.products.description'), $url = '', $callback_data = TelegramMessageHandler::DESCRIPTION . TelegramMessageHandler::DELIMITER . '_P_' . $product->id . TelegramMessageHandler::DELIMITER),
            ];
        }

        $option[] = [
            $this->telegram->buildInlineKeyBoardButton(__('responses.cart.add_to_cart'), $url = '', $callback_data = TelegramMessageHandler::ADD_TO_CART . TelegramMessageHandler::DELIMITER . '_P_' . $product->id . TelegramMessageHandler::DELIMITER),
        ];

        $pagination = [];
        if (count($this->getCategoryProductsByPage($category, $page - 1, 1))) {
            $pagination[] =
            $this->telegram->buildInlineKeyBoardButton(__('responses.products.prev'), $url = '', $callback_data = TelegramMessageHandler::PRODUCTS . TelegramMessageHandler::DELIMITER . '_C_' . $category . TelegramMessageHandler::DELIMITER . '_I_' . ($page > 1 ? $page - 1 : 1) . TelegramMessageHandler::DELIMITER);
        }

        if (count($this->getCategoryProductsByPage($category, $page + 1, 1))) {
            $pagination[] =
            $this->telegram->buildInlineKeyBoardButton(__('responses.products.next'), $url = '', $callback_data = TelegramMessageHandler::PRODUCTS . TelegramMessageHandler::DELIMITER . '_C_' . $category . TelegramMessageHandler::DELIMITER . '_I_' . ($page + 1) . TelegramMessageHandler::DELIMITER);
        }

        if (count($pagination)) {
            $option[] = $pagination;
        }

        if ($this->getCart() && $this->getCart()->items()->count()) {
            $option[] = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.cart.cart'), $url = '', $callback_data = TelegramMessageHandler::CART . TelegramMessageHandler::DELIMITER),
            ];
        }

        $option[] = [
            $this->telegram->buildInlineKeyBoardButton(__('responses.categories.return_to_categories'), $url = '', $callback_data = TelegramMessageHandler::CATEGORIES . TelegramMessageHandler::DELIMITER),
        ];

        $keyb = $this->telegram->buildInlineKeyBoard($option);

        $productModel = Product::find($product->id);

        $text = $this->buildProductTotalsMessage($productModel);

        // $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text];

        // handle imgs in pro & local
        if (ifAppLocal()) {
            $url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Image_created_with_a_mobile_phone.png/640px-Image_created_with_a_mobile_phone.png';
        } else {
            $url = $product->cover;
        }

        $mediaMessage = [
            'chat_id' => $chat_id,
            'photo' => $url,
            'caption' => $text,
            'reply_markup' => $keyb,
        ];
        // $sentMessage = $this->telegram->sendMessage($mediaMessage);

        if ($replaceableMessage) {
            $mediaMessage = [
                'chat_id' => $this->telegram->ChatID(),
                'media' => json_encode([
                    'type' => 'photo',
                    'media' => $url,
                    'caption' => $text,
                ]),
                'message_id' => $replaceableMessage,
                'reply_markup' => $keyb,
            ];
            $sentMessage = $this->telegram->editMessageMedia($mediaMessage);
        } else {
            $message = $this->telegram->sendPhoto($mediaMessage);
            $this->updateStoredMessageAdditional('replaceable_message', $message['result']['message_id']);
        }
    }

    public function sendDescription($product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            return;
        }

        $description = preg_replace('/(<\/p>|<\/h1>|<\/h2>)/', "$1\n", $product->description);
        $formattedDescription = html_entity_decode(strip_tags($description));
        $this->sendMessage($formattedDescription);
    }

    public function sendProduct($product_id)
    {
        $option = [];
        $product = Product::find($product_id);
        if (!$product) {
            return;
        }

        $option[] = [
            $this->telegram->buildInlineKeyBoardButton(__('responses.cart.add_to_cart'), $url = '', $callback_data = TelegramMessageHandler::ADD_TO_CART . TelegramMessageHandler::DELIMITER . '_P_' . $product_id . TelegramMessageHandler::DELIMITER),
        ];
        $keyb = $this->telegram->buildInlineKeyBoard($option);

       $text = $this->buildProductTotalsMessage($product);
        //        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text];
        $image = Storage::disk('public')->url($product->image_url);

        $mediaMessage = [
            'chat_id' => $this->telegram->ChatID(),
            'photo' => $image,
            'caption' => $text,
            'reply_markup' => $keyb,
        ];
        $message = $this->telegram->sendPhoto($mediaMessage);
    }

    public function updateProduct($product, $image_message, $message)
    {

        // \Log::debug('send Product: ',$product);

        $chat_id = $this->telegram->ChatID();

        if (count($product->album) > 0) {
            $media = [['type' => 'photo', 'media' => 'attach://' . $product->id . '_cover.png']];
            $files = [$product->id . '_cover.png' => curl_file_create($product->cover)];
            $index = 1;
            foreach ($product->album as $img) {
                $name = $product->id . '_' . $index . '.png';
                array_push($media, ['type' => 'photo', 'media' => 'attach://' . $name]);
                $files[$name] = curl_file_create($img, '', $name);

                $index++;
            }

            $postContent = [
                'chat_id' => $chat_id,
                'media' => json_encode($media),

            ];
            $postContent = array_merge($postContent, $files);
            $image_message = $this->telegram->sendMediaGroup($postContent);
        } else {
            //send  product cover image
            $image_message = $this->sendImage($product->cover);
        }
        //send text with buttons
        //create buttons
        $option = [
            $this->telegram->buildInlineKeyBoardButton(__('responses.cart.add_to_cart'), $url = '', $callback_data = TelegramMessageHandler::ADD_TO_CART . TelegramMessageHandler::DELIMITER . '_P_' . $product->id . TelegramMessageHandler::DELIMITER),
        ];
        $keyb = $this->telegram->buildInlineKeyBoard([$option]);
        $text = "
                Product: " . $product->name . "
            Price: " . price_format($product->price);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $text];
        $message = $this->telegram->sendMessage($content);
        return [$image_message, $message];
    }

    public function sendCart()
    {

        $chat_id = $this->telegram->ChatID();
        /* $data = $this->getCart($this->telegram->UserID());

        if (!isset($data) || count($data->items) == 0) {
        $this->sendMessage(__('responses.cart.empty_cart'));
        return;
        }*/

        // get updated cart
        $cart = new Cart();
        $updatedCart = $cart->updateCart();
        $deleted_products = $updatedCart['deleted_products'];
        $updated_products = $updatedCart['updated_products'];

        if (count($deleted_products) > 0) {
            $this->sendMessage(__('responses.cart.update_cart'));
            foreach ($deleted_products as $product) {
                $this->sendMessage($product->name);
            }
        }

        if (count($updated_products) > 0) {
            $this->sendMessage(__('responses.cart.update_cart_items_price'));
            foreach ($updated_products as $product) {
                $this->sendMessage($product->name);
            }
        }

        // refresh the cart and check it after updating
        $myCart = $cart->getCart();
        if (!$myCart || count($myCart->items) == 0) {
            $this->sendMessage(__('responses.cart.empty_cart'));
            return;
        }

        //show cart
        $this->sendMessage(__('responses.cart.intro'));

        foreach ($myCart->items as $product) {

            //create product buttons
            $option = [
                [
                    $this->telegram->buildInlineKeyBoardButton(__('responses.cart.remove_from_cart'), $url = '', $callback_data = TelegramMessageHandler::REMOVE_FROM_CART . TelegramMessageHandler::DELIMITER . '_P_' . $product->id . TelegramMessageHandler::DELIMITER),
                ],
            ];

            $keyb = $this->telegram->buildInlineKeyBoard($option);

            // get product info

            $text = $this->buildProductTotalsMessage($product->product);
            if (ifAppLocal()) {
                $url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Image_created_with_a_mobile_phone.png/640px-Image_created_with_a_mobile_phone.png';
            } else {
                $url = Storage::disk('public')->url($product->product->image_url);
            }

            $mediaMessage = [
                'chat_id' => $chat_id,
                'photo' => $url,
                'caption' => $text,
                'reply_markup' => $keyb,
            ];

            $this->telegram->sendPhoto($mediaMessage);
        }

        //send cart operations
        //send product name and buttons
        $option = [

            [
                $this->telegram->buildInlineKeyBoardButton(__('responses.cart.checkout'), $url = '', $callback_data = TelegramMessageHandler::CHECKOUT . TelegramMessageHandler::DELIMITER),
                $this->telegram->buildInlineKeyBoardButton(__('responses.categories.return_to_categories'), $url = '', $callback_data = TelegramMessageHandler::CATEGORIES . TelegramMessageHandler::DELIMITER),
            ],

        ];
        $keyb = $this->telegram->buildInlineKeyBoard($option);


        $tex = $this->buildCartTotalsMessage();
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $tex];
        $message = $this->telegram->sendMessage($content);
    }

    public function sendCheckout()
    {
        /*
        //send product name and buttons
        $option=[

        [
        $this->telegram->buildInlineKeyBoardButton(__('responses.cart.yes'), $url = '', $callback_data = TelegramMessageHandler::CHECKOUT_TELEGRAM_DATA.TelegramMessageHandler::DELIMITER),
        $this->telegram->buildInlineKeyBoardButton(__('responses.cart.no'), $url = '', $callback_data = TelegramMessageHandler::CHECKOUT_START.TelegramMessageHandler::DELIMITER)
        ],

        ];
        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => __('responses.checkout.reg_name').': '.$this->telegram->FirstName().' '.$this->telegram->LastName() ];
        $this->telegram->sendMessage($content);*/

        /* if ($this->getCart()->items()->count() == 0) {
        $this->sendMessage(__('responses.cart.empty_cart'));
        return;
        }*/

        // update cart
        $cart = new Cart();
        $updatedCart = $cart->updateCart();
        $deleted_products = $updatedCart['deleted_products'];
        $updated_products = $updatedCart['updated_products'];

        if (count($deleted_products) > 0) {
            $this->sendMessage(__('responses.cart.update_cart'));
            foreach ($deleted_products as $product) {
                $this->sendMessage($product->name);
            }
        }

        if (count($updated_products) > 0) {
            $this->sendMessage(__('responses.cart.update_cart_items_price'));
            foreach ($updated_products as $product) {
                $this->sendMessage($product->name);
            }
        }

        // check it after updating
        $myCart = $cart->getCart();
        if (count($myCart->items) == 0) {
            $this->sendMessage(__('responses.cart.empty_cart'));
            return;
        }

        $this->updateStoredMessageAdditional('current_state', TelegramMessageHandler::CHECKOUT_FIRST_NAME);
        $this->sendMessage(__('responses.checkout.enter_complelete_name'));
    }

    public function processCheckout()
    {
        //set first name and last name
        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional);
        $additional->first_name = $this->telegram->FirstName();
        $additional->last_name = $this->telegram->LastName();
        $additional->current_state = TelegramMessageHandler::CHECKOUT_CITY;
        $storedMessage->additional = json_encode($additional);
        $storedMessage->save();
        //$this->sendMessage(__('responses.checkout.customer_governorate'));
        $this->sendCity();
    }

    public function sendCity()
    {
        $option = [];
        $state = TelegramMessageHandler::CHECKOUT_HANDEL_CITY;
        $index = 1;
        $row = [];
        foreach (get_states() as $stateCode => $stateName) {

            $city = $this->telegram->buildInlineKeyBoardButton($stateName, $url = '', $callback_data = $state . TelegramMessageHandler::DELIMITER . '_G_' . $stateCode . TelegramMessageHandler::DELIMITER);

            array_push($row, $city);
            if ($index % 3 == 0) {
                array_push($option, $row);
                $row = [];
            }
            $index++;
        }

        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => __('responses.checkout.customer_governorate')];
        $this->telegram->sendMessage($content);
    }

    public function sendPayment()
    {
        $option = [];
        $cart = new Cart();
        $myCart = $cart->getCart();

        if ($myCart->currency == 'USD') {

            $codMethod = get_cod_payment_method();

            $state = '_S_payment_method' . TelegramMessageHandler::DELIMITER . '_PM_' . $codMethod->getKey();
            $payment =
                [
                $this->telegram->buildInlineKeyBoardButton($codMethod->getLabel(), $url = '', $callback_data = $state . TelegramMessageHandler::DELIMITER),
            ];

            array_push($option, $payment);
        } else {

            $enabled_payments = get_enabled_payment_methods();
            foreach ($enabled_payments as $enabled_payment) {
                $paymentUrl = '';
                //select payment first then send the pay button
                if ($enabled_payment->needsRedirect() && false) {
                    $paymentUrl = $enabled_payment->getRedirectUri($this->getCart());
                }
                $state = '_S_payment_method' . TelegramMessageHandler::DELIMITER . '_PM_' . $enabled_payment->getKey();

                $payment =
                    [
                    $this->telegram->buildInlineKeyBoardButton($enabled_payment->getLabel(), $url = $paymentUrl, $callback_data = $state . TelegramMessageHandler::DELIMITER),
                ];

                array_push($option, $payment);
            }
        }

        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => get_payment_method_message()];
        $this->telegram->sendMessage($content);

        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional);
        //log::info(serialize($additional));
        $myCart->update([
            //'payment_method' => $paymentMethod,
            'customer_first_name' => $additional->first_name,
            'customer_last_name' => $additional->last_name,
            'customer_governorate' => $additional->city,
            'customer_address' => $additional->address,
            'customer_phone_number' => $additional->phone,
            'notes' => $additional->notes ?? '',
            'additional_question1' => $additional->additional_question1 ?? '',
            'additional_question2' => $additional->additional_question2 ?? '',
        ]);
    }

    public function handelPaymentCod()
    {
        $this->updateStoredMessageAdditional('current_state', TelegramMessageHandler::ORDER_DETAILS);
        $order = $this->createOrder('cod');
        $this->sendOrderDetails($order);
        //$this->clearStoredMessageAdditional();
    }

    public function handelPaymentFatora()
    {
        // \Log::debug('Fatora Payment');
        $cart = $this->getCart();
        $this->updateStoredMessageAdditional('current_state', TelegramMessageHandler::CHECKOUT_PAYMENT_FATORA);
        $fatora = new \App\PaymentMethods\Fatora($cart->store_id);
        $paymentUrl = $fatora->getRedirectUri($this->getCart(), ['platform' => 'telegram', 'chat_id' => $this->telegram->ChatID()]);
        $cart->update([
            'payment_info' => $fatora->getPaymentInfo(),
            'payment_method' => 'fatora',
        ]);
        //\Log::debug($paymentUrl);
        //send pay button

        $payment =
            [
            $this->telegram->buildInlineKeyBoardButton(__('responses.checkout.pay'), $url = $paymentUrl, $callback_data = TelegramMessageHandler::CHECKOUT_PAYMENT_FATORA . TelegramMessageHandler::DELIMITER),
        ];
        $keyb = $this->telegram->buildInlineKeyBoard([$payment]);
        $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => __('responses.checkout.pay_msg')];
        $this->telegram->sendMessage($content);
    }

    public function getUserExtraField($method, $idx)
    {
        $paymentMethod = config('payment_methods.' . $method);
        $payment = new $paymentMethod();
        $fields = $payment->getExtraUserFields();
        return $fields[$idx] ?? null;
    }

    public function handlePaymentMethod($method)
    {
        $cartClass = new Cart();
        $cartRecord = $cartClass->getCart();
        $cartRecord->update([
            'payment_method' => $method,
        ]);
        $payment = getPaymentClassByKey($cartRecord->payment_method);

        $payment = new $payment($this->store_id);
        $additional_callback = [];

        if ($payment->getExtraUserFields()) {
            $storedMessage = $this->getStoredMessage();
            $additional = json_decode($storedMessage->additional, true);

            if (!array_key_exists($method . '_is_complete', $additional) || !$additional[$method . '_is_complete']) {

                $additional['extra_field_idx'] = 0;
                $additional['user_extra_fields'] = [];
                $additional[$method . '_is_complete'] = false;

                $additional['current_state'] = TelegramMessageHandler::USEREXTRAFIELDS;

                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();

                $this->sendMessage($payment->getExtraUserFields()[0]['label']);
                return;
            } else {
                $additional_callback = $additional['user_extra_fields'];

                $additional['extra_field_idx'] = 0;
                $additional['user_extra_fields'] = [];
                $additional['current_state'] = '';
                $additional[$method . '_is_complete'] = false;
                $additional['additional_fields'] = $additional_callback;

                $storedMessage->additional = json_encode($additional);
                $storedMessage->save();
            }
        }

        $checkoutResult = $cartClass->checkout($cartRecord);

        if (is_string($checkoutResult)) {
            $payButton = [
                $this->telegram->buildInlineKeyBoardButton(__('responses.checkout.pay'), $url = $checkoutResult, $callback_data = TelegramMessageHandler::CHECKOUT_PAYMENT_METHOD . TelegramMessageHandler::DELIMITER),
            ];

            $keyb = $this->telegram->buildInlineKeyBoard([$payButton]);
            $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => __('responses.checkout.pay_msg')];
            $this->telegram->sendMessage($content);
        } else if ($checkoutResult instanceof Order) {
            if ($payment->getDescription()) {
                $this->sendMessage(str_replace('<br>', PHP_EOL, $payment->getDescription()));
            }
            $this->sendOrderDetails($checkoutResult);
        } else {
            $this->sendMessage(__('responses.checkout.payment_failed'));
            $this->sendPayment();
        }
    }

    public function sendOrderDetails($order)
    {
        if ($order) {
            // Already handled in the OrderCreated Event
//            $this->sendMessage(__('responses.checkout.order_completed', [
//                'order_id' => $order->id,
//            ]));

            // send additional message that store defined it.
            $additional_message = get_additional_message();

            if ($additional_message) {
                $this->sendMessage($additional_message);
            }

            //            $url = generate_invoice($order);
            //
            //             handle files in pro & local
            //            if (ifAppLocal())
            //                $url = 'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf';
            //
            //            $this->sendFile($url);
        } else {
            $this->sendMessage('order failed!!');
        }

    }

    public function sendFile($url)
    {
        $chat_id = $chat_id ?? $this->telegram->ChatID();

        $content = array('chat_id' => $chat_id, 'document' => $url);

        return $this->telegram->sendDocument($content);
    }

    public function getCategories()
    {
        $store_id = $this->store_id;
        $categories = Category::query()->where([
            ['store_id', $store_id],
            ['active', 1],
        ])->get();
        return CategoryResource::collection($categories)->response()->getData()->data;
    }

    public function getCategoryProductsByPage($category, $page)
    {
        $products = null;
        if ($page < 1) {
            return [];
        }

        if ($category) {
            $category = Category::where('id', $category)->first();
            $products = $category->products();
        } else {
            $products = Product::query();
        }

        request()->merge([
            'page' => $page,
        ]);

        $products = $products->where([
            ['store_id', $this->store_id],
            ['active', 1],
        ])->paginate($this->product_limit);

        return ProductResource::collection($products)->response()->getData()->data;
    }

    public function getCart()
    {
        $cartClass = new Cart();

        $cart = $cartClass->getCart();
        if (!$cart) {
            $cart = $cartClass->createCart();
        }

        $cart->update([
            'platform' => self::SIGNATURE,
        ]);

        return $cart;
    }

    public function addToCart($product_id)
    {
        // \Log::debug('addToCart: '.$product_id);

        $cartClass = new Cart();
        $cart = $cartClass->getCart();
        if (!$cart) $cart = $cartClass->createCart();
        $cart->update([
            'platform' => self::SIGNATURE,
            'platform_id' => $this->telegram->ChatID(),
            'payment_redirect_uri' => 'guest.payment.redirect'
        ]);

        if (!$cartClass->can_add_product_to_cart($product_id)) {

            $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.categories.return_to_categories'), $url = '', $callback_data = TelegramMessageHandler::CATEGORIES . TelegramMessageHandler::DELIMITER)];
            $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.cart.cart'), $url = '', $callback_data = TelegramMessageHandler::CART . TelegramMessageHandler::DELIMITER)];
            $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.cart.checkout'), $url = '', $callback_data = TelegramMessageHandler::CHECKOUT . TelegramMessageHandler::DELIMITER)];

            $message = [
                'chat_id' => $this->telegram->ChatID(),
                'text' => __('responses.currency_are_not_matched'),
                'reply_markup' => $this->telegram->buildInlineKeyBoard($actionsList),
            ];

            $this->telegram->sendMessage($message);
            return;
        }

        $product = Product::find($product_id);

        //check product type
        if ($product->type == 'product') {
            $cartItem = $cartClass->addProduct($product_id, request()->all());
            $message = $this->telegram->sendMessage($this->prepareAfterCheckoutMessage($cartItem));
            logger($message);
        } else {
            $this->updateStoredMessageAdditional('product_id', $product_id);
            // send checkin date message
            $this->sendMonths('in');
        }
    }

    public function sendMonths($type, $page = 0, $year = null, $month = null)
    {
        $additional = json_decode($this->getStoredMessage()->additional);
        log::info((array) $additional);
        $chat_id = $this->telegram->ChatID();
        $state = $type == 'out' ? TelegramMessageHandler::MONTH_OUT : TelegramMessageHandler::MONTH_IN;
        $month_text_message = $type == 'out' ? __('responses.checkout.month.end') : __('responses.checkout.month.start');
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $content = ['chat_id' => $chat_id, 'text' => __('responses.checkout.month')];
        $option = [];
        $option_row = [];
        $index = 1;
        Carbon::setLocale('ar_SY');
        $currentMonth = Carbon::now();
        $paginationConditionDate = now(); // in case type=in

        if ($type == 'out') {
            $currentMonth = now()->setYear($additional->year_in)->setMonth($additional->month_in)->setDay($additional->day_in);
            $paginationConditionDate = $currentMonth->copy(); // in case type=out

            // if checkin day was last day in the month, we have to skip this month
            $ifLastDay = $currentMonth->diffInDays($currentMonth->copy()->endOfMonth());

            if ($ifLastDay == 0) {
                $currentMonth->setDay(1)->addMonth();
                $paginationConditionDate->setDay(1)->addMonth();
            }
        }

        if ($month) {
            $currentMonth->setMonth($month)->setYear($year);
        }

        //$currentMonth = $currentMonth->addMonths($page);

        $days = $currentMonth->daysInMonth;

        $monthsKeys = [];
        $months = [];
        // TODO: Replace message
        if ($currentMonth->gt($paginationConditionDate)) {
            $previousMonth = $currentMonth->copy()->subMonth();
            $months[] = $this->telegram->buildInlineKeyBoardButton(__('responses.categories.previous'), $url = '', $callback_data = self::SEND_MONTHS . TelegramMessageHandler::DELIMITER . '_T_' . $type . TelegramMessageHandler::DELIMITER . '_Y_' . $previousMonth->year . TelegramMessageHandler::DELIMITER . '_M_' . $previousMonth->month . TelegramMessageHandler::DELIMITER);
        }

        $months[] = $this->telegram->buildInlineKeyBoardButton($currentMonth->translatedFormat('F Y'), $url = '', $callback_data = self::SEND_MONTHS_LIST . TelegramMessageHandler::DELIMITER . '_T_' . $type . TelegramMessageHandler::DELIMITER);

        if ($currentMonth->lt($paginationConditionDate->addMonths(11)->subDay())) {
            $nextMonth = $currentMonth->copy()->addMonth();
            $months[] = $this->telegram->buildInlineKeyBoardButton(__('responses.categories.next'), $url = '', $callback_data = self::SEND_MONTHS . TelegramMessageHandler::DELIMITER . '_T_' . $type . TelegramMessageHandler::DELIMITER . '_Y_' . $nextMonth->year . TelegramMessageHandler::DELIMITER . '_M_' . $nextMonth->month . TelegramMessageHandler::DELIMITER);
        }

        $monthsKeys[] = $months;

        $daysArray = [];
        for ($i = 1; $i <= $days; ++$i) {

            $tmpCarbon = $currentMonth->copy()->setDay($i);

            if ($type == 'in' && !$tmpCarbon->isPast()) {
                $callBackDate = $state . TelegramMessageHandler::DELIMITER . '_Y_' . $currentMonth->year . TelegramMessageHandler::DELIMITER . '_M_' . $currentMonth->month . TelegramMessageHandler::DELIMITER . '_D_' . $tmpCarbon->day . TelegramMessageHandler::DELIMITER;
                $daysArray[] = $this->telegram->buildInlineKeyBoardButton($tmpCarbon->format('d'), $url = '', $callback_data = $callBackDate);
            }

            if ($type == 'out' && (now()->setYear($additional->year_in)->setMonth($additional->month_in)->setDay($additional->day_in))->lt($tmpCarbon)) {
                $callBackDate = $state . TelegramMessageHandler::DELIMITER . '_Y_' . $currentMonth->year . TelegramMessageHandler::DELIMITER . '_M_' . $currentMonth->month . TelegramMessageHandler::DELIMITER . '_D_' . $tmpCarbon->day . TelegramMessageHandler::DELIMITER;
                $daysArray[] = $this->telegram->buildInlineKeyBoardButton($tmpCarbon->format('d'), $url = '', $callback_data = $callBackDate);
            }

            if (count($daysArray) % 7 == 0) {
                $monthsKeys[] = $daysArray;
                $daysArray = [];
            }
        }

        if (count($daysArray)) {
            $monthsKeys[] = $daysArray;
        }

        //prepare categories 2 each row
        //        foreach ($months as $month) {
        //            array_push($option_row, $this->telegram->buildInlineKeyBoardButton($month, $url = '', $callback_data = $state . TelegramMessageHandler::DELIMITER . '_M_' . $month . TelegramMessageHandler::DELIMITER));
        //            if ($index % 4 == 0) {
        //                array_push($option, $option_row);
        //                $option_row = [];
        //            }
        //            $index++;
        //        }

        // build buttons
        $keyb = $this->telegram->buildInlineKeyBoard($monthsKeys);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $month_text_message];
        $key = "calendar_" . $type;
        if (isset($additional->$key)) {
            //log::info($additional->$key);
            $content['message_id'] = $additional->$key;
            $message = $this->telegram->sendMessage($content);
        } else {
            $message = $this->telegram->sendMessage($content);
            $this->updateStoredMessageAdditional($key, $message['result']['message_id']);
        }
        //        $message = $this->telegram->sendMessage($content);
    }

    public function sendMonthList($type)
    {
        $month_text_message = $type == 'out' ? __('responses.checkout.month.end') : __('responses.checkout.month.start');
        $additional = json_decode($this->getStoredMessage()->additional);
        $chat_id = $this->telegram->ChatID();
        Carbon::setLocale('ar_SY');
        $currentMonth = Carbon::now();
        $remainingMonths = 12 - $currentMonth->month;
        $monthsKeys = [];
        $months = [];
        $monthsCount = 12;

        if ($type == 'out') {
            $currentMonth = now()->setYear($additional->year_in)->setMonth($additional->month_in)->setDay($additional->day_in);

            $ifLastDay = $currentMonth->diffInDays($currentMonth->copy()->endOfMonth());

            if ($ifLastDay == 0) {
                $currentMonth->setDay(1)->addMonth();
            }

        }

        for ($i = 0; $i < $monthsCount; $i++) {
            $tmpCarbon = $currentMonth->copy()->setMonth($currentMonth->month);
            $callBackDate = self::SEND_MONTHS . self::DELIMITER . '_T_' . $type . self::DELIMITER . '_Y_' . $tmpCarbon->year . self::DELIMITER . '_M_' . $tmpCarbon->month . self::DELIMITER;
            $monthsKeys[] = $this->telegram->buildInlineKeyBoardButton($tmpCarbon->translatedFormat('F Y'), $url = '', $callback_data = $callBackDate);
            if (count($monthsKeys) % 3 === 0) {
                $months[] = $monthsKeys;
                $monthsKeys = [];
            }
            $currentMonth->addMonth();
        }

        if (count($monthsKeys) > 0) {
            $months[] = $monthsKeys;
        }
        $keyb = $this->telegram->buildInlineKeyBoard($months);

        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $month_text_message];
        $key = "calendar_" . $type;
        if (isset($additional->$key)) {
            $content['message_id'] = $additional->$key;
            $message = $this->telegram->sendMessage($content);
        } else {
            $message = $this->telegram->sendMessage($content);
            $this->updateStoredMessageAdditional($key, $message['result']['message_id']);
        }
        //        $message = $this->telegram->sendMessage($content);
    }

    public function sendDays($type, $month)
    {
        $additional = json_decode($this->getStoredMessage()->additional);
        $chat_id = $this->telegram->ChatID();
        $state = $type == 'out' ? TelegramMessageHandler::DAY_OUT : TelegramMessageHandler::DAY_IN;

        $option = [];
        $option_row = [];
        $max_day = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
        $index = 1;
        //prepare categories 2 each row
        for ($i = 1; $i <= $max_day; $i++) {
            array_push($option_row, $this->telegram->buildInlineKeyBoardButton($i, $url = '', $callback_data = $state . TelegramMessageHandler::DELIMITER . '_D_' . $i . TelegramMessageHandler::DELIMITER));
            if ($index % 5 == 0) {
                array_push($option, $option_row);
                $option_row = [];
            }
            $index++;
        }

        // build buttons
        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => __('responses.checkout.day')];
        $key = "calendar_" . $type;
        if ($previousMessage = $additional->$key) {
            $content['message_id'] = $previousMessage;
            $message = $this->telegram->editMessageText($content);
        } else {
            $message = $this->telegram->sendMessage($content);
            $this->updateStoredMessageAdditional($key, $message['result']['message_id']);
        }
    }

    public function getStoredMessage()
    {
        $storedMessage = BotMessages::where([
            'platform' => 'Telegram',
            'sender_id' => $this->telegram->ChatID(),
            'recipient_id' => $this->telegram->UserID(),
        ])->first();
        if (!$storedMessage) {
            $storedMessage = BotMessages::create([
                'platform' => 'Telegram',
                'message_type' => 'calendar',
                'sender_id' => $this->telegram->ChatID(),
                'recipient_id' => $this->telegram->UserID(),
                'message_id' => $this->telegram->MessageID(),
                'message_text' => '',
                'reply' => 'empty',
                'created_at' => date('Y-m-d H:i:s'),
                'additional' => json_encode([
                    'current_state' => '',
                    'category_id' => '',
                    'product_id' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'city' => '',
                    'address' => '',
                    'phone' => '',
                    'notes' => '',
                    'additional_question1' => '',
                    'additional_question2' => '',
                    'year_in' => '',
                    'month_in' => '',
                    'day_in' => '',
                    'time_in' => '',
                    'year_out' => '',
                    'month_out' => '',
                    'day_out' => '',
                    'user_extra_fields' => [],
                ]),
            ]);
        }
        return $storedMessage;
    }

    public function updateStoredMessageAdditional($key, $val)
    {
        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional);
        if (isset($additional->$key)) {
            $additional->$key = $val;
        } else {
            $additional = json_decode($storedMessage->additional, true);
            $additional[$key] = $val;
        }

        $storedMessage->additional = json_encode($additional);
        $storedMessage->save();
    }

    public function clearStoredMessageAdditional()
    {
        $storedMessage = $this->getStoredMessage();
        $storedMessage->additional = json_encode([
            'current_state' => '',
            'category_id' => '',
            'product_id' => '',
            'first_name' => '',
            'last_name' => '',
            'city' => '',
            'address' => '',
            'phone' => '',
            'notes' => '',
            'additional_question1' => '',
            'additional_question2' => '',
            'year_in' => '',
            'month_in' => '',
            'day_in' => '',
            'time_in' => '',
            'year_out' => '',
            'month_out' => '',
            'day_out' => '',
            'user_extra_fields' => [],
        ]);
        $storedMessage->save();
    }

    public function handelMonth($type, $year, $month, $day)
    {

        if ($type == 'in') {
            $this->updateStoredMessageAdditional('year_in', $year);
            $this->updateStoredMessageAdditional('month_in', $month);
            $this->updateStoredMessageAdditional('day_in', $day);
        } else {
            $this->updateStoredMessageAdditional('year_out', $year);
            $this->updateStoredMessageAdditional('month_out', $month);
            $this->updateStoredMessageAdditional('day_out', $day);
        }

        // TODO: Send hour list

        if (ifHourEnabled()) {
            $this->sendHour($type);
        } else {
            $this->setCheckDate('0:0', $type);
        }

        // $this->sendDays($type, $month);

    }

    public function sendHour($type)
    {
        $chat_id = $this->telegram->ChatID();
        $content = ['chat_id' => $chat_id, 'text' => __('responses.cart.pick_hour')];
        $message = $this->telegram->sendMessage($content);
        $this->updateStoredMessageAdditional('current_state', self::SEND_HOUR);
        $this->updateStoredMessageAdditional('current_direction', $type);
    }

    public function sendMinutes($hour, $type)
    {
        $intervals = [0, 15, 30, 45];
        $chat_id = $this->telegram->ChatID();
        $hoursKb = [];

        foreach ($intervals as $interval) {
            $text = now()->setMinute($interval)->setHour($hour)->format('g:i A');
            $hoursAm[] = $this->telegram->buildInlineKeyboardButton($text, $url = '', $callback_data = self::SET_HOUR . TelegramMessageHandler::DELIMITER . '_H_' . $text . TelegramMessageHandler::DELIMITER . '_T_' . $type . TelegramMessageHandler::DELIMITER);
        }

        foreach ($intervals as $interval) {
            $text = now()->setMinute($interval)->setHour($hour + 12)->format('g:i A');
            $hoursPm[] = $this->telegram->buildInlineKeyboardButton($text, $url = '', $callback_data = self::SET_HOUR . TelegramMessageHandler::DELIMITER . '_H_' . $text . TelegramMessageHandler::DELIMITER . '_T_' . $type . TelegramMessageHandler::DELIMITER);
        }

        $hoursKb = $this->telegram->buildInlineKeyBoard([$hoursAm, $hoursPm]);

        $content = ['chat_id' => $chat_id, 'text' => __('responses.cart.pick_minutes'), 'reply_markup' => $hoursKb];
        $message = $this->telegram->sendMessage($content);
        logger('message', $message);
    }

    public function setCheckDate($hour, $type)
    {
        //logger($hour);
        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional, 1);
        $additional['time_' . $type] = $hour;
        $storedMessage->additional = json_encode($additional);
        $storedMessage->save();
        $product = Product::find($additional['product_id'] ?? null);
        $cartClass = new Cart();
        if ($type === 'in') {
            $this->updateStoredMessageAdditional('calendar_in', null);
            if ($product->require_end_date) {
                //logger('required end date');
                $this->sendMonths('out');
            } else {
                $storedMessageAdditional = $this->getStoredMessage()->additional;
                $storedMessageAdditional = json_decode($storedMessageAdditional);

                $checkinHour = null;
                $checkinMin = null;

                if (ifHourEnabled()) {
                    // handle checkin time
                    $checkinInfo = explode(':', $storedMessageAdditional->time_in);
                    $checkinHour = $checkinInfo[0];
                    $checkinDetails = explode(' ', $checkinInfo[1]);
                    $checkinMin = $checkinDetails[0];
                    $checkinTime = $checkinDetails[1];

                    if ($checkinTime == 'PM' && $checkinHour != 12) {
                        $checkinHour = $checkinHour + 12;
                    }

                    if ($checkinTime == 'AM' && $checkinHour == 12) {
                        $checkinHour = $checkinHour + 12;
                    }

                }

                $cartItem = $cartClass->addProduct($additional['product_id'], [
                    'checkin' => now()->setYear($storedMessageAdditional->year_in)->setMonth($storedMessageAdditional->month_in)->setDay($storedMessageAdditional->day_in)->setTime($checkinHour, $checkinMin),
                ]);

                $message = $this->prepareAfterCheckoutMessage($cartItem);
                $this->telegram->sendMessage($message);
            }
        } else {
            $storedMessageAdditional = $this->getStoredMessage()->additional;
            $storedMessageAdditional = json_decode($storedMessageAdditional);

            $checkinHour = null;
            $checkinMin = null;
            $checkoutHour = null;
            $checkoutMin = null;

            if (ifHourEnabled()) {
                // handle checkin time
                $checkinInfo = explode(':', $storedMessageAdditional->time_in);
                $checkinHour = $checkinInfo[0];

                $checkinDetails = explode(' ', $checkinInfo[1]);
                $checkinMin = $checkinDetails[0];
                $checkinTime = $checkinDetails[1];

                if ($checkinTime == 'PM' && $checkinHour != 12) {
                    $checkinHour = $checkinHour + 12;
                }

                if ($checkinTime == 'AM' && $checkinHour == 12) {
                    $checkinHour = $checkinHour + 12;
                }

                // handle checkout time
                $checkoutInfo = explode(':', $storedMessageAdditional->time_out);
                $checkoutHour = $checkoutInfo[0];
                $checkoutDetails = explode(' ', $checkoutInfo[1]);
                $checkoutMin = $checkoutDetails[0];
                $checkoutTime = $checkoutDetails[1];

                if ($checkoutTime == 'PM' && $checkoutHour != 12) {
                    $checkoutHour = $checkoutHour + 12;
                }

                if ($checkoutTime == 'AM' && $checkoutHour == 12) {
                    $checkoutHour = $checkoutHour + 12;
                }

            }

            $additional = [
                'checkin' => now()->setYear($storedMessageAdditional->year_in)->setMonth($storedMessageAdditional->month_in)->setDay($storedMessageAdditional->day_in)->setTime($checkinHour, $checkinMin),
                'checkout' => now()->setYear($storedMessageAdditional->year_out)->setMonth($storedMessageAdditional->month_out)->setDay($storedMessageAdditional->day_out)->setTime($checkoutHour, $checkoutMin),
            ];

            $cartItem = $cartClass->addProduct($storedMessageAdditional->product_id, $additional);
            $this->telegram->sendMessage($this->prepareAfterCheckoutMessage($cartItem));
            $this->updateStoredMessageAdditional('calendar_out', null);
        }
    }

    public function handelDay($type, $day)
    {
        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional);
        $product = Product::find($additional->product_id);

        if ($type == 'in') {
            $this->updateStoredMessageAdditional('day_in', $day);

            if ($product && $product->require_end_date) {
                //send month out
                $this->sendMonths('out');
            } else {
                //addtocart
                $cart = new Cart();
                $year = date('Y');
                $month = $additional->month_in;
                $day = $additional->day_in;
                $hour = 13;
                $minute = 00;
                $date = Carbon::parse("$year-$month-$day $hour:$minute");
                $data = [
                    'checkin' => $date,
                    'checkout' => $date,
                ];

                $cart->addProduct($product->id, $data);
                $this->clearStoredMessageAdditional();
            }
        } else {
            $this->updateStoredMessageAdditional('day_out', $day);
            //addtocart
            //addtocart
            $cart = new Cart();
            $year = date('Y');
            $storedMessage = $this->getStoredMessage();
            $additional = json_decode($storedMessage->additional);
            $month = $additional->month_in;
            $day = $additional->day_in;
            $hour = 13;
            $minute = 00;
            $date = Carbon::parse("$year-$month-$day $hour:$minute");
            $year = date('Y');
            $month = $additional->month_out;
            $day = $additional->day_out;
            $hour = 13;
            $minute = 00;
            $date_out = Carbon::parse("$year-$month-$day $hour:$minute");
            $data = [
                'checkin' => $date,
                'checkout' => $date_out,
            ];

            $cart->addProduct($product->id, $data);
            $this->sendReservationDetails();
            //clear bot message
            $this->clearStoredMessageAdditional();
        }
    }

    public function sendReservationDetails()
    {
        $year = date('Y');
        $storedMessage = $this->getStoredMessage();
        $additional = json_decode($storedMessage->additional);
        $month = $additional->month_in;
        $day = $additional->day_in;
        $hour = 13;
        $minute = 00;
        $date = Carbon::parse("$year-$month-$day $hour:$minute");
        $text = __('responses.cart.reserv_to_cart', [$date]);
        $month = $additional->month_out;
        if ($month != '') {
            $day = $additional->day_out;
            $hour = 13;
            $minute = 00;
            $date_out = Carbon::parse("$year-$month-$day $hour:$minute");
            $text = __('responses.cart.reserv_full_to_cart', [$date, $date_out]);
        }
        $option = [

            [
                $this->telegram->buildInlineKeyBoardButton(__('responses.checkout.proceed'), $url = '', $callback_data = TelegramMessageHandler::CART . TelegramMessageHandler::DELIMITER),

                $this->telegram->buildInlineKeyBoardButton(__('responses.categories.return_to_categories'), $url = '', $callback_data = TelegramMessageHandler::CATEGORIES . TelegramMessageHandler::DELIMITER),
            ],

        ];
        $keyb = $this->telegram->buildInlineKeyBoard($option);
        $content = ['chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => $text];
        $this->telegram->sendMessage($content);
    }

    public function removeFromCart($product_id)
    {
        // \Log::debug('removeFromCart: '.$product_id);
        $cart = new Cart();
        $cart->removeFromCart($product_id);
        $content = ['callback_query_id' => $this->telegram->Callback_ID(), 'text' => __('responses.cart.removed_from_cart'), 'show_alert' => true];
        $this->telegram->answerCallbackQuery($content);
    }

    public function createOrder($paymentMethod)
    {
        $cart = new Cart();
        $myCart = $cart->getCart();
        $myCart->update([
            'payment_method' => $paymentMethod,
        ]);
        $order = $cart->saveOrder($myCart);
        $myCart->update([
            'active' => false,
        ]);
        return $order;
    }

    public function sendCommands()
    {
        $options = [
            ['command' => 'store', 'description' => 'Show store categories'],
            ['command' => 'cart', 'description' => 'Cart'],
        ];

        if ($this->getQuestions()) {
            $options[] = ['command' => 'faq', 'description' => 'FAQs'];
        }

        if ($this->getAboutUs()) {
            $options[] = ['command' => 'about', 'description' => 'About Us'];
        }

        if ($this->getContactUs()) {
            $options[] = ['command' => 'contact', 'description' => 'Contact Us'];
        }

        if ($this->ifPoweredButtonEnabled()) {
            $options[] = ['command' => 'poweredbyecart', 'description' => 'Powered By Ecart'];
        }

        $commands = json_encode($options);
        $content = ['commands' => $commands];
        \Log::debug($this->telegram->setMyCommands($content));
    }

    public function setDescription($desc)
    {
        $content = ['chat_id' => $this->telegram->ChatID(), 'description' => $desc];
        $message = $this->telegram->setChatDescription($content);
    }

    public function test()
    {
        //\Log::debug($this->sendImage('https://mangalek.com/wp-content/mangalek.png'));
        $url = 'https://images.ctfassets.net/hrltx12pl8hq/qGOnNvgfJIe2MytFdIcTQ/429dd7e2cb176f93bf9b21a8f89edc77/Images.jpg';
        $img = curl_file_create($url, 'image/jpg');

        $content = ['chat_id' => $this->telegram->ChatID(), 'media' => $img, 'message_id' => 74];
        $this->telegram->editMessageMedia($content);
        \Log::debug($this->telegram->MessageID());
    }

    public function prepareAfterCheckoutMessage($cartItem)
    {
        $hourFormat = '';

        if (ifHourEnabled()) {
            $hourFormat = ' g:i A';
        }

        if ($cartItem->product->type === 'reservation') {
            $checkIn = Carbon::parse($cartItem->additional['checkin'])->timezone('Asia/Damascus')->format('Y-m-d' . $hourFormat);
            if ($cartItem->product->require_end_date && ($cartItem->additional['checkout'] ?? null)) {
                $checkOut = Carbon::parse($cartItem->additional['checkout'])->timezone('Asia/Damascus')->format('Y-m-d' . $hourFormat);
                $textMessage = __('responses.cart.product_reserved_with_end_date', ['start_date' => $checkIn, 'end_date' => $checkOut]);
            } else {
                $textMessage = __('responses.cart.product_reserved', ['start_date' => $checkIn]);
            }
        } else {
            $textMessage = __('responses.cart.added_to_cart');
        }
        $actionsList = [];
        $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.categories.return_to_categories'), $url = '', $callback_data = TelegramMessageHandler::CATEGORIES . TelegramMessageHandler::DELIMITER)];
        $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.cart.cart'), $url = '', $callback_data = TelegramMessageHandler::CART . TelegramMessageHandler::DELIMITER)];
        $actionsList[] = [$this->telegram->buildInlineKeyboardButton(__('responses.cart.checkout'), $url = '', $callback_data = TelegramMessageHandler::CHECKOUT . TelegramMessageHandler::DELIMITER)];
        return [
            'chat_id' => $this->telegram->ChatID(),
            'text' => $textMessage,
            'reply_markup' => $this->telegram->buildInlineKeyBoard($actionsList),
        ];
    }

    public function buildProductTotalsMessage($product)
    {
        $cartClass = new Cart();
        $productTotals = $cartClass->calculateProductTotals($product);
        $text = "
                المنتج: " . $product->name . "
السعر: " . $productTotals['formatted_price'];

        if (($productTotals['total_fees'] ?? null) > 0) {
            $text .= '
رسوم الخدمة: ' . $productTotals['formatted_total_fees'];
        }

        if (($productTotals['total_tax'] ?? null) > 0) {
            $text .= '
الضريبة: ' . $productTotals['formatted_total_tax'];
        }
        return $text;
    }

    public function buildCartTotalsMessage()
    {
        $cart = $this->getCart();

        $cart = \App\Http\Resources\Cart::make($cart);
        $cartResult = $cart->toArray(request());

        $taxIncluded = ($cartResult['tax_total'] ?? null) > 0;
        $feesIncluded = ($cartResult['fees_total'] ?? null) > 0;

        $count = $cart->items->count();

        $text = "${count} منتج في السلة";

        if ($taxIncluded || $feesIncluded)
            $text .= "
المجموع الجزئي: " . $cartResult['formatted_sub_total'];

        if ($taxIncluded) {
            $text .= '
إجمالي الضرائب: ' . $cartResult['formatted_tax_total'];
        }

        if ($feesIncluded) {
            $text .= '
إجمالي رسوم الخدمة: ' . $cartResult['formatted_fees_total'];
        }

        $text .= '
المجموع الكلي: ' . $cartResult['formatted_total'];

        return $text;
    }
}
