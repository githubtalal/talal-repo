<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currency = __('app.currency_types.' . $this->currency ?? 'SYP');
        return [
            'id' => $this->id,
            'customer_uuid' => $this->customer_uuid,
            'total' => $this->total,
            'formatted_total' => price_format($this->total, $currency),
            'is_active' => $this->active,
            'customer_first_name' => $this->customer_first_name,
            'customer_last_name' => $this->customer_last_name,
            'customer_phone_number' => $this->customer_phone_number,
            'customer_governorate' => $this->customer_governorate,
            'customer_address' => $this->customer_address,
            'notes' => $this->notes,
            'payment_method' => $this->payment_method,
            'tax_total' => $this->tax_amount,
            'fees_total' => $this->fees_amount,
            'sub_total' => $this->total - $this->tax_total - $this->fees_total,
            'formatted_tax_total' => price_format($this->tax_amount, $currency),
            'formatted_fees_total' => price_format($this->fees_amount, $currency),
            'formatted_sub_total' => price_format($this->subtotal, $currency),
        ];
    }
}
