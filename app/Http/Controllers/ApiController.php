<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\Cart as CartResource;
use App\Http\Resources\CartItem;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBot;
use App\Models\StoreSettings;
use Illuminate\Http\Request;
use App\Models\TelegramBot;
use App\Models\TelegramMessageHandler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    public function storeSettings()
    {
        $cart = $this->cart->getCart();
        $store = Store::query()->find(\request('store_id'));
        $logo = $store->logo ?? null;
        return response()->json([
            'success' => true,
            'cart' => $cart ? new \App\Http\Resources\Cart($cart) : null,
            'items' => $cart ? CartItem::collection($cart->items)->response()->getData() : [],
            'logo' => $logo ? Storage::url($logo) : null,
        ]);
    }

    public function getCart()
    {
        $cart = $this->cart->getCart();
        return response()->json([
            'success' => true,
            'cart' => $cart ? new \App\Http\Resources\Cart($cart) : null,
            'items' => $cart ? CartItem::collection($cart->items)->response()->getData() : [],
        ]);
    }

    public function categories()
    {
        $categories = Category::query()->where([
            'store_id' => request('store_id'),
            'active' => 1,
        ])->get();
        return CategoryResource::collection($categories)->response()->getData()->data;
    }

    public function products(Category $category = null)
    {
        if ($category) {
            $products = $category->products();
        } else {
            $products = Product::query();
        }

        $products = $products->where([
            'store_id' => request('store_id'),
            'active' => 1,
        ])->get();

        return ProductResource::collection($products);
    }

    public function addToCart(Product $product)
    {
        $data = request()->all();

        if ($data['checkin_time'] ?? false)
            $data['checkin'] .= ' ' . $data['checkin_time'];
        if ($data['checkout_time'] ?? false)
            $data['checkout'] .= ' ' . $data['checkout_time'];


        $cartItem = $this->cart->addProduct($product->id, $data);
        $cart = $this->cart->getCart();
        return response()->json([
            'success' => true,
            'cart_item' => new CartItem($cartItem),
            'cart' => new CartResource($cart),
            'items' => CartItem::collection($cart->items)->response()->getData(),
        ]);
    }

    public function removeFromCart($cartItemId)
    {
        $cart = $this->cart->removeFromCart($cartItemId);
        return response()->json([
            'success' => true,
            'cart' => $cart ? new \App\Http\Resources\Cart($cart) : null,
            'items' => $cart ? CartItem::collection($cart->items)->response()->getData() : [],
        ]);
    }

    public function updateQuantity(\App\Models\CartItem$cartItem)
    {
        $this->cart->updateItemQty($cartItem->id, request('quantity'));
        $cart = $this->cart->getCart();
        return response()->json([
            'cart' => $cart ? new \App\Http\Resources\Cart($cart) : null,
            'items' => $cart ? CartItem::collection($cart->items)->response()->getData() : [],
        ]);
    }

    public function getCheckout()
    {
        $cart = $this->cart->getCart();
        $payment_methods = array_map(function ($method) {

            return [
                'key' => $method->getKey(),
                'label' => $method->getLabel(),
                'desc' => $method->getDescription(),
                'logo' => $method->getLogo(),
                'extra_fields' => $method->getExtraUserFields(),
            ];
        }, get_enabled_payment_methods());
        if (!$cart) {
            return response()->json([
                'success' => false,
            ]);
        }

        $settings = StoreSettings::query()->where('store_id', request('store_id'))->where('key', 'bot_settings')->first();
        return response()->json([
            'success' => true,
            'cart' => new CartResource($cart),
            'cart_items' => CartItem::collection($cart->items),
            'payment_methods' => $payment_methods,
            'flow' => $settings->value ?? [],
        ]);
    }

    public function checkout()
    {
        $cart = $this->cart->getCart();
        $additional = $cart->additional ?? [];
        $additional['additional_fields'] = request('additional_user_fields', []);

        $cart->update([
            'payment_method' => request('method'),
            'customer_phone_number' => request('customer_phone_number'),
            'customer_first_name' => request('customer_first_name'),
            'customer_last_name' => request('customer_last_name'),
            'customer_address' => request('customer_address'),
            'customer_governorate' => request('customer_governorate'),
            'platform' => 'web',
            'payment_ref' => Str::random(),
            'notes' => request('notes'),
            'payment_redirect_uri' => 'guest.payment.redirect',
            'additional' => $additional,
        ]);
        $result = $this->cart->checkout($cart);

        if (is_string($result)) {
            return response()->json([
                'success' => true,
                'redirect_url' => $result,
            ]);
        } else if ($result instanceof Order) {
            return response()->json([
                'success' => true,
                'order' => $result,
                'additional_message' => get_additional_message(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'redirect_url' => null,
            ]);
        }
    }

    public function emptyCart()
    {
        $cartDeleted = $this->cart->deleteCart();
        return response()->json([
            'success' => $cartDeleted,
        ]);
    }

    public function updateCart(Request $request)
    {
        $cart = $this->cart->getCart();

        $data = [];

        foreach (array_merge($request->get('customer'), $request->only(['notes', 'payment_method'])) as $key => $value) {
            if ($value) {
                $data[$key] = $value;
            }
        }

        $cart->update($data);

        $cart->update([]);

        return response()->json([
            'success' => true,
            'data' => new CartResource($cart),
        ]);
    }

    public function savePaymentMethod()
    {
        $cart = $this->cart->getCart();

        $cart->update([
            'payment_method' => request('method'),
            'payment_ref' => Str::random(),
        ]);
        return true;
    }

    public function getStoreInfo()
    {
        $results = [];
        $store = Store::where('token', request('token'))->first();

        if ($store) {
            $results = [
                'store_name' => $store->name,
                'store_logo' => Storage::disk('public')->url($store->logo),
                'store_button_style' => $store->button_style,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    public function telegrambot($store_id)
    {
        // \Log::debug('In Telegram Bot');

        $storeBot = StoreBot::query()->where([
            'store_id' => $store_id,
            'platform' => 'telegram',
            'token_type' => 'api_key',
        ])->first();
        if ($storeBot) {

            if (!storeHasPermission('telegram_bot', $storeBot->store_id)) {
                logger('Store is inactive/ has expired feature');
                return;
            }

            //$handler->sendCommands();
            try {
                $handler = new TelegramMessageHandler();
                $bot = new TelegramBot($storeBot->token);
                $handler->telegram = $bot;
                $handler->store_id = $store_id;
                $handler->consumeMessage();
            } catch (\Exception $e) {
                logger('Exception while handling Telegram message', [
                    'exception' => $e->getMessage(),
                    'full_request' => request()->all(),
                    'store_id' => $storeBot->store_id,
                    'store_bot_id' => $storeBot->id,
                    'store_bot_token' => $storeBot->token,
                ]);
            }
        } else {
            logger('Got a message from undefined store bot', [
                'full_request' => request()->all(),
            ]);
        }
    }

    public function test()
    {
        $storeBot = StoreBot::query()->where('store_id', 2)->first();
        if ($storeBot) {
            $storeBot->setTelegramCommand();
        }
    }

    public function getStoreDetails(Request $request)
    {
        $slug = Str::slug($request->slug, '-');
        $s = [
            'faq' => 'FAQs',
            'who-are-we' => 'About_Us',
            'contact-us' => 'Contact_Us'
        ];
        if (isset($s[$slug])) {
            $detail = StoreSettings::query()
            ->where('store_id', request('store_id'))
            ->where('key', $s[$slug])
            ->pluck('value');

                return response()->json([
                    'success' => true,
                    'data' => $detail ?? []
                ]);
        } else
            abort(400);
    }

    public function getAllStoreDetails()
    {
        $result = DB::table('store_settings')->where('store_id', request('store_id'))->whereIn('key', ['FAQs', 'About_Us', 'Contact_Us'])->pluck('value');
            return response()->json([
                'data' => $result ?? []
            ]);
    }

    public function getProductInfo(Request $request)
    {
        $product = Product::find($request->product_id);

        if ($product)
        {
            $productInfo = new \App\Http\Resources\Product($product);
            return response()->json([
                'id' => $productInfo['id'],
                'name' => $productInfo['name'],
                'type' => $productInfo['type'],
                'price' => $productInfo['price'],
            ]);
            return $productInfo;

        } else

            return response()->json([
                'data' => false
            ]);

    }
}
