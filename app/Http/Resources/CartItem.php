<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $checkin = $this->resource->additional['checkin'] ?? null;
        $checkout = $this->resource->additional['checkout'] ?? null;

        $dateFormat = 'Y-m-d' . (ifHourEnabled() ? ' g:i A' : '');

        return [
            'id' => $this->id,
            'name' => $this->product->name,
            'price' => $this->price,
            'formatted_price' => price_format($this->price),
            'image' => Storage::disk('public')->url($this->product->image_url),
            'quantity' => $this->quantity,
            'product' => new Product($this->product),
            'additional'=> $this->resource->additional,
            'checkin' => $checkin ? Carbon::parse($checkin)->timezone('Asia/Damascus')->format($dateFormat) : null,
            'checkout' => $checkout ? Carbon::parse($checkout)->timezone('Asia/Damascus')->format($dateFormat) : null,

            'checkin_date' => $checkin ? Carbon::parse($checkin)->timezone('Asia/Damascus')->format('Y-m-d') : '',
            'checkout_date' => $checkout ? Carbon::parse($checkout)->timezone('Asia/Damascus')->format('Y-m-d') : '',
            'checkin_time' => $checkin ? Carbon::parse($checkin)->timezone('Asia/Damascus')->format('g:i A') : '',
            'checkout_time' => $checkout ? Carbon::parse($checkout)->timezone('Asia/Damascus')->format('g:i A') : '',
        ];
    }
}
