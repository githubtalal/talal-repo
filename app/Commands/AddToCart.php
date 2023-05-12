<?php

namespace App\Commands;

use App\Cart;
use App\Facebook\Facebook;

class AddToCart extends Command
{
    const PRODUCT_ID = 'product_id';
    const SIG = 'add-to-cart';

    private $produdctId;
    private $isCurrecymatch = true;

    public static function create(array $payload = []): self
    {
        // Sig: add-to-cart_{ProductID}
        $instance = new self();
        $instance->produdctId = $payload[0] ?? null;

        return $instance;
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::PRODUCT_ID]))
            throw new \Exception('Missing product id parameter');

        return self::SIG . '_' . $properties[self::PRODUCT_ID];
    }

    public function run()
    {
        $cartClass = new Cart;

        if (!$cartClass->can_add_product_to_cart($this->produdctId)) {
            $this->isCurrecymatch = false;
            return;
        }

        $cart = $cartClass->getCart();

        $cart->update([
            'platform' => Facebook::SIGNATURE,
            'payment_redirect_uri' => 'guest.payment.redirect'
        ]);

        $product = $this->productRepository->find($this->produdctId);
        if (!$product->isReservation()) {
            $this->cartRepository->addProduct($this->produdctId);
            $this->cartRepository->getCart()->update([
                'platform' => Facebook::SIGNATURE
            ]);
        }
    }

    public function getProductId()
    {
        return $this->produdctId;
    }

    public function getProduct()
    {
        return $this->productRepository->find($this->produdctId);
    }

    public function isCurrecymatch()
    {
        return $this->isCurrecymatch;
    }
}
