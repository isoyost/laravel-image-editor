<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'convert_to' => 'required_without:resize_to|in:png,jpeg,gif',
            'resize_to' => 'required_without:convert_to|array',
            'resize_to.width' => 'required|int|gt:0',
            'resize_to.height' => 'required|int|gt:0',
        ];
    }
}
