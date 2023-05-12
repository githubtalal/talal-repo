<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Models\StoreSettings;

class Store extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => Storage::disk('public')->url($this->logo),
            'subname' => $this->subname,
            'has_faq' => (StoreSettings::query()->where('store_id', $this->id)->where('key', 'FAQs')->pluck('value')) != "[]" ? true : false,
            'has_who_are_we' => (StoreSettings::query()->where('store_id', $this->id)->where('key', 'About_Us')->pluck('value')) != "[]" ? true : false,
            'has_contact_us' => (StoreSettings::query()->where('store_id', $this->id)->where('key', 'Contact_Us')->pluck('value')) != "[]" ? true : false,
        ];
    }
}
