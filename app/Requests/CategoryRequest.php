<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'previous_image' => 'nullable',
            'image' => ['required_if:previous_image,null', 'image', File::image()->max(1024)],
        ];
    }

}
