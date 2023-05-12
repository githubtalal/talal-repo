<?php

namespace App\PaymentMethods;

use App\Models\Cart;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;

class SyriatelCashApi extends PaymentMethod
{
    protected $label = 'Syriatel Cash API';

    private $baseURL = 'https://merchants.syriatel.sy:1443/ePayment_external_Json/rs/ePaymentExternalModule/';
    private $paymentRef;
    private $otpCode;

    private $transactionId;
    private $token;

    public function getKey(): string
    {
        return 'syriatel-cash-api';
    }

    public function parseResponse($data): PaymentMethod
    {
        $this->paymentRef = $data['payment_ref'] ?? null;
        $this->otpCode = $data['otp_code'] ?? null;
        $this->token = $data['token'] ?? null;


        return $this;
    }

    public function needsRedirect(): bool
    {
        return true;
    }

    public function getTransactionNumber(): ?string
    {
        return $this->transactionId;
    }

    public function isPaymentSucceeded(): bool
    {
        try {
            $config = $this->getPaymentConfig();

            $merchantGsm = $config['merchant_gsm'];
            $merchantGsm = '0' . substr($merchantGsm, 1, 9);

            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'paymentConfirmation', [
                'json' => [
                    'OTP' => $this->otpCode,
                    'merchantMSISDN' => $merchantGsm,
                    'transactionID' => $this->paymentRef,
                    'token' => $this->token,
                ],
                'verify' => false,
            ]);

            $responseBody = json_decode((string)$response->getBody());

            if ($responseBody->errorCode == 0) {
                $this->transactionId = $this->paymentRef;
                return true;
            }

            logger('Syriatel paymentConfirmation. failed', [
                'error' => $responseBody->errorCode ?? null,
                'error_message' => $responseBody->errorDesc ?? null,
                'payment_ref' => $this->paymentRef,
            ]);

        } catch (\Exception $e) {
            logger('Error while trying to do "doPayment" in MTN Cash', [
                'exception_message' => $e->getMessage(),
                'payment_ref' => $this->paymentRef,
            ]);
        }
        return false;
    }

    public function getRedirectUri(Cart $cart, $additional = []): ?string
    {
        $token = $this->getMerchantToken();
        if (!$token) return null;
        $config = $this->getPaymentConfig();

        $customerGsm = $cart->additional['additional_fields']['syriatel_customer_gsm'] ?? null;

        if (!$customerGsm)
            return null;
        $customerGsm = '0' . substr($customerGsm, 1, 9);

        $merchantGsm = $config['merchant_gsm'];
        $merchantGsm = '0' . substr($merchantGsm, 1, 9);

        $transaction_id = $cart->payment_ref;

        if (!app()->isProduction())
            $amount = 1;
        else
            $amount = $cart->total;


        try {
            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'paymentRequest', [
                'json' => [
                    'customerMSISDN' => $customerGsm,
                    'merchantMSISDN' => $config['merchant_gsm'],
                    'amount' => str($amount),
                    'transactionID' => $transaction_id,
                    'token' => $token,
                ],
                'verify' => false,
            ]);
            $responseBody = json_decode((string)$response->getBody());

            if ($responseBody->errorCode == 0) {
                $data = [
                    'callback_url' => $this->getCallBackUrl($cart, $additional),
                    'payment_ref' => $transaction_id,
                    'customer_gsm' => $customerGsm,
                    'token' => $token,
                ];

                return URL::temporarySignedRoute('syriatel.otp', now()->addMinutes(15), [
                    'ref' => encrypt(json_encode($data)),
                ]);
            }

            logger('Init payment failed in Syriatel Cash', [
                'error_code' => $responseBody->errorCode ?? '',
                'error_message' => $responseBody->errorDesc ?? '',
                'customer_gsm' => $customerGsm,
                'merchant_gsm' => $merchantGsm
            ]);

            return null;

        } catch (\Exception $e) {
            logger('Error while initiating Syriatel cash payment', [
                'exception' => $e->getMessage(),
                'customer_gsm' => $customerGsm,
                'merchant_gsm' => $merchantGsm,
                'store_id' => $this->storeId,
            ]);
        }
        return null;
    }

    public function getPaymentInfo(): array
    {
        return [
            'transaction_id' => $this->transactionId,
        ];
    }

    public function getBaseBasicInfo(): array
    {
        return [
            [
                'name' => 'user_name',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
            [
                'name' => 'password',
                'type' => 'password',
                'autocomplete' => 'false',
            ],
            [
                'name' => 'merchant_gsm',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
        ];
    }

    public function getBasicInfo(): array
    {
        return [
            [
                'name' => 'merchant_gsm',
                'type' => 'text',
                'autocomplete' => 'false',
            ],
        ];
    }

    private function getMerchantToken()
    {
        $paymentConfig = $this->getPaymentConfig();
        $basePaymentConfig = $this->getBasePaymentConfig();

        $userName = $basePaymentConfig['user_name'];
        $password = $basePaymentConfig['password'];

        try {
            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'getToken', [
                'verify' => false,
                'json' => [
                    'username' => $userName,
                    'password' => $password,
                ],
            ]);
            $responseBody = json_decode((string)$response->getBody());

            if ($responseBody->errorCode == 0) {
                return $responseBody->token;
            }

            logger('Error after getting merchant token from Syriatel', [
                'error_message' => $responseBody->errorDesc ?? '',
                'error_code' => $responseBody->errorCode ?? '',
                'store_id' => $this->storeId,
            ]);
        } catch (\Exception $e) {

            logger('[FAILURE] Error while trying to get merchant token from Syriatel', [
                'store_id' => $this->storeId,
                'exception' => $e->getMessage(),
            ]);
        }

    }

    public function getDescription(): string
    {
        return 'عند اختيارك الدفع عن طريق سيرياتيل كاش، سيتم ارسال رمز التحقق لك لاتمام العملية';
    }

    public function getExtraUserFields()
    {
        return [
            [
                'label' => __('app.user_extra_fields.syriatel_customer_gsm'),
                'name' => 'syriatel_customer_gsm',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}",
                'placeholder' => '09XXXXXXXX'
            ],
        ];
    }
}
