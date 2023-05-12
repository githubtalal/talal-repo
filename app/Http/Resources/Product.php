<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = Storage::disk('public')->url($this->image_url);
        $feesTaxInfo = $this->getFeesTax();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'formatted_price' => price_format($this->price, __('app.currency_types.' . $this->currency)),
            'fees' => $feesTaxInfo['fees'],
            'tax' => $feesTaxInfo['tax'],
            'formatted_fees' => price_format($feesTaxInfo['fees'], __('app.currency_types.' . $this->currency)),
            'formatted_tax' => price_format($feesTaxInfo['tax'], __('app.currency_types.' . $this->currency)),
            'formatted_subtotal' => price_format($this->price + $feesTaxInfo['fees'] + $feesTaxInfo['tax'], __('app.currency_types.' . $this->currency)),
            'cover' => $image,
            'album' => [$image, $image, $image],
            'type' => $this->type,
            'description' => $this->description ?? false,
            'description_with_style' => preg_replace('/<p>(.*?)<\/p>/', '<span>$1</span>', $this->description),
            'description_without_style' => html_entity_decode(strip_tags($this->description)),
            'require_end_data' => $this->require_end_date,
            'require_hour' => ifHourEnabled(),
        ];
    }
}
