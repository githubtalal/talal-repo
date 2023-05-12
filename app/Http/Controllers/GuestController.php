<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Facebook\Facebook;
use App\Facebook\Messages\Message;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function init()
    {
        if ($token = \request()->get('user_token')) {
            $token = Crypto::decrypt($token);
            [$userId, $storeToken] = explode('-', $token);
        } else {
            $storeToken = request()->get('token');
            $userId = request()->get('user_id');
        }

        $store = Store::query()->where('token', $storeToken)->first();
        $cookie = Cookie::make('User-Id', $userId, null, config('app.url'));
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not defined',
            ]);
        }

        if (request()->get('user_token')) {
            $userId = request()->get('user_token');
        } else {
            $userId = generate_user();
        }
        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'button_text' => $store->button_text,
            'button_color' => $store->button_color,
            'button_style' => asset('Baseet/images/' . ($store->button_style ?? 'Artboard-8.svg')),
        ])->withCookie($cookie);
    }

    public function initStore()
    {
        $guestToken = request()->get('guest');
        try {
            [$userId, $storeToken] = explode('-', Crypto::decrypt($guestToken));
        } catch (\Exception$e) {
            abort(400);
        }

        $store = Store::query()->where('token', $storeToken)->first();

        if (!$store) {
            abort(400);
        }

        if (!storeHasPermission('website_widget', $store->id)) {
            $message = __('app.store_is_not_active');
            abort(403, $message);
        }

        Cart::query()->where('customer_uuid', $userId)->update(['active' => false]);

        session()->put('user_id', $userId);
        session()->put('store_id', $store->id);
//        dd($store);
        return redirect()->route('store.slug',$store->slug);
    }


    public function checkout()
    {
        $cart = Cart::query()->where([
            'customer_uuid' => session('user_id'),
            'active' => true,
        ])->first();

        if (!$cart) {
            abort(400);
        }

        return view('dashboard.store.checkout', compact('cart'));
    }

    public function addToCart(Product $product)
    {
        $cart = Cart::query()->where([
            'customer_uuid' => session('user_id'),
            'active' => true,
        ])->first();
        if (!$cart) {
            $cart = Cart::query()->create([
                'customer_uuid' => session('user_id'),
                'active' => true,
                'total' => 0,
                'store_id' => session('store_id'),
            ]);
        }

        $data = [
            'checkin' => request('checkin'),
            'checkout' => request('checkout'),
        ];
        $cartItem = CartItem::query()->create([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
            'price' => $product->price,
            'quantity' => request()->get('quantity') ?? 1,
            'additional' => $data,
        ]);

        $cartItem->update([
            'price' => $this->calculateItem($cartItem),
        ]);
        $cart->update([
            'total' => $this->calculateTotal(),
        ]);
        return redirect()
            ->route('store.store')
            ->with('success', 'Product added to cart');
    }

    public function saveOrder(Request $request)
    {
        $customer = Customer::query()->where([
            'uuid' => session('user_id'),
            'phone_number' => $request->get('phone_number'),
        ])->first();
        if (!$customer) {
            $customer = Customer::query()->create([
                'uuid' => session('user_id'),
                'phone_number' => $request->get('phone_number'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
            ]);
        } else {
            $customer->update([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
            ]);
        }

        $order = Order::query()->create([
            'store_id' => session('store_id'),
            'customer_id' => $customer->id,
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'governorate' => $request->get('governorate'),
            'phone_number' => $request->get('phone_number'),
            'total' => $this->calculateTotal(),
        ]);
        $this->createOrderItems($order);

        return $this->redirectToPayment($order);
    }

    private function calculateTotal()
    {
        $cart = cart();
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $this->calculateItem($item);
        }
        return $total;
    }

    private function calculateItem($cartItem)
    {
        if ($cartItem->product->type === 'product') {
            return ($cartItem->product->price * $cartItem->quantity);
        } else {
            if ($cartItem->product->require_end_date && ($cartItem->additional['checkout'])) {
                $checkInData = $cartItem->additional['checkin'];
                $checkOutData = $cartItem->additional['checkout'];

                $diff = Carbon::parse($checkOutData)->diffInDays($checkInData);

                return ($diff * $cartItem->product->price);
            } else {
                return $cartItem->product->price * $cartItem->quantity;
            }
        }
    }

    public function createOrderItems($order)
    {
        $cart = cart();
        foreach ($cart->items as $item) {
            OrderItem::query()->create([
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'total' => $item->price,
            ]);
        }

    }

    public function redirectToPayment($order)
    {
        $client = app('GuzzleClient')();
        $response = $client->post('https://egate-t.fatora.me/api/create-payment', [
            'auth' => ['MadFox', 'Mad@Fox123'],
            'verify' => false,
            'json' => [
                'lang' => 'en',
                'callbackURL' => route('store.callback', [
                    'order_id' => $order,
                ]),
                'terminalId' => '14740003',
                'amount' => $order->total,
                //                'cardNumber' => '9110016130235694',
                //                'expiryDate' => '31.05.2025',
                //                'expiryDate' => '2025-05',
            ],
            //            'headers' => [
            //                'Content-Type' => 'application/json'
            //            ],
        ]);
        $response = (string)$response->getBody();
        logger('response', json_decode($response, 1));
        $response = json_decode($response);

        if ($response->ErrorCode == 0) {

            $order->update([
                'payment_id' => $response->Data->paymentId,
            ]);

            return redirect()->away($response->Data->url);
        }
    }

    public function redirectAfterPayment()
    {
        $paymentRef = request('payment_ref');

        if (!$paymentRef) abort(400);
        $order = Order::query()->where('payment_ref', $paymentRef)->first();

        if (!$order || !$order->isPaid()) {
            return view('failed-payment', [
                'message' => __('responses.checkout.payment_failed'),
                'logo' => '',
            ]);
        }

        $logo = __('payment_methods.payment_logos.' . $order->payment_method);
        $logo = asset($logo);

        return view('payment-success', [
            'payment_id' => $order->trx_num,
            'order_id' => $order->id,
            'logo' => $logo,
        ]);
    }

    public function removeFromCart(Product $product)
    {
        $cart = \App\Models\Cart::query()->where([
            'customer_uuid' => session('user_id'),
            'active' => true,
        ])->first();
        \App\Models\CartItem::query()->where([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
        ])->delete();
        $cart->update([
            'total' => $this->calculateTotal(),
        ]);
        if ($cart->items->count() === 0) {
            return redirect()
                ->route('store.store')
                ->with('success', 'Product Deleted from cart');
        } else {
            return redirect()
                ->back()
                ->with('success', 'Product Deleted from cart');
        }

    }

    public function contact()
    {
        Mail::send('contact-us-email', ['object' => request()], function ($message) {
            $message->from(\request()->email, request()->name);

            $message->to('anas@madfox.solutions');
        });

        return redirect()->back()->with('success_message', __('contact.success'));
    }

    public function storeBySlug(Request $request, $slug)
    {
        $store = Store::query()->where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }

        if (!storeHasPermission('website_widget', $store->id)) {
            $message = __('app.store_is_not_active');
            abort(403, $message);
        }

        $user_id = $request->get('guest_id', Str::random());

        session()->put('store_id', $store->id);
        session()->put('user_id', $user_id);

        Cart::query()->where('customer_uuid', $user_id)->update(['active' => false]);

        return view('dashboard.store.store', compact('store'));

    }
}
