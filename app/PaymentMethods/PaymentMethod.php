<?php

namespace App\PaymentMethods;

use App\Models\Cart;
use App\Models\StoreSettings;

abstract class PaymentMethod
{

    protected $keyIdentifier;
    protected $identifier;
    protected $operationSucceeded;
    protected $transactionNumber;
    protected $storeId;
    protected $data;
    protected $error;
    protected $label;

    public function __construct($storeId = null, $data = null)
    {
        $this->storeId = $storeId;
        $this->data = $data;
    }


    public function getRedirectUri(Cart $cart, $additional = []): ?string
    {
        return null;
    }

    abstract public function getKey(): string;

    public function getLabel(): string
    {
        return $this->label;
    }

    abstract public function parseResponse($data): self;

    abstract public function isPaymentSucceeded(): bool;

    abstract public function getPaymentInfo(): array;

    abstract public function getBasicInfo(): array;

    public function getBaseBasicInfo(): array
    {
        return $this->getBasicInfo();
    }

    public function getTransactionNumber(): ?string
    {
        return $this->transactionNumber;
    }

    public function needsRedirect(): bool
    {
        return false;
    }

    protected function getPaymentConfig()
    {
        $config = StoreSettings::query()
            ->where('key', 'payment_method')
            ->where('store_id', $this->storeId)
            ->first();
        if (!$config) return null;
        return $config['value'][$this->getKey()];
    }

    protected function getBasePaymentConfig()
    {
        $config = StoreSettings::query()
            ->where('key', 'payment_method')
            ->where('store_id', 1) // eCart Store ID
            ->first();
        if (!$config) return null;
        return $config['value'][$this->getKey()];
    }

    public function getError()
    {
        return $this->error;
    }

    public function getDescription(): string
    {
        return '';
    }

    public function setLabel($label)
    {
        return $this->label = $label;
    }

    public function getExtraUserFields()
    {
        return [];
    }

    public function getLogo()
    {
        return null;
    }

    /**
     * Get the url that handle the payment and process the order without redirect
     * @param Cart $cart
     * @param array $additional
     * @return string
     */
    public function getProcessingUrl($cart, $additional = []): string
    {
        return $additional['processing_url'] ?? route('payment_processor.process', [
            'payment_ref' => $cart->payment_ref
        ]);
    }

    /**
     * Returns the redirect URL (No payment processing)
     * @param Cart $cart
     * @param $additional
     * @return string
     */
    public function getRedirectUrl($cart, $additional = []): string
    {
        return $additional['redirect_url'] ?? route('payment_processor.redirect', [
            'payment_ref' => $cart->payment_ref
        ]);
    }

    /**
     * Returns the url that process the payment and redirect to the corresponding view
     * @param Cart $cart
     * @param $additional
     * @return string
     */
    public function getCallBackUrl($cart, $additional = []): string
    {
        return $additional['callback_url'] ?? route('payment_processor.callback', [
            'payment_ref' => $cart->payment_ref
        ]);
    }

    public function hasTestEnv()
    {
        return false;
    }
    public function isTest(): bool
    {
        $config = $this->getPaymentConfig();

        return $config && isset($config['is_test']);
    }
}
