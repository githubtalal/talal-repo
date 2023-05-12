<?php

namespace App\PaymentMethods;

class SyriatelCash extends PaymentMethod
{

    protected $label = 'Syriatel Cash';
    public function getKey(): string
    {
        return 'syriatel_cash';
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

    public function needsRedirect(): bool
    {
        return false;
    }

    public function getBasicInfo(): array
    {
        return [
            [
                'name' => 'phone number',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}",
            ],
            [
                'name' => 'notification_number',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}",
            ],
        ];
    }

    public function getDescription(): string
    {
        $config = $this->getPaymentConfig();
        return
            'عن طريق تطبيق أقرب إليك' .
            '<br>' .
            '- افتح تطبيق أقرب إليك' .
            '<br>' .
            '- اذهب لقسم سيرياتيل كاش' .
            '<br>' .
            '- ادخل الرمز السري الخاص بحسابك' .
            '<br>' .
            '- انقر على الدفع اليدوي' .
            '<br>' .
            '- ادخل رقم التاجر ' .
            ($config['phone number'] ?? '') .
            ' ثم أكده مرة ثانية' .
            '<br>' .
            '- ادخل المبلغ المطلوب دفعه وأكد المبلغ' .
            '<br>' .
            '- ادخل المبلغ المطلوب دفعه' .
            '<br>' .
            '- انتظر رسالة تاكيد عملية الدفع' .
            '<br>' .
            '- قم بالتقاط صورة للشاشة و ارسلها لنا عبر واتس اب على الرقم ' .
            ($config['notification_number'] ?? '');
    }
}
