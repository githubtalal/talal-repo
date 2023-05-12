<?php

namespace App\PaymentMethods;

class Fouad extends PaymentMethod
{

    protected $label = 'Fouad';

    public function getKey(): string
    {
        return 'fouad';
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
        return                     'حوالة مالية عبر شركة الفؤاد' .
            '<br>'.
            'يمكنك الدفع عن طريق  الفؤاد بإرسالة حوالة بحسب المعلومات التالية' .
            '<br>'.
            '  - الاسم:' .
            ($config['username'] ?? '') .
            '<br>'.
            '- رقم الموبايل: ' .
            ($config['phone number'] ?? '') .
            '<br>'.
            '- العنوان: ' .
            ($config['address'] ?? '') .
            '<br>'.
            '- المبلغ المراد دفعه'.
            '- قم بالتقاط صورة للإشعار و ارسلها لنا عبر واتس اب على الرقم '.
            ($config['notification_number'] ?? '');

    }
}
