<?php

namespace App\Commands;

class SetExtraUserFields extends Command
{
    private $idx;
    private $fieldValue;
    const SIG = 'set-extra-user-fields';

    public static function create(array $payload = []): Command
    {
        $command = new self();
        $command->idx = $payload[0];
        $command->fieldValue = $payload[1];

        return $command;
    }

    public function run()
    {
        $cart = $this->cartRepository->getCart();
        $additional = $cart->additional;
        $additional['additional_user_fields_' . $cart->payment_method][$this->getfieldInfo($this->idx)['name']] = $this->fieldValue;

        $next = $this->idx + 1;
        if (!$this->getfieldInfo($next)) {
            $additional[$cart->payment_method . '_is_complete'] = true;
        }

        $cart->update([
            'additional' => $additional,
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return '';
    }

    public function getfieldInfo($index)
    {
        $cart = $this->cartRepository->getCart();
        $paymentMethod = config('payment_methods.' . $cart->payment_method);
        $payment = new $paymentMethod();
        $fields = $payment->getExtraUserFields();
        return $fields[$index] ?? null;
    }

    public function getMethod()
    {
        return $this->cartRepository->getCart()->payment_method;
    }

    public function getIdx()
    {
        return $this->idx;
    }
}
