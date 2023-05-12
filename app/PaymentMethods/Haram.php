<?php

namespace App\PaymentMethods;

use App\Models\Cart;

class Haram extends PaymentMethod
{
    protected $label = 'Haram';
    public function getKey(): string
    {
        return 'haram';
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
                'name' => 'username',
                'type' => 'text',
                'autocomplete' => 'false'
            ],
            [
                'name' => 'phone number',
                'type' => 'text',
                'autocomplete' => 'false',
                'pattern' => "[09]{2}[0-9]{8}"
            ],
            [
                'name' => 'address',
                'type' => 'text',
                'autocomplete' => 'false'
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
        return 'حوالة مالية عبر شركة الهرم' .
            '<br>'.
            'يمكنك الدفع عن طريق  الهرم بإرسالة حوالة بحسب المعلومات التالية' .
            '<br>'.
            '- الاسم: ' .
            ($config['username'] ?? '') .
            '<br>'.
            '- رقم الموبايل: ' .
            ($config['phone number'] ?? '') .
            '<br>'.
            '- العنوان: ' .
            ($config['address'] ?? '') .
            '<br>'.
            '- المبلغ المراد دفعه'.
            '<br>'.
            '- قم بالتقاط صورة للإشعار و ارسلها لنا عبر واتس اب على الرقم '.
            ($config['notification_number'] ?? '');
    }
}
