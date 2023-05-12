<?php

use App\Facebook\Facebook;
use App\Http\Controllers\MessengerController;
use App\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBot;
use App\Models\StoreSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

if (!function_exists('current_user')) {
    function current_user()
    {
        foreach (config('auth.guards') as $guard => $v) {
            if (auth($guard)->check()) {
                return auth($guard)->user();
            }
        }

        return null;
    }
}

if (!function_exists('model_changed_attributes')) {
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    function model_changed_attributes($model)
    {
        $changedAttributes = [];
        foreach ($model->getDirty() as $attributeName => $value) {
            $changedAttributes[] = $attributeName . ' [' . $model->getOriginal($attributeName) . ' => ' . $model->getAttribute($attributeName) . ' ]';
        }
        return $changedAttributes;
    }
}

if (!function_exists('cart')) {

    function cart()
    {
        return \App\Models\Cart::query()->where([
            'customer_uuid' => session('user_id'),
            'store_id' => session('store_id'),
            'active' => true,
        ])->first();
    }
}

if (!function_exists('create_cart')) {
    function create_cart()
    {
        $userId = cookie()->get('User-ID');
        if (!$userId) {
            return null;
        }

        [$user_id, $storeToken] = explode('-', decrypt_userid($userId));
        $store = \App\Models\Store::query()->where('token', $storeToken)->first();
        \App\Models\Cart::query()->create([
            'customer_uuid' => $user_id,
            'store_id' => $storeToken,
            'active' => true,
        ]);
        return \App\Models\Cart::query()->where('customer_uuid', $userId)->first();
    }
}

if (!function_exists('generate_user')) {
    function generate_user()
    {
        $storeToken = request()->get('token');
        $userId = request()->get('user_id');
        $store = \App\Models\Store::query()->where('token', $storeToken)->first();
        return encrypt_userid($userId, $storeToken);
    }
}

if (!function_exists('encrypt_userid')) {
    function encrypt_userid($user_id, $store_token)
    {
        $toEncrypt = $user_id . '-' . $store_token;
        return \App\Crypto::encrypt($toEncrypt);
    }
}

if (!function_exists('decrypt_userid')) {
    function decrypt_userid($userId)
    {
        return \App\Crypto::decrypt($userId);
    }
}

if (!function_exists('product_in_cart')) {
    function product_in_cart($product)
    {
        $cart = \App\Models\Cart::query()->where([
            'customer_uuid' => session('user_id'),
            'active' => true,
        ])->first();
        if (!$cart) {
            return false;
        }

        return \App\Models\CartItem::query()->where([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
        ])->exists();
    }
}
if (!function_exists('price_format')) {
    function price_format($price, $currency = 'ليرة سورية', $fraction = 0, $position = 'right')
    {
        $price = floatval($price);
        $price = number_format($price, $fraction, '.', ',');
        if ($position == 'right') {
            return $price . ' ' . $currency;
        }
        return $currency . $price;
        // $format = $position == 'right' ? '%i'.$currency : $currency.'%i';
        // return money_format($format, $price);
    }
}

if (!function_exists('get_states')) {
    function get_states()
    {
        $states = [];
        foreach (__('responses.states') as $key => $value) {
            $states[$key] = $value;
        }
        return $states;
    }
}

if (!function_exists('store_has_bot')) {
    function store_has_bot($bot_id, $platform)
    {
        $storeId = auth()->user()->store->id;
        return \App\Models\StoreBot::query()->where([
            'store_id' => $storeId,
            'platform' => $platform,
            'platform_id' => $bot_id,
        ])->exists();
    }
}

if (!function_exists('get_enabled_payment_methods')) {
    function get_enabled_payment_methods()
    {
        $enabled_payment_methods = [];

        if (auth()->check()) {
            $store_id = auth()->user()->store->id;
        } else {
            $store_id = request('store_id') ?? session('store_id');
        }

        $all_payment_methods = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'payment_method'],
        ])->first();

        if (!$all_payment_methods) {
            return [];
        }

        $arr = $all_payment_methods->value;

        foreach ($arr as $key => $value) {
            if ($value['enabled'] ?? false) {

                $class = config('payment_methods')[$key] ?? false;
                if ($class) {
                    $paymentObject = new $class($store_id, (array)$value);
                    $paymentObject->setLabel(__('payment_methods.payment_methods.' . $class));
                    if ($key == 'cod' && ($value['label'] ?? false)) {
                        $paymentObject->setLabel($value['label']);
                    }

                    $enabled_payment_methods[] = $paymentObject;
                }

            }
        }
        return $enabled_payment_methods;
    }
}

