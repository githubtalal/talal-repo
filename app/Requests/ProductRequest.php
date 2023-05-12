<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required',
            'fees_type' => 'required_if:has_special_fees,on',
            'percentage_fees_amount' => 'required_if:fees_type,percentage',
            'number_fees_amount' => 'required_if:fees_type,number',
            'tax_type' => 'required_if:has_special_tax,on',
            'percentage_tax_amount' => 'required_if:tax_type,percentage',
            'number_tax_amount' => 'required_if:tax_type,number',
            'previous_image' => 'nullable',
            'image_url' => ['required_if:previous_image,null', 'image', File::image()->max(1024)],
            'type' => 'required',
        ];
    }

}
