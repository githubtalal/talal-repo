<?php

namespace App\PaymentMethods;

use App\Models\Cart;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;

class MtnCashApi extends PaymentMethod
{
    protected $label = 'MTN Cash API';

    private $baseURL = 'https://services.mtnsyr.com:2021/';
    private $paymentRef;
    private $otpCode;

    private $transactionId;

    public function getKey(): string
    {
        return 'mtn-cash-api';
    }

    public function parseResponse($data): PaymentMethod
    {
        $this->paymentRef = $data['payment_ref'] ?? null;
        $this->otpCode = $data['otp_code'] ?? null;


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
            $token = $this->getMerchantToken();
            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'doPayment', [
                'form_params' => [
                    'inputObj' => json_encode([
                        'token' => $token,
                        'OTP' => $this->otpCode,
                        'BpartyTransactionID' => $this->paymentRef,
                    ]),
                    'verify' => false,
                ]]);
            $responseBody = json_decode((string)$response->getBody());

            if ($response->getStatusCode() == 200 && $responseBody->result) {
                $this->transactionId = $responseBody->data->transactionId;
                return true;
            }

            logger('MTN doPayment failed', [
                'error' => $responseBody->error ?? null,
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


        $customerGsm = $cart->additional['additional_fields']['mtn_customer_gsm'] ?? null;

        if (!$customerGsm)
            return null;

        $customerGsm = '963' . substr($customerGsm, 1, 9);
        $transaction_id = $cart->payment_ref;

        if (!app()->isProduction())
            $amount = 1;
        else
            $amount = $cart->total;


        try {
            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'paymentRequestInit', [
                'form_params' => [
                    'inputObj' => json_encode([
                        'token' => $token,
                        'customerGSM' => $customerGsm,
                        'amount' => str($amount),
                        'BpartyTransactionID' => $transaction_id,
                    ])
                ],
                'verify' => false,
            ]);
            $responseBody = json_decode((string)$response->getBody());

            if ($responseBody->result) {
                $data = [
                    'callback_url' => $this->getCallBackUrl($cart, $additional),
                    'payment_ref' => $transaction_id,
                    'customer_gsm' => $customerGsm,
                ];

                return URL::temporarySignedRoute('mtn.otp', now()->addMinutes(15), [
                    'ref' => encrypt(json_encode($data)),
                ]);
            }
            logger('Init payment failed in MTN Cash', [
                'error_code' => $responseBody->error,
                'error_message' => $responseBody->errorDesc ?? '',
                'customer_gsm' => $customerGsm,
            ]);

            return null;

        } catch (\Exception $e) {
            logger('Error while initing MTN cash payment', [
                'exception' => $e->getMessage(),
                'customer_gsm' => $customerGsm,
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

    public function getBasicInfo(): array
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

    private function getMerchantToken()
    {
        $paymentConfig = $this->getPaymentConfig();
        $userName = $paymentConfig['user_name'];
        $password = $paymentConfig['password'];
        $merchantGsm = $paymentConfig['merchant_gsm'];

        try {
            $httpClient = new Client();
            $response = $httpClient->post($this->baseURL . 'authenticateMerchant', [
                'verify' => false,
                'form_params' => ([
                    'inputObj' => json_encode([
                        'userName' => $userName,
                        'password' => $password,
                        'merchantGSM' => $merchantGsm,
                    ])
                ]),
            ]);
            $responseBody = json_decode((string)$response->getBody());

            if ($responseBody->result) {
                return $responseBody->data->token;
            }

            logger('Error after getting merchant token from MTN', [
                'error_message' => $responseBody->errorDesc ?? '',
                'error_code' => $responseBody->error,
                'merchant_gsm' => $merchantGsm,
            ]);
        } catch (\Exception $e) {

            logger('[FAILURE] Error while trying to get merchant token from MTN', [
                'merchant_gsm' => $merchantGsm,
                'exception' => $e->getMessage(),
            ]);
        }

    }

    public function getDescription(): string
    {
        $config = $this->getPaymentConfig();

        return 'عند اختيارك الدفع عن طريق كاش موبايل، سيتم ارسال رمز التحقق لك لاتمام العملية';
    }

    public function getExtraUserFields()
    {
        return [
            [
                'label' => __('app.user_extra_fields.customer_gsm'),
                'name' => 'mtn_customer_gsm',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}",
                'placeholder' => '09XXXXXXXX'
            ],
        ];
    }
}