if (!function_exists('get_cod_payment_method')) {
    function get_cod_payment_method()
    {
        $store_id = request('store_id') ?? session('store_id');

        $all_payment_methods = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'payment_method'],
        ])->first();

        if (!$all_payment_methods) {
            return [];
        }

        $arr = $all_payment_methods->value;

        foreach ($arr as $key => $value) {
            if ($key == 'cod') {
                $class = config('payment_methods')[$key];
                $paymentObject = new $class($store_id, (array)$value);

                if ($value['label'] ?? false) {
                    $paymentObject->setLabel($value['label']);
                }

                return $paymentObject;
            }
        }
    }
}

if (!function_exists('can_access_feature')) {
    function can_access_feature($feature)
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $permission = Permission::where('name', $feature)->first();
        $model_has_permission = DB::table('model_has_permissions')
            ->where([
                ['model_id', $user->id],
                ['permission_id', $permission->id],
            ])->first();

        if (!$model_has_permission) {
            return;
        }

        if (!$model_has_permission->expires_at) {
            return;
        }

        $expiresAt = Carbon::parse($model_has_permission->expires_at);

        return $user->hasRole('store-owner') && $user->hasPermissionTo($feature) && $expiresAt->gte(now());
    }
}

if (!function_exists('storeHasPermission')) {
    function storeHasPermission($feature, $store_id)
    {
        $store = Store::where('id', $store_id)->first();
        $user = $store->user()->first();

        if (!$user) {
            return;
        }

        $permission = Permission::where('name', $feature)->first();
        $model_has_permission = DB::table('model_has_permissions')
            ->where([
                ['model_id', $user->id],
                ['permission_id', $permission->id],
            ])->first();

        if (!$model_has_permission) {
            return;
        }

        if (!$model_has_permission->expires_at) {
            return;
        }

        $expiresAt = Carbon::parse($model_has_permission->expires_at);

        return $store->is_active && $expiresAt->gte(now());
    }
}

if (!function_exists('is_mobile')) {
    function is_mobile()
    {
        return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/', request()->userAgent());
    }
}

if (!function_exists('transform_content_to_arabic')) {
    function transform_content_to_arabic($html)
    {
        $arabic = new \ArPHP\I18N\Arabic();

        $p = $arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $html = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        return $html;
    }
}

if (!function_exists('generate_invoice')) {
    function generate_invoice(Order $order)
    {
        $invoice = new Invoice($order);
        $fileName = $invoice->prepareInvoice();
        $url = Storage::disk('public')->url($fileName);
        return $url;
    }
}

if (!function_exists('getStoreSteps')) {
    function getStoreSteps()
    {
        $steps = null;

        if (auth()->check()) {
            $store_id = auth()->user()->store->id;
        } else {
            $store_id = request('store_id') ?? session('store_id');
        }

        $bot_settings = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $value = $bot_settings->value;
            if (array_key_exists('steps', $value)) {
                $steps = $value['steps'];
            }
        }

        return $steps;
    }
}

