<?php

namespace App\PaymentMethods;

use App\Models\Cart;

abstract class Ecash extends PaymentMethod
{
    private $baseUrl = 'https://checkout.ecash-pay.com/Checkout';

    protected $isSuccess;
    protected $message;
    protected $orderRef;
    protected $amount;
    protected $token;

    public function parseResponse($data): PaymentMethod
    {
        $this->isSuccess = $data['IsSuccess'] ?? false;
        $this->message = $data['Message'];
        $this->orderRef = $data['OrderRef'];
        $this->transactionNumber = $data['TransactionNo'];
        $this->amount = $data['Amount'];
        $this->token = $data['Token'];

        return $this;
    }

    public function isPaymentSucceeded(): bool
    {
        $cart = Cart::query()->where('payment_ref', $this->orderRef)->first();
        if (!$cart) return false;


        return $this->isSuccess && $this->verifyToken($cart, $this->token);
    }

    public function verifyToken($cart, $token)
    {
        $paymentConfig = $this->getPaymentConfig();
        $merchantId = $paymentConfig['merchant_id'];
        $merchantSecret = $paymentConfig['merchant_secret'];
        $orderRef = $cart->payment_ref;

        $key = $merchantId;
        $key .= $merchantSecret;
        $key .= $this->transactionNumber;
        $key .= $this->amount;
        $key .= $orderRef;

        return strtoupper(md5($key)) == $token;

    }

    public function needsRedirect(): bool
    {
        return true;
    }

    public function getRedirectUri(Cart $cart, $additional = []): ?string
    {
        $paymentConfig = $this->getPaymentConfig();
        $checkoutType = $this->getCheckoutType();
        $terminal = $paymentConfig['terminal_id'];
        $merchantId = $paymentConfig['merchant_id'];
        $currency = 'SYP';
        $lang = 'AR';
        $orderRef = $cart->payment_ref;

        $redirectUrl = $this->getRedirectUrl($cart, $additional);
        $redirectUrl = urlencode($redirectUrl);

        $callBackUrl = $this->getProcessingUrl($cart, $additional);
        $callBackUrl = urlencode($callBackUrl);

        $verificationCode = $this->calculateVerificationCode($cart);
        $amount = $this->getTotal($cart);

        $checkoutUrl = $this->baseUrl;
        $checkoutUrl .= "/$checkoutType";
        $checkoutUrl .= "/$terminal";
        $checkoutUrl .= "/$merchantId";
        $checkoutUrl .= "/$verificationCode";
        $checkoutUrl .= "/$currency";
        $checkoutUrl .= "/$amount";
        $checkoutUrl .= "/$lang";
        $checkoutUrl .= "/$orderRef";
        $checkoutUrl .= "/$redirectUrl";
        $checkoutUrl .= "/$callBackUrl";

        return $checkoutUrl;
    }

    public function calculateVerificationCode($cart)
    {
        $paymentConfig = $this->getPaymentConfig();
        $merchantId = $paymentConfig['merchant_id'];
        $merchantSecret = $paymentConfig['merchant_secret'];
        $amount = $this->getTotal($cart);
        $orderRef = $cart->payment_ref;


        $key = $merchantId;
        $key .= $merchantSecret;
        $key .= str($amount);
        $key .= $orderRef;

        return strtoupper(md5($key));
    }

    public function getPaymentInfo(): array
    {
        return [
            'transaction_id' => $this->transactionNumber,
        ];
    }

    public function getBasicInfo(): array
    {
        return [
            [
                'name' => 'merchant_id',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
            [
                'name' => 'merchant_secret',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
            [
                'name' => 'terminal_id',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
        ];
    }

    abstract function getCheckoutType();

    public function getTotal($cart)
    {
        return app()->isProduction() ? $cart->total : 1600;
    }

}
