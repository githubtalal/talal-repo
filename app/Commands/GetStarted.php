<?php

namespace App\Commands;

class GetStarted extends Command
{
    const SIG = 'get-started';

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
