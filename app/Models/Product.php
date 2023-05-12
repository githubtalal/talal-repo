<?php

namespace App\Models;

use App\Traits\StoreAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;


class Product extends Model
{
    use HasFactory, StoreAccess, SoftDeletes, Loggable;

    protected $guarded = [];

    protected $casts = [
        'additional' => 'json',
    ];

    public function isReservation()
    {
        return $this->type == 'reservation';
    }

    public function requireEndDate()
    {
        return $this->isReservation() && $this->require_end_date;
    }

    public function base_image()
    {
        return Storage::disk('public')->url($this->image_url);
    }

    public function cover_images()
    {
        $covers = [];
        foreach ($this->images as $image) {
            $covers[] = Storage::disk('public')->url($image);
        }

        return $covers;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getHasOrdersAttribute()
    {
        $hasOrders = false;
        $orderItems = OrderItem::where('product_id', $this->id)->get();
        if (count($orderItems)) {
            $hasOrders = true;
        }
        return $hasOrders;
    }

    public function getFeesTax()
    {
        $customSettings = StoreSettings::where([
            ['key', 'custom_settings'],
            ['store_id', $this->store_id],
        ])->first();

        // get product fees
        if ($this->has_special_fees) {
            $fees = $this->fees_amount;
            $fees_type = $this->fees_type;
        } else {
            $fees = $customSettings->value['fees_amount'] ?? 0;
            $fees_type = $customSettings->value['fees_type'] ?? '';
        }

        // get product tax
        if ($this->has_special_tax) {
            $tax = $this->tax_amount;
            $tax_type = $this->tax_type;
        } else {
            $tax = $customSettings->value['tax_amount'] ?? 0;
            $tax_type = $customSettings->value['tax_type'] ?? '';
        }

        // calculate fees & tax by percentage
        if ($fees && $fees_type == 'percentage') {
            $fees = $this->price * ($fees / 100);
        }

        if ($tax && $tax_type == 'percentage') {
            $tax = $this->price * ($tax / 100);
        }

        return [
            'fees' => $fees,
            'tax' => $tax,
        ];
    }
}
