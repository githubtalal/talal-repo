<?php

namespace App\Http\Controllers;

use App\Commands\AboutUs;
use App\Commands\ContactUs;
use App\Commands\FAQs;
use App\Commands\GetStarted;
use App\Commands\ListCategories;
use App\Commands\ListCategoryProducts;
use App\Commands\PoweredByEcart;
use App\Commands\ShowCart;
use App\Facebook\Facebook;
use App\Facebook\Messages\Buttons\PostbackButton;
use App\Facebook\MessengerProfiler\MessengerProfiler;
use App\Facebook\MessengerProfiler\Properties\GetStartedButton;
use App\Facebook\MessengerProfiler\Properties\PersistentMenu;
use App\Models\Category;
use App\Models\Store;
use App\Models\StoreBot;
use App\Models\StoreSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MessengerController extends Controller
{
    public function view()
    {
        $store = auth()->user()->store;

        $bots = $store->bots()->where([
            'store_id' => $store->id,
            'platform' => 'facebook',
            'token_type' => Facebook::PAGE_TOKEN,
        ])->get();


        return view('newDashboard.bot.messenger.view', compact('bots'));
    }

    public function listPages()
    {
        $store = auth()->user()->store;
        $token = session('platform_token');
        $facebook = Facebook::create();
        $longLivedAccessToken = $store->bots()->where([
            'store_id' => $store->id,
            'platform' => 'facebook',
            'active' => true,
            'token_type' => Facebook::LONG_LIVED_USER_TOKEN,
        ])->first();

        logger('[FACEBOOK CONNECT] Listing Pages', [
            'store_id' => $store->id,
            'long_lived_access_token' => $longLivedAccessToken->token ?? '',
        ]);
        if ($longLivedAccessToken && !$longLivedAccessToken->isExpired()) {
            $pages = $facebook->getPagesFromUserAccessToken($token);

            logger('[FACEBOOK CONNECT] Getting  Pages from Facebook', [
                'store_id' => $store->id,
                'pages' => $pages
            ]);

            if (!$pages) {
                return redirect()->back()->with('error_message', __('app.responses_messages.error_message'));
            }

            $managedPages = collect($pages)->filter(function ($page) {
                $check = $page->access_token ?? false;
                if ($page->tasks ?? false) { // In case there is no tasks field in the page object.
                    $check = $check && in_array('MANAGE', $page->tasks);
                }
                return $check;
            });

            logger('[FACEBOOK CONNECT] Facebook Managed Pages', [
                'store_id' => $store->id,
                'pages' => $managedPages->toArray(),
            ]);

            foreach ($managedPages as $page) {
                $pagePic = $facebook->getPageProfilePicture($page->id);
                if ($pagePic) {
                    $page->image = $pagePic->url;
                }
            }
            return view('newDashboard.bot.messenger.list-pages', compact('managedPages'));
        }
        abort(403);
    }

    public function create(Request $request)
    {
        $accessToken = $request->access_token;
        $expiresIn = $request->expires_in;
        $userId = $request->user_id;
        $store = auth()->user()->store;
        try {
            $longLivedAccessToken = Facebook::create()->getLongLivedUserAccessToken($accessToken);

            logger('[FACEBOOK CONNECT] Getting long lived user access token', [
                'store_id' => $store->id,
                'long_lived_access_token' => $longLivedAccessToken->getAccessToken(),
            ]);

            $data = [
                'store_id' => $store->id,
                'platform' => 'facebook',
                'platform_id' => $userId,
                'active' => true,
                'token_type' => Facebook::LONG_LIVED_USER_TOKEN,
            ];
            $previousUserLongLivedAccessToken = StoreBot::query()->where($data)->first();

            if ($previousUserLongLivedAccessToken) {
                $previousUserLongLivedAccessToken->update([
                    'token' => $longLivedAccessToken->getAccessToken(),
                    'expires_at' => now()->addDays(Facebook::USER_TOKEN_EXPIRATION_DAYS),
                    'expires_in' => now()->addDays(Facebook::USER_TOKEN_EXPIRATION_DAYS),
                ]);
            } else {
                $data['token'] = $longLivedAccessToken->getAccessToken();
                $data['expires_at'] = now()->addDays(Facebook::USER_TOKEN_EXPIRATION_DAYS);
                $data['expires_in'] = now()->addDays(Facebook::USER_TOKEN_EXPIRATION_DAYS);
                StoreBot::create($data);
            }

            session()->put('platform_token', $longLivedAccessToken->getAccessToken());
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            logger('[FACEBOOK CONNECT] Error while getting long lived user token', [
                'store_id' => $store->id,
                $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function setPages(Request $request)
    {
        $store = auth()->user()->store;
        $data = [
            'store_id' => $store->id,
            'platform' => 'facebook',
            'active' => true,
            'token_type' => Facebook::PAGE_TOKEN,
        ];

        foreach ($request->get('pages', []) as $idx => $pageAccessToken) {
            return   $pageData = array_merge($data, [
                'platform_id' => $request->get('page_ids')[$idx],
            ]);
            $page = StoreBot::query()->where($pageData)->first();
            if (!$page) {
                $pageData['token'] = $pageAccessToken;
                $page = StoreBot::query()->create($pageData);
            } else {
                $page->update([
                    'token' => $pageAccessToken,
                ]);
            }
            $isSetMessengerProfile = $this->setPageMessengerProfile($page->token, $page->platform_id);
            $isSubscribedToWebhook = Facebook::create()->subscribePageToWebHook($page->platform_id, $page->token);

            logger('[FACEBOOK CONNECT] Setting Facebook webhooks & menu', [
                'store_id' => $store->id,
                'menu_result' => $isSetMessengerProfile,
                'webhooks_result' => $isSubscribedToWebhook,
            ]);

            if (!$isSetMessengerProfile || !$isSubscribedToWebhook)
                return redirect()->back()->with('error_message', __('app.responses_messages.error_message'));
        }

        return redirect()->back();
    }


    public function setPageMessengerProfile($pageAccessToken, $pageId = null)
    {
        $persistentMenu = PersistentMenu::create();
        $getStartedButton = GetStartedButton::create()->setPayload(ListCategories::buildPayload());

        // build category button
        $categoryButton = new PostbackButton();
        $categoryButton->setTitle(__('responses.messenger_profile.categories'))->setPayload(ListCategories::buildPayload());
        $persistentMenu->addButton($categoryButton);

        // build show cart button
        $showCartButton = new PostbackButton();
        $showCartButton->setTitle(__('responses.messenger_profile.cart'));
        $showCartButton->setPayload(ShowCart::buildPayload());

        $persistentMenu->addButton($showCartButton);

        // check if store has FAQs section
        $questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', auth()->user()->store->id]
        ])->first();

        if ($questions) {
            $questions = json_decode($questions->value);

            if ($questions) {
                $faqButton = new PostbackButton();
                $faqButton->setTitle(__('responses.messenger_profile.faq'));
                $faqButton->setPayload(FAQs::buildPayload());

                $persistentMenu->addButton($faqButton);
            }
        }

        // check if store has contact us section
        $contact_us = StoreSettings::where([
            ['key', 'Contact_Us'],
            ['store_id', auth()->user()->store->id]
        ])->first();

        if ($contact_us) {
            $value = json_decode($contact_us->value);

            if ($value) {
                $contactUsButton = new PostbackButton();
                $contactUsButton->setTitle(__('responses.messenger_profile.contact_us'));
                $contactUsButton->setPayload(ContactUs::buildPayload());

                $persistentMenu->addButton($contactUsButton);
            }
        }

        // check if store has about us section
        $about_us = StoreSettings::where([
            ['key', 'About_Us'],
            ['store_id', auth()->user()->store->id]
        ])->first();

        if ($about_us) {
            $value = json_decode($about_us->value);

            if ($value) {
                $aboutUsButton = new PostbackButton();
                $aboutUsButton->setTitle(__('responses.messenger_profile.about_us'));
                $aboutUsButton->setPayload(AboutUs::buildPayload());

                $persistentMenu->addButton($aboutUsButton);
            }
        }

        // check if PoweredByEcart button enabled
        $bot_settings = StoreSettings::where([
            ['store_id', auth()->user()->store->id],
            ['key', 'bot_settings']
        ])->first();

        if ($bot_settings) {
            if (array_key_exists('power_button', $bot_settings->value)) {
                $PoweredByEcartButton = new PostbackButton();
                $PoweredByEcartButton->setTitle(__('responses.help.Powered-By-Ecart'));
                $PoweredByEcartButton->setPayload(PoweredByEcart::buildPayload());

                $persistentMenu->addButton($PoweredByEcartButton);
            }
        }

        try {
            return MessengerProfiler::create($pageAccessToken)
                ->addProperty($persistentMenu)
                ->addProperty($getStartedButton)
                ->send();
        } catch (\Exception $e) {
            report($e);
            logger('Error while setting messenger profile', [
                'error' => $e->getMessage(),
                'page_id' => $pageId,
            ]);
        }
    }
}
