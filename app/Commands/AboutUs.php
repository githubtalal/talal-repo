<?php

namespace App\Commands;

use App\Models\StoreSettings;

class AboutUs extends Command
{
    const SIG = 'about-us';

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

    public function get_about_us()
    {
        $contactUs = StoreSettings::where([
            ['key', 'About_Us'],
            ['store_id', request('store_id')]
        ])->first();

        $value = json_decode($contactUs->value);
        return $value;
    }
}
