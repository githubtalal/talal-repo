<?php

namespace App\PaymentMethods;

use App\Models\Cart;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;

class Fatora extends PaymentMethod
{
    private $payment_id;

    protected $label = 'Fatora';
    public function getRedirectUri(Cart $cart, $additional = []): ?string
    {
        $config = $this->getPaymentConfig();
        $client = new Client();


        $data = [
            'lang' => 'ar',
            'callbackURL' =>  $this->getRedirectUrl($cart, $additional),
            'triggerURL' => $this->getProcessingUrl($cart, $additional),
            'terminalId' => $config['terminal_id'],
            'amount' => $cart->total,
        ];

        try {
            $endpoint = $this->isTest() ? 'https://egate-t.fatora.me/api/create-payment' : 'https://egate.fatora.me/api/create-payment';
            $response = $client->post($endpoint, [
                'auth' => [$config['username'], $config['password']],
                'verify' => false,

                'json' => $data,
            ]);

            $response = (string) $response->getBody();

            $response = json_decode($response);

            if ($response->ErrorCode == 0) {
                $this->payment_id = $response->Data->paymentId;

                return $response->Data->url;
            } else {
                logger('Fatora failed to create payment', [
                    'error_message' => $response->ErrorMessage,
                    'error_code' => $response->ErrorCode,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            logger('Error in fatora payment', [
                'cart_id' => $cart->id,
                'exception' => $e->getMessage(),
                'terminalId' => $config['terminal_id'],
            ]);
        }
        return null;
    }

    public function getKey(): string
    {
        return 'fatora';
    }

    public function parseResponse($data): PaymentMethod
    {
        $this->payment_id = $data['payment_id'];
        return $this;
    }

    public function isPaymentSucceeded(): bool
    {
        $client = new Client();

        try {
            $config = $this->getPaymentConfig();
            $username = $config['username'];
            $password = $config['password'];
            $endpoint = $this->isTest() ?
                "https://egate-t.fatora.me/api/get-payment-status/$this->payment_id"
                : "https://egate.fatora.me/api/get-payment-status/$this->payment_id";

            $response = $client->get($endpoint, [
                'auth' => [$username, $password],
                'verify' => false,
            ]);

            $response = (string)$response->getBody();

            $response = json_decode($response);

            if ($response->ErrorCode == 0) {
                if ($response->Data->status === 'A') {
                    $this->transactionNumber = $response->Data->rrn;
                    return true;
                }
            } else {
                logger('Failed from fatora when querying payment', [
                    'error_message' => $response->ErrorMessage,
                    'error_code' => $response->ErrorCode,
                ]);
            }
        } catch (\Exception $e) {
            logger('Error when querying fatora payment', [
                'payment_id' => $this->payment_id,
                'error_message' => $e->getMessage(),
            ]);
        }
        return false;
    }

    public function getPaymentInfo(): array
    {
        return [
            'payment_id' => $this->payment_id,
        ];
    }

    public function needsRedirect(): bool
    {
        return true;
    }

    public function getBasicInfo(): array
    {
        return [
            [
                'name' => 'username',
                'type' => 'text',
                'autocomplete' => 'false'
            ],
            [
                'name' => 'password',
                'type' => 'password',
                'autocomplete' => 'new-password'
            ],
            [
                'name' => 'terminal_id',
                'type' => 'text',
                'autocomplete' => 'terminal_id'
            ]
        ];
    }

    public function getDescription(): string
    {
        return   'عبر بوابة الدفع الالكتروني من فاتورة' .
            '<br>'.
            'يمكنك الدفع باستخدام البطاقات البنوك العاملة على شبكة فاتورة (بنك سورية الدولي الإسلامي - بنك الشام - بنك سورية والخليج - بنك البركة)';
    }


    public function getLogo()
    {
//        return null;
        return asset('Baseet/images/BlueFatoraLogo.svg');
    }

    public function hasTestEnv()
    {
        return true;
    }
}
