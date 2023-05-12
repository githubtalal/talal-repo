<?php

namespace App\Commands;

class PoweredByEcart extends Command
{
    const SIG = 'powered-by-ecart';

    public static function create(array $payload = []): Command
    {
        return new self();
    }

    public function run()
    {
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }
}
