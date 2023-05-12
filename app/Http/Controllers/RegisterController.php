<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
const WIDGET_ID = 3;

class RegisterController extends Controller
{

    public function register()
    {
        session()->remove('store_id');
        session()->remove('user_id');
        $products = Product::query()->withoutGlobalScope('store_access')->whereJsonContainsKey('additional->permission_name')->get();
        session()->put('store_id', $products->first()->store_id);
        // TODO: Remove store_id from session after registeration
        return view('auth.register', compact('products'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'store_name' => 'required|max:255',
            'store_type' => 'required|max:255',
            'store_manager' => 'required|max:255',
            'phone_number' => 'required', 'digits:10',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required',
            'payment_method' => 'required',
            'products' => 'array|required',
            'otp_code' => 'required',
        ]);


        if (session('otp_code') != $request->otp_code) {

            return redirect()->back()->with('error','الرجاء التأكد من رمز التحقق');
        }


        // Put session id as user id to complete cart flow
        session()->put('user_id', Str::random(24));

        $cartClass = new Cart();
        $cart = $cartClass->getCart();

        if (!$cart) {
            $cart = $cartClass->createCart();
        }

        $cart->update(['currency' => 'SYP']);
        foreach ($request->products as $product) {
            $cartClass->addProduct(WIDGET_ID, [
                'date' => now(),
                'quantity' => $product
            ]);
        }

        $availableMethods = config('payment_methods');
        if (!isset($availableMethods[$request->get('payment_method')])) {
            abort(403);
        }

        $payment_method = new $availableMethods[$request->get('payment_method')](session('store_id'));

        $cart->update([
            'payment_method' => $payment_method->getKey(),
            'payment_info' => $payment_method->getPaymentInfo(),
            'additional' => [
                'store_info' => $request->all(),
                'additional_fields' => $request->get('additional_user_fields'),
            ],
            'customer_first_name' => $request->get('store_manager'),
            'customer_last_name' => '',
            'customer_phone_number' => $request->get('phone_number'),
            'customer_governorate' => 'Damascus',
            'payment_redirect_uri' => 'register.payment.redirect',
        ]);

        $result = $cartClass->checkout($cart);

        // Redirect Uri
        if (is_string($result)) {
            return redirect()->away($result);
        } else if ($result instanceof Order) { // Order [PENDING]
            return redirect('/')->with('success_message', __('responses.checkout.inperson_payment'));
        } else { // ERROR
            return redirect()->back()->with('error_message', 'عملية الدفع لم تنجح!
                الرجاء أختيار وسيلة دفع أخرى أو إعادة المحاولة.')->withInput($request->all());
        }
    }

    public function redirectAfterPayment()
    {
        $paymentRef = request('payment_ref');

        if (!$paymentRef) abort(400);

        $order = Order::query()->where('payment_ref', $paymentRef)->first();
        $store = Store::query()->where('order_id', $order->id ?? -1)->first();

        if (!$order || !$order->isPaid() || !$store) {
            return redirect('/')->with('error_message', __('app.payment_error.payment_error'));
        }

        $user = $store->user->first();

        if ($user) {
            Auth::login($user);
        }

        return redirect()->route('dashboard')->with('success_message', 'تم إنشاء المتجر بنجاح');
    }


    public function send_otp(Request $request)
    {

        $this->validate($request, [
            'phone_number' => 'required', 'digits:10',
        ]);

        if (OtpService::send_otp($request->phone_number)) {

            return response()->json(['success' => true], 200);
        } else {

            return response()->json(['success' => false], 405);
        }
    }


    public function validate_otp(Request $request)
    {

        if (session('otp_code') == $request->otp_code) {

            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => false], 405);
    }
}
