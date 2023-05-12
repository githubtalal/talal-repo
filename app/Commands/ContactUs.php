<?php

namespace App\Commands;

use App\Models\StoreSettings;

class ContactUs extends Command
{
    const SIG = 'contact-us';

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

    public function get_contact_us()
    {
        $contactUs = StoreSettings::where([
            ['key', 'Contact_Us'],
            ['store_id', request('store_id')]
        ])->first();

        $value = json_decode($contactUs->value);
        return $value;
    }
}
