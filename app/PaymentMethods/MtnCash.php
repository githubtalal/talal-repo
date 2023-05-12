<?php

namespace App\PaymentMethods;

use App\Models\Cart;

class MtnCash extends PaymentMethod
{
    protected $label = 'MTN Cash';

    public function getKey(): string
    {
        return 'mtn_cash';
    }

    public function parseResponse($data): PaymentMethod
    {
        return $this;
    }

    public function isPaymentSucceeded(): bool
    {
        return true;
    }

    public function getPaymentInfo(): array
    {
        return [];
    }

    public function getBasicInfo(): array
    {
        return [
            [
                'name' => 'phone number',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}"
            ],
            [
                'name' => 'notification_number',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}"
            ],
        ];
    }

    public function getDescription(): string
    {
        $config = $this->getPaymentConfig();

        return '                  عن طريق تطبيق كاش موبايل' .
            '<br>' .
            '                           - افتح تطبيق كاش موبايل' .
            '<br>' .
            '                    - ادخل الرمز السري الخاص بحسابك' .
            '<br>' .
            '               - انقر على الدفع ' .
            '<br>' .
            '              - ادخل رقم التاجر ' .
            ($config['phone number'] ?? '') .
            ' ثم أكده مرة ثانية' .
            '<br>' .
            '           - ادخل المبلغ المطلوب دفعه وأكد المبلغ' .
            '<br>' .
            '          - ادخل المبلغ المطلوب دفعه' .
            '<br>' .
            '    - انتظر رسالة تاكيد عملية الدفع' .
            '<br>' .
            '   - قم بالتقاط صورة للشاشة و ارسلها لنا عبر واتس اب على الرقم ' .
            ($config['notification_number'] ?? '');
    }
}