if (!function_exists('ifHourEnabled')) {
    function ifHourEnabled()
    {
        if (auth()->check()) {
            $store_id = auth()->user()->store->id;
        } else {
            $store_id = request('store_id') ?? session('store_id');
        }

        $bot_settings = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings && array_key_exists('hour', $bot_settings->value)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_payment_method_message')) {
    function get_payment_method_message()
    {
        $message = __('app.payment_method_message');
        $store_id = request('store_id') ?? session('store_id');

        $bot_settings = StoreSettings::where([
            ['key', 'bot_settings'],
            ['store_id', $store_id],
        ])->first();

        if ($bot_settings && array_key_exists('payment_method', $bot_settings->value)) {
            $message = $bot_settings->value['payment_method'];
        }

        return $message;
    }
}

if (!function_exists('translateNumber')) {
    function translateNumber($number)
    {
        return strtr(strval($number), array('٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }
}

if (!function_exists('updateMenus')) {
    function updateMenus($store_id)
    {
        // update telegram menu
        $telegramBot = StoreBot::where([
            ['store_id', $store_id],
            ['platform', 'telegram'],
            ['active', true],
            ['token_type', 'api_key'],
        ])->first();

        if ($telegramBot) {
            $telegramBot->setTelegramCommand();
        }

        // update messenger menu
        $facebookPages = StoreBot::where([
            ['store_id', $store_id],
            ['platform', 'facebook'],
            ['active', true],
            ['token_type', Facebook::PAGE_TOKEN],
        ])->get();

        if ($facebookPages) {
            foreach ($facebookPages as $page) {
                (new MessengerController)->setPageMessengerProfile($page->token, $page->platform_id);
            }
        }
    }
}

if (!function_exists('ifAppLocal')) {
    function ifAppLocal()
    {
        $local = false;
        $vars = ['debug', 'development', 'local'];
        if (in_array(env('APP_ENV'), $vars)) {
            $local = true;
        }

        return $local;
    }
}

if (!function_exists('get_additional_message')) {
    function get_additional_message()
    {
        $message = '';
        $store_id = request('store_id') ?? session('store_id');

        $bot_settings = StoreSettings::where([
            ['key', 'bot_settings'],
            ['store_id', $store_id],
        ])->first();

        if ($bot_settings) {
            $settings = $bot_settings->value;
            if (array_key_exists('additional_message', $settings) && array_key_exists('status', $settings['additional_message'])) {
                $message = $settings['additional_message']['text'];
            }

        }

        return $message;
    }
}

if (!function_exists('get_notes_label')) {
    function get_notes_label()
    {
        $label = __('responses.cart.enter_notes');

        if (auth()->check()) {
            $store_id = auth()->user()->store->id;
        } else {
            $store_id = request('store_id') ?? session('store_id');
        }

        $bot_settings = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists('notes', $bot_settings) && $bot_settings['notes']) {
                $label = $bot_settings['notes'];
            }

        }

        return $label;
    }
}

if (!function_exists('get_default_filter_date')) {
    function get_default_filter_date()
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->toDateString();
        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}

if (!function_exists('get_subscription_info')) {
    function get_subscription_info()
    {
        $subscriptionsInfo = [];
        $user = auth()->user();

        $services = Product::query()->withoutGlobalScope('store_access')->whereJsonContainsKey('additional->permission_name')->get();

        foreach ($services as $service) {
            $permission = Permission::where('name', $service->additional['permission_name'])->first();
            $model_has_permission = DB::table('model_has_permissions')
                ->where([
                    ['model_id', $user->id],
                    ['permission_id', $permission->id],
                ])->first();

            if ($model_has_permission && $model_has_permission->expires_at) {
                $expires_at_date = Carbon::parse($model_has_permission->expires_at);
                if ($expires_at_date->gt(now()) && $expires_at_date->diffInDays(now()) <= 10) {
                    $subscriptionsInfo[$permission->name] = $expires_at_date->diffInDays();
                }
            }
        }
        return $subscriptionsInfo;
    }
}

if (!function_exists('getThemeForStore')) {
    function getThemeForStore($storeId)
    {
        return in_array($storeId, [
            1,
            20,
            10,
        ]) ? 1 : 0;
    }
}

if (!function_exists('getPaymentClassByKey')) {
    function getPaymentClassByKey($paymentKey)
    {
        $paymentClass = config('payment_methods.' . $paymentKey);

        return $paymentClass;
    }
}

if (!function_exists('get_additional_questions')) {
    function get_additional_questions($idx)
    {
        $store_id = request('store_id') ?? session('store_id');

        $bot_settings = StoreSettings::where([
            ['store_id', $store_id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
            if (array_key_exists("question$idx", $bot_settings)
                && $bot_settings["question$idx"]
            && array_key_exists("question$idx", $bot_settings['steps'] ?? [])) {
                return $bot_settings["question$idx"];
            }

        }

        return null;
    }
}
