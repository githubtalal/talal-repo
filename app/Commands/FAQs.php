<?php

namespace App\Commands;

use App\Models\StoreSettings;

class FAQs extends Command
{
    const SIG = 'faq';

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

    public function get_Questions()
    {
        $questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', request('store_id')]
        ])->first();

        $questions = json_decode($questions->value);

        return $questions;
    }
}
