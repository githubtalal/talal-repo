<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'webhooks/*',
        'register-payment-processor',
        'register-payment-redirect',
        'gateway-v2/callback',
        'gateway-v2/redirect',
        'register-payment-callback',
        'payment-processor/*'
    ];
}
